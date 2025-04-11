<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validApiKey = config('app.api_key', 'X7K9P2M4Q8R3T6W5');
        $apiKey = $request->query('apikey') ?? $request->input('apikey');

        if (empty($apiKey) || $apiKey !== $validApiKey) {
            return response('invalid_apikey', 401)->header('Content-Type', 'text/plain');
        }

        return $next($request);
    }
}
