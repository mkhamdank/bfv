<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

class QuotaService
{
    private const CONNECTION = 'quota';

    public function checkQuota(string $endpointSlug): void
    {
        try {
            $status = $this->readQuotaStatus($endpointSlug);
        } catch (Throwable $exception) {
            $this->logDatabaseFailure('check', $endpointSlug, $exception);

            if (config('quota.fail_closed', false)) {
                throw new HttpResponseException(response()->json([
                    'message' => 'Layanan quota sedang tidak tersedia. Silakan coba kembali.',
                    'endpoint_slug' => $endpointSlug,
                ], 503));
            }

            return;
        }

        if ($status === null || $status['used'] < $status['daily_limit']) {
            return;
        }

        $resetAt = CarbonImmutable::now('UTC')->startOfDay()->addDay();
        throw new HttpResponseException(response()->json([
            'message' => sprintf(
                'Kuota harian endpoint "%s" telah tercapai (%d dari %d pemakaian). Kuota direset pada tengah malam UTC (%s).',
                $endpointSlug,
                $status['used'],
                $status['daily_limit'],
                $resetAt->format('Y-m-d H:i:s')
            ),
            'endpoint_slug' => $endpointSlug,
            'daily_limit' => $status['daily_limit'],
            'used' => $status['used'],
            'reset_at' => $resetAt->toIso8601String(),
        ], 429));
    }

    public function recordUsage(
        string $endpointSlug,
        ?string $employee = null,
        ?int $inputTokens = null,
        ?int $outputTokens = null,
        ?int $totalTokens = null,
        ?array $metadata = null
    ): bool {
        $this->validateUsage($endpointSlug, $employee, $inputTokens, $outputTokens, $totalTokens);

        try {
            $connection = DB::connection(self::CONNECTION);
            $policyExists = $connection->table('quota_policies')
                ->where('endpoint_slug', $endpointSlug)
                ->exists();

            if (! $policyExists) {
                return false;
            }

            $now = CarbonImmutable::now('UTC');
            $connection->table('quota_usage')->insert([
                'endpoint_slug' => $endpointSlug,
                'usage_date' => $now->toDateString(),
                'employee' => $employee,
                'call_count' => 1,
                'input_tokens' => $inputTokens ?? 0,
                'output_tokens' => $outputTokens ?? 0,
                'total_tokens' => $totalTokens ?? 0,
                'metadata' => $metadata === null ? null : json_encode($metadata, JSON_THROW_ON_ERROR),
                'created_at' => $now->format('Y-m-d H:i:s'),
            ]);

            return true;
        } catch (Throwable $exception) {
            $this->logDatabaseFailure('record', $endpointSlug, $exception);

            return false;
        }
    }

    public function getQuotaStatus(string $endpointSlug): ?array
    {
        try {
            return $this->readQuotaStatus($endpointSlug);
        } catch (Throwable $exception) {
            $this->logDatabaseFailure('status', $endpointSlug, $exception);

            return null;
        }
    }

    private function readQuotaStatus(string $endpointSlug): ?array
    {
        $connection = DB::connection(self::CONNECTION);
        $policy = $connection->table('quota_policies')
            ->where('endpoint_slug', $endpointSlug)
            ->first();

        if ($policy === null) {
            return null;
        }

        $usageDate = CarbonImmutable::now('UTC')->toDateString();
        $usage = $connection->table('quota_usage')
            ->where('endpoint_slug', $endpointSlug)
            ->where('usage_date', $usageDate)
            ->selectRaw('COALESCE(SUM(call_count), 0) AS used')
            ->selectRaw('COALESCE(SUM(input_tokens), 0) AS input_tokens')
            ->selectRaw('COALESCE(SUM(output_tokens), 0) AS output_tokens')
            ->selectRaw('COALESCE(SUM(total_tokens), 0) AS total_tokens')
            ->selectRaw('MAX(created_at) AS last_used_at')
            ->selectRaw('COUNT(DISTINCT employee) AS unique_employees')
            ->first();
        $used = (int) $usage->used;
        $dailyLimit = (int) $policy->daily_limit;

        return [
            'endpoint_slug' => $endpointSlug,
            'daily_limit' => $dailyLimit,
            'used' => $used,
            'input_tokens' => (int) $usage->input_tokens,
            'output_tokens' => (int) $usage->output_tokens,
            'total_tokens' => (int) $usage->total_tokens,
            'remaining' => max(0, $dailyLimit - $used),
            'usage_percentage' => $dailyLimit === 0 ? null : round(($used / $dailyLimit) * 100, 2),
            'status' => $this->quotaStatus($dailyLimit, $used),
            'last_used_at' => $usage->last_used_at,
            'unique_employees' => (int) $usage->unique_employees,
            'policy_created_at' => $policy->created_at,
            'policy_updated_at' => $policy->updated_at,
            'reset_at' => CarbonImmutable::now('UTC')->startOfDay()->addDay()->toIso8601String(),
        ];
    }

    private function quotaStatus(int $dailyLimit, int $used): string
    {
        if ($dailyLimit === 0) {
            return 'BLOCKED';
        }

        $percentage = ($used / $dailyLimit) * 100;

        if ($percentage >= 100) {
            return 'EXCEEDED';
        }
        if ($percentage >= 90) {
            return 'WARNING';
        }
        if ($percentage >= 75) {
            return 'CAUTION';
        }

        return 'OK';
    }

    private function validateUsage(
        string $endpointSlug,
        ?string $employee,
        ?int $inputTokens,
        ?int $outputTokens,
        ?int $totalTokens
    ): void {
        if (trim($endpointSlug) === '' || strlen($endpointSlug) > 255) {
            throw new InvalidArgumentException('The quota endpoint slug must contain between 1 and 255 characters.');
        }

        if ($employee !== null && strlen($employee) > 199) {
            throw new InvalidArgumentException('The quota employee value must not exceed 199 characters.');
        }

        foreach ([$inputTokens, $outputTokens, $totalTokens] as $tokenCount) {
            if ($tokenCount !== null && $tokenCount < 0) {
                throw new InvalidArgumentException('Quota token counts must be non-negative database integers.');
            }
        }
    }

    private function logDatabaseFailure(string $operation, string $endpointSlug, Throwable $exception): void
    {
        try {
            Log::warning('API quota database operation failed.', [
                'operation' => $operation,
                'endpoint_slug' => $endpointSlug,
                'exception' => get_class($exception),
            ]);
        } catch (Throwable) {
        }
    }
}