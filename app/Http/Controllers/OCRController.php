<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use App\Services\QuotaService;
use DateTimeImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use RuntimeException;

class OCRController extends Controller
{
    private const ETOLL_PROMPT = <<<'PROMPT'
Inspect the attached image and return only valid JSON in this exact top-level shape:
{"transactions":[...]}

Apply this document-priority rule before extracting any transaction: if the image contains the title "FORM KONDISI KHUSUS" or "FORM INFORMASI TRANSAKSI TOL"/"FORM INFORMASI TRANSAKSI TOLL", supported by at least two form labels such as "Gerbang Masuk", "Gerbang Keluar", "Tanggal Transaksi", "Waktu Transaksi", or "Nominal Toll", treat it as a special form. Extract only that form and ignore every other toll receipt or transaction visible in the same image. Map "Gerbang Masuk" to "asal", "Gerbang Keluar" to "lokasi", combine "Tanggal Transaksi" and "Waktu Transaksi" as "tanggal_jam", and map "Nominal Toll" to "amount". A form time written as HH:MM must be returned as HH:MM:00. If the special form is blank or its transaction fields cannot be read, return an empty transactions array; do not fall back to other receipts in that image.

Otherwise, first verify that the document is a toll receipt/slip before extracting anything. Look for toll-specific evidence such as the standalone word "TOL", "E-TOLL", "GERBANG TOL", "GT", "JASAMARGA", an entry/exit gate, or a recognizable toll-road name. Do not treat an unrelated receipt as a toll slip merely because it contains a date or Rupiah amount. If there is no credible toll-specific evidence, return {"transactions":[]}.

If it is a toll slip, extract every e-toll transaction visible in the image. If multiple receipts or slip rows are shown (e.g. a list of transaction history), extract each as a separate object. Every transaction object must contain exactly these keys:
- "tanggal_jam": string or null. Transcribe the printed date and time in DD/MM/YYYY HH:mm:ss order. Indonesian e-toll receipts use DAY/MONTH/YEAR, never MONTH/DAY/YEAR. The first number is the day and the second is the month, even when both are 12 or less. Preserve that semantic order for '/', '-', or spaces around separators. For example, "03/07/2026 06:35:45" means 3 July 2026 and must remain "03/07/2026 06:35:45". The backend converts this value to YYYY-MM-DD HH:mm:ss. Return null if missing.
- "amount": number or null. Toll fee paid in IDR, numeric value only. Strip "Rp", dot thousand-separators, and spaces (e.g. "Rp46.000" becomes 46000). Use this priority: (1) the price on the same line as "e-toll" or "E-TOLL"; (2) if that label is absent or unreadable but there is credible toll evidence, a visible Rupiah value labeled "Tarif", "Biaya", "Total", "Bayar", or similar; (3) if no price label is visible, the single most plausible currency-formatted value inside the same transaction block, close to its date/time and toll location. This fallback selects a visible value; never invent or calculate an amount. Do not extract saldo/balance, card numbers, transaction IDs, reference numbers, or gate codes. If multiple values remain ambiguous, use null. When fallback (2) or (3) is used, lower confidence and add a warning that the amount was inferred because the "e-toll" label was not visible. Return null if missing.
- "lokasi": string or null. Toll gate/location name, such as "REMBANG", "WARU 5", or "KEJAPANAN UTAMA". Return null if missing.
- "asal": string or null. Origin/entry gate (look for "Asal GB" or "ASAL"). Return the name only; strip bracketed codes such as "[06 03]". Return null if the label is absent.
- "confidence": number from 0 to 1, legibility score for this individual transaction.
- "warnings": array of strings describing per-transaction difficulties (blur, partial crop, glare, ambiguous value). Use [] if fully legible.

Do not include markdown or explanatory text outside the JSON.
PROMPT;

    private const PARKING_PROMPT = <<<'PROMPT'
Inspect the attached image and return only valid JSON with exactly these keys:
{"amount":null,"confidence":0,"warnings":[]}

First verify that the image shows a parking receipt, parking ticket, or parking payment transaction. If it is not clearly a parking document, return amount null, confidence 0, and a warning explaining that no parking receipt was identified.

Extract only the parking fee actually paid. Prefer a visible parking-specific "Total", "Bayar", "Biaya Parkir", or equivalent final amount. Return the amount as a number in IDR with no currency symbol or thousand separators (for example, "Rp12.000" becomes 12000). Do not use card balance, top-up, change, deposits, maximum tariffs, unrelated purchases, or transaction/reference numbers. Never invent or calculate an amount; return null when it is missing or ambiguous.

Return confidence as a number from 0 to 1 reflecting legibility. Return warnings as an array of strings describing issues such as blur, crop, glare, or an ambiguous amount; use [] if fully legible. If multiple separate parking receipts are visible and one cannot be identified as the intended receipt, return amount null with a warning; do not sum them.

Do not include markdown or explanatory text outside the JSON.
PROMPT;

    public function extractEtoll(Request $request, GeminiService $gemini, QuotaService $quota): JsonResponse
    {
        $result = $this->extractImage($request, $gemini, self::ETOLL_PROMPT);
        $normalized = $this->normalizeEtollResult($result);
        $quota->recordUsage('ocr/etoll');

        return response()->json($normalized);
    }

    public function extractParking(Request $request, GeminiService $gemini): JsonResponse
    {
        $result = $this->extractImage($request, $gemini, self::PARKING_PROMPT);

        return response()->json($this->normalizeParkingResult($result));
    }

    private function extractImage(Request $request, GeminiService $gemini, string $prompt): array
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $image = $request->file('image');
        if (! $image instanceof UploadedFile) {
            throw new RuntimeException('The uploaded OCR image is unavailable.');
        }

        $mimeType = $image->getMimeType();
        $path = $image->getRealPath();
        if (! is_string($mimeType) || ! is_string($path)) {
            throw new RuntimeException('Unable to inspect the uploaded OCR image.');
        }

        $imageData = file_get_contents($path);
        if ($imageData === false) {
            throw new RuntimeException('Unable to read the uploaded OCR image.');
        }

        return $gemini->generateJsonFromImage($prompt, $mimeType, $imageData);
    }

    private function normalizeEtollResult(array $result): array
    {
        $transactions = $result['transactions'] ?? null;
        if (! is_array($transactions)) {
            throw new RuntimeException('Gemini returned an invalid e-toll OCR result.');
        }

        $normalized = [];
        foreach ($transactions as $transaction) {
            $keys = ['tanggal_jam', 'amount', 'lokasi', 'asal', 'confidence', 'warnings'];
            if (! is_array($transaction)) {
                throw new RuntimeException('Gemini returned an invalid e-toll transaction.');
            }

            foreach ($keys as $key) {
                if (! array_key_exists($key, $transaction)) {
                    throw new RuntimeException('Gemini returned an incomplete e-toll transaction.');
                }
            }

            $warnings = $this->validateWarnings($transaction['warnings']);
            if (! $this->isValidAmount($transaction['amount'])) {
                throw new RuntimeException('Gemini returned an invalid e-toll amount.');
            }
            if (! $this->isNullableString($transaction['lokasi']) || ! $this->isNullableString($transaction['asal'])) {
                throw new RuntimeException('Gemini returned invalid e-toll location fields.');
            }
            if (! $this->isValidConfidence($transaction['confidence'])) {
                throw new RuntimeException('Gemini returned an invalid e-toll confidence score.');
            }

            $tanggalJam = $this->normalizeEtollDate($transaction['tanggal_jam'], $warnings);
            $normalized[] = [
                'tanggal_jam' => $tanggalJam,
                'amount' => $transaction['amount'],
                'lokasi' => $transaction['lokasi'],
                'asal' => $transaction['asal'],
                'confidence' => $transaction['confidence'],
                'warnings' => $warnings,
            ];
        }

        return ['transactions' => $normalized];
    }

    private function normalizeParkingResult(array $result): array
    {
        foreach (['amount', 'confidence', 'warnings'] as $key) {
            if (! array_key_exists($key, $result)) {
                throw new RuntimeException('Gemini returned an incomplete parking OCR result.');
            }
        }

        if (! $this->isValidAmount($result['amount'])) {
            throw new RuntimeException('Gemini returned an invalid parking amount.');
        }
        if (! $this->isValidConfidence($result['confidence'])) {
            throw new RuntimeException('Gemini returned an invalid parking confidence score.');
        }

        return [
            'amount' => $result['amount'],
            'confidence' => $result['confidence'],
            'warnings' => $this->validateWarnings($result['warnings']),
        ];
    }

    private function normalizeEtollDate($value, array &$warnings): ?string
    {
        if ($value === null) {
            return null;
        }
        if (! is_string($value)) {
            throw new RuntimeException('Gemini returned an invalid e-toll date/time.');
        }

        $value = trim($value);
        if ($value === '') {
            $warnings[] = 'The transaction date/time could not be read.';

            return null;
        }

        $value = preg_replace('/\s*([\/-])\s*/', '$1', $value);
        $value = is_string($value) ? preg_replace('/\s+/', ' ', trim($value)) : null;
        foreach (['!d/m/Y H:i:s', '!d-m-Y H:i:s', '!d m Y H:i:s', '!d/m/Y H:i', '!d-m-Y H:i', '!d m Y H:i'] as $format) {
            $date = is_string($value) ? DateTimeImmutable::createFromFormat($format, $value) : false;
            $errors = DateTimeImmutable::getLastErrors();
            if ($date !== false && ($errors === false || ($errors['warning_count'] === 0 && $errors['error_count'] === 0))) {
                return $date->format('Y-m-d H:i:s');
            }
        }

        $warnings[] = 'The transaction date/time could not be converted from DD/MM/YYYY HH:mm:ss.';

        return null;
    }

    private function validateWarnings($warnings): array
    {
        if (! is_array($warnings)) {
            throw new RuntimeException('Gemini returned invalid OCR warnings.');
        }

        foreach ($warnings as $warning) {
            if (! is_string($warning)) {
                throw new RuntimeException('Gemini returned invalid OCR warnings.');
            }
        }

        return array_values($warnings);
    }

    private function isValidAmount($amount): bool
    {
        return $amount === null
            || ((is_int($amount) || is_float($amount)) && is_finite($amount) && $amount >= 0);
    }

    private function isValidConfidence($confidence): bool
    {
        return (is_int($confidence) || is_float($confidence))
            && is_finite($confidence)
            && $confidence >= 0
            && $confidence <= 1;
    }

    private function isNullableString($value): bool
    {
        return $value === null || is_string($value);
    }
}
