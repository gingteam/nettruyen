<?php

use Psr\Http\Message\ServerRequestInterface;
use React\Promise\PromiseInterface;

class RequestHeaderMiddleware
{
    public function __invoke(ServerRequestInterface $request, callable $next): PromiseInterface
    {
        $headers = [];
        $site = $request->getAttribute('site');

        $referer = match ($site) {
            'hentaivn' => 'https://hentaivn.life/',
            'nettruyen' => 'https://www.nettruyentv.com/',
            default => false
        };

        if ($referer) {
            $headers['referer'] = $referer;
        }

        $request = $request->withAttribute('headers', $headers);

        return $next($request);
    }
}
