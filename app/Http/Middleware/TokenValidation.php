<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // hard coded token here just for the purpose of this test.
        // token should save in database
        if ($request->input('api_token') !== 'test-token') {
            return response()->json([
                'success' => false,
                'message' =>  'Invalid token'
            ], Response::HTTP_NETWORK_AUTHENTICATION_REQUIRED);
        }

        return $next($request);
    }
}
