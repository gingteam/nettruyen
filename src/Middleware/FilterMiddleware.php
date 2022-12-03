<?php

use FrameworkX\ErrorHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Promise\PromiseInterface;

class FilterMiddleware
{
    public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface|PromiseInterface
    {
        $url = $request->getQueryParams()['url'] ?? '';

        if (false === filter_var($url, FILTER_VALIDATE_URL)) {
            return (new ErrorHandler())->requestNotFound();
        }

        $request = $request->withAttribute('url', $url);

        return $next($request);
    }
}
