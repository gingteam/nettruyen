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
        /** @var string */
        $url = $request->getAttribute('url');

        /** @var array<string, string> */
        $headers = $request->getAttribute('headers');

        $browser = new Browser();

        return $browser->requestStreaming(
            method: 'GET',
            url: $url,
            headers: $headers,
        )->then(function (ResponseInterface $response) {
            return new Response(
                headers: [
                    'Content-Type' => 'image/jpg',
                ],
                body: $response->getBody()
            );
        })->catch(function () {
            return (new ErrorHandler())->requestNotFound();
        });
    }
}
