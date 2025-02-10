<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->isValidApiKey($request)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }

    private function isValidApiKey(Request $request): bool
    {
        $apiKey = $request->header('X-API-KEY');
        return $apiKey && $apiKey === config('services.api_key');
    }

}
