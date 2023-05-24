<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Filament\Support\Exceptions\Halt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class MessageAnalyticAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Check if API key and API secret are present in request headers
        if (!$request->hasHeader('X-Api-Key') || !$request->hasHeader('X-Api-Secret')) {
            return response()->json(['error' => 'API key and Secret are required'], 400);
        }
        $apiKey = $request->header('X-Api-Key');
        $apiSecret =  $request->header('X-Api-Secret');
        $user = User::where('api_key', $apiKey)->first();

        // If user found and API secret matches, proceed with the request
        if ($user &&  $apiSecret == $user->api_secret) {
            $request->merge(['user_id' => $user->id]); // Add user_id to the request
            return $next($request);
        }

        // If API key and/or secret is invalid, return unauthorized response
        return response()->json(['error' => 'Unauthorized', 'code' => Response::HTTP_UNAUTHORIZED], Response::HTTP_UNAUTHORIZED);
    }
}
