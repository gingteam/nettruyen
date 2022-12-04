<?php

use Psr\Http\Message\ServerRequestInterface;
use React\Promise\PromiseInterface;

class RefererMiddleware
{
    public function __invoke(ServerRequestInterface $request, callable $next): PromiseInterface
    {
        $site = $request->getAttribute('site');
        $referer = match ($site) {
            'hentaivn' => 'https://hentaivn.in/',
            default => 'https://www.nettruyentv.com/'
        };

        $request = $request->withAttribute('referer', $referer);

        return $next($request);
    }
}
