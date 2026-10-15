<?php

namespace Tests\Unit;

use App\Services\GeminiService;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use RuntimeException;
use Tests\TestCase;

class GeminiServiceTest extends TestCase
{
    public function test_it_rejects_an_empty_prompt(): void
    {
        $this->expectException(InvalidArgumentException::class);

        app(GeminiService::class)->generateText('  ');
    }

    public function test_it_requires_an_api_key(): void
    {
        config(['services.gemini.api_key' => null]);

        $this->expectException(RuntimeException::class);

        app(GeminiService::class)->generateText('Hello');
    }

    public function test_it_sends_the_prompt_to_gemini_and_returns_text(): void
    {
        config([
            'services.gemini.api_key' => 'test-api-key',
            'services.gemini.model' => 'gemini-test-model',
        ]);

        $history = [];
        $handler = HandlerStack::create(new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Hello from Gemini.'],
                            ],
                        ],
                    ],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]));
        $handler->push(Middleware::history($history));

        $service = new GeminiService(['handler' => $handler]);

        $this->assertSame('Hello from Gemini.', $service->generateText('Hello'));
        $request = $history[0]['request'];
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('generativelanguage.googleapis.com', $request->getUri()->getHost());
        $this->assertSame('/v1beta/models/gemini-test-model:generateContent', $request->getUri()->getPath());
        $this->assertSame('test-api-key', $request->getHeaderLine('x-goog-api-key'));
        $this->assertSame(
            ['contents' => [['parts' => [['text' => 'Hello']]]]],
            json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR)
        );
    }

    public function test_it_sends_an_image_and_returns_json_ocr_data(): void
    {
        config([
            'services.gemini.api_key' => 'test-api-key',
            'services.gemini.model' => 'gemini-test-model',
        ]);

        $history = [];
        $handler = HandlerStack::create(new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => '{"transactions":[]}'],
                            ],
                        ],
                    ],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]));
        $handler->push(Middleware::history($history));

        $service = new GeminiService(['handler' => $handler]);

        $this->assertSame(
            ['transactions' => []],
            $service->generateJsonFromImage('Read receipt', 'image/jpeg', 'image-bytes')
        );

        $request = $history[0]['request'];
        $payload = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame('image/jpeg', $payload['contents'][0]['parts'][1]['inlineData']['mimeType']);
        $this->assertSame(base64_encode('image-bytes'), $payload['contents'][0]['parts'][1]['inlineData']['data']);
        $this->assertSame('application/json', $payload['generationConfig']['responseMimeType']);
    }
}
