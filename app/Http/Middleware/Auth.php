<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Response;

class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userKey = $request->header('x-localhost');
        $authKey = getGlobal('AUTH_KEY');

        if($userKey !== $authKey)
            return response('', HttpResponse::HTTP_FORBIDDEN);

        return $next($request);
    }
}
