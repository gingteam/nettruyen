<?php

use Psr\Http\Message\ServerRequestInterface;
use React\Promise\PromiseInterface;

class RequestHeaderMiddleware
{
    /**
     * @param ServerRequestInterface $request
     * @param callable(ServerRequestInterface): PromiseInterface<ServerRequestInterface> $next
     * @return PromiseInterface<ServerRequestInterface>
     */
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $headers = [];
        $site = $request->getAttribute('site');

        $referer = match ($site) {
            'hentaivn' => 'https://hentaivn.de/',
            'nettruyen' => 'https://www.nettruyenup.com/',
            default => false
        };

        if ($referer) {
            $headers['referer'] = $referer;
        }

        $request = $request->withAttribute('headers', $headers);

        return $next($request);
    }
}
