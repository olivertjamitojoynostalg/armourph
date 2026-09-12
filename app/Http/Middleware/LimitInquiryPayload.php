<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LimitInquiryPayload
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->server('CONTENT_LENGTH', 0) > 16_384) {
            abort(Response::HTTP_REQUEST_ENTITY_TOO_LARGE, 'The inquiry submission is too large.');
        }

        return $next($request);
    }
}
