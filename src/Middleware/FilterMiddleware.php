<?php

use FrameworkX\ErrorHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Promise\PromiseInterface;

class FilterMiddleware
{
    /**
     * @param ServerRequestInterface $request
     * @param callable(ServerRequestInterface): PromiseInterface<ServerRequestInterface> $next
     * @return ResponseInterface|PromiseInterface<ServerRequestInterface>
     */
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $url = $request->getQueryParams()['url'] ?? '';

        if (false === filter_var($url, FILTER_VALIDATE_URL)) {
            return (new ErrorHandler())->requestNotFound();
        }

        $request = $request->withAttribute('url', $url);

        return $next($request);
    }
}
