<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectToInformSubdomain
{
    /**
     * Apex and www serve the same app; send browsers to the inform subdomain.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = strtolower($request->getHost());

        if (! in_array($host, ['menpowerr-eg.co', 'www.menpowerr-eg.co'], true)) {
            return $next($request);
        }

        $target = 'https://inform.menpowerr-eg.co'.$request->getRequestUri();

        return redirect()->away($target, 301);
    }
}
