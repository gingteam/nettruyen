<?php

use FrameworkX\ErrorHandler;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Response as OAResponse;
use OpenApi\Attributes\Schema;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Browser;
use React\Http\Message\Response;
use React\Promise\PromiseInterface;
use Symfony\Component\DomCrawler\Crawler;

#[Get(
    path: '/read',
    description: 'Returns the list of paths of the images',
    tags: ['Read'],
    parameters: [
        new Parameter(
            name: 'url',
            description: 'URL',
            in: 'query',
            required: true,
            schema: new Schema(type: 'string'),
            example: 'https://www.nettruyenin.com/truyen-tranh/vo-luyen-dinh-phong/chap-2793/929207'
        ),
    ],
    responses: [
        new OAResponse(
            response: 200,
            description: 'Return if successful',
            content: new JsonContent(
                type: 'array',
                items: new Items(
                    description: 'Link of image',
                    type: 'string'
                ),
            )
        ),
        new OAResponse(response: 404, description: 'Return if failed'),
    ]
)]
class ReadController
{
    /**
     * @return PromiseInterface<Response>
     */
    public function __invoke(ServerRequestInterface $request)
    {
        /** @var string */
        $url = $request->getAttribute('url');
        $browser = new Browser();

        return $browser->request('GET', $url)->then(function (ResponseInterface $response) {
            $crawler = new Crawler((string) $response->getBody());

            $images = [];
            $crawler->filterXPath('//div[contains(@id, "page_")]/img')
                ->each(function (Crawler $node, int $i) use (&$images) {
                    $images[] = $node->attr('data-original');
                });

            array_shift($images);

            return Response::json($images);
        }, function () {
            return (new ErrorHandler())->requestNotFound();
        });
    }
}
