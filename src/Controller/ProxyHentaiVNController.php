<?php

use FrameworkX\ErrorHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Browser;
use React\Http\Message\Response;
use React\Http\Message\ResponseException;
use React\Promise\PromiseInterface;

class ProxyHentaiVNController
{
    public function __invoke(ServerRequestInterface $request): PromiseInterface|ResponseInterface
    {
        $url = $request->getAttribute('url');
        $browser = new Browser();

        return $browser->requestStreaming('GET', $url, [
            'referer' => 'https://hentaivn.in/',
        ])->then(function (ResponseInterface $response) {
            return new Response(body: $response->getBody());
        })->catch(function (ResponseException $e) {
            return (new ErrorHandler())->requestNotFound();
        });
    }
}
