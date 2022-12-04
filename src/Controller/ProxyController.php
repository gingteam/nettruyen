<?php

use FrameworkX\ErrorHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Browser;
use React\Http\Message\Response;
use React\Promise\PromiseInterface;

class ProxyController
{
    public function __invoke(ServerRequestInterface $request): PromiseInterface|ResponseInterface
    {
        $url = $request->getAttribute('url');
        $referer = $request->getAttribute('referer');

        $browser = new Browser();

        return $browser->requestStreaming('GET', $url, [
            'referer' => $referer,
        ])->then(function (ResponseInterface $response) {
            return new Response(body: $response->getBody());
        })->catch(function () {
            return (new ErrorHandler())->requestNotFound();
        });
    }
}
