<?php

namespace App\Services;

use GuzzleHttp\Client;
use InvalidArgumentException;
use RuntimeException;

class GeminiService
{
    private Client $httpClient;

    public function __construct(array $clientOptions = [])
    {
        $this->httpClient = new Client(array_merge([
            'base_uri' => 'https://generativelanguage.googleapis.com/v1beta/',
            'connect_timeout' => 10,
            'timeout' => 30,
        ], $clientOptions));
    }

    public function generateText(string $prompt): string
    {
        if (trim($prompt) === '') {
            throw new InvalidArgumentException('The Gemini prompt must not be empty.');
        }

        return $this->request([['text' => $prompt]]);
    }

    public function generateJsonFromImage(string $prompt, string $mimeType, string $imageData): array
    {
        if (trim($prompt) === '') {
            throw new InvalidArgumentException('The Gemini prompt must not be empty.');
        }

        if (! in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            throw new InvalidArgumentException('Gemini OCR supports JPEG, PNG, and WebP images.');
        }

        if ($imageData === '') {
            throw new InvalidArgumentException('The Gemini image must not be empty.');
        }

        $text = $this->request([
            ['text' => $prompt],
            [
                'inlineData' => [
                    'mimeType' => $mimeType,
                    'data' => base64_encode($imageData),
                ],
            ],
        ], true);

        $result = json_decode($text, true, 512, JSON_THROW_ON_ERROR);
        if (! is_array($result)) {
            throw new RuntimeException('Gemini returned invalid OCR JSON.');
        }

        return $result;
    }

    private function request(array $parts, bool $jsonResponse = false): string
    {
        $apiKey = config('services.gemini.api_key');
        if (! is_string($apiKey) || trim($apiKey) === '') {
            throw new RuntimeException('Gemini API key is not configured. Set GEMINI_API_KEY.');
        }

        $model = config('services.gemini.model');
        if (! is_string($model) || trim($model) === '') {
            throw new RuntimeException('Gemini model is not configured. Set GEMINI_MODEL.');
        }

        $body = [
            'contents' => [
                ['parts' => $parts],
            ],
        ];

        if ($jsonResponse) {
            $body['generationConfig'] = [
                'responseMimeType' => 'application/json',
            ];
        }

        $response = $this->httpClient->post('models/'.rawurlencode(trim($model)).':generateContent', [
            'headers' => [
                'x-goog-api-key' => trim($apiKey),
            ],
            'json' => $body,
        ]);

        $payload = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        if (! is_array($payload)) {
            throw new RuntimeException('Gemini returned an invalid response.');
        }

        $parts = $payload['candidates'][0]['content']['parts'] ?? null;
        if (! is_array($parts)) {
            $blockReason = $payload['promptFeedback']['blockReason'] ?? null;
            if (is_string($blockReason)) {
                throw new RuntimeException('Gemini blocked the prompt: '.$blockReason.'.');
            }

            throw new RuntimeException('Gemini response did not contain text candidates.');
        }

        $text = '';
        foreach ($parts as $part) {
            if (is_array($part) && isset($part['text']) && is_string($part['text'])) {
                $text .= $part['text'];
            }
        }

        if (trim($text) === '') {
            throw new RuntimeException('Gemini returned an empty text response.');
        }

        return $text;
    }
}
