<?php

namespace App\Http\Middleware;

use App\Services\QuotaService;
use Closure;
use Illuminate\Http\Request;

class CheckApiQuota
{
    public function handle(Request $request, Closure $next, string $endpointSlug)
    {
        app(QuotaService::class)->checkQuota($endpointSlug);

        return $next($request);
    }
}