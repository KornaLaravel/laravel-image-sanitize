<?php

namespace LaravelAt\ImageSanitize;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ImageSanitizeMiddleware
{
    public function __construct(
        protected RequestHandler $requestHandler,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $this->requestHandler->handle($request);

        return $next($request);
    }
}
