<?php

use FrameworkX\ErrorHandler;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response as OAResponse;
use OpenApi\Attributes\Schema;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Browser;
use React\Http\Message\Response;
use React\Promise\PromiseInterface;
use Symfony\Component\DomCrawler\Crawler;

#[Get(
    path: '/detail',
    description: 'Return the list of episodes, detail of the story',
    tags: ['Detail'],
    parameters: [
        new Parameter(
            name: 'url',
            description: 'URL',
            example: 'https://www.nettruyenin.com/truyen-tranh/vo-luyen-dinh-phong-176960',
            required: true,
            in: 'query',
            schema: new Schema(type: 'string')
        ),
    ],
    responses: [
        new OAResponse(
            response: 200,
            description: 'Return if successful',
            content: new JsonContent(
                type: 'array',
                items: new Items(
                    type: 'object',
                    properties: [
                        new Property(
                            property: 'title',
                            type: 'string'
                        ),
                        new Property(
                            property: 'description',
                            type: 'string'
                        ),
                        new Property(
                            property: 'chapters',
                            type: 'array',
                            items: new Items(
                                type: 'object',
                                properties: [
                                    new Property(
                                        type: 'string',
                                        property: 'name'
                                    ),
                                    new Property(
                                        type: 'string',
                                        property: 'url'
                                    ),
                                ]
                            )
                        ),
                    ]
                )
            )
        ),
        new OAResponse(
            response: 404,
            description: 'Return if failed'
        ),
    ]
)]
class DetailController
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

            $title = $crawler->filter('.title-detail')->first()->text();
            $description = $crawler->filter('.detail-content')->first()->text();
            $chapters = [];

            $crawler
                ->filterXPath('//div[contains(@class, "col-xs-5 chapter")]/a')
                ->each(function (Crawler $anchor, int $i) use (&$chapters) {
                    $chapters[] = [
                        'url' => $anchor->attr('href'),
                        'name' => $anchor->text(),
                    ];
                });

            return Response::json(compact(['title', 'description', 'chapters']));
        }, function () {
            return (new ErrorHandler())->requestNotFound();
        });
    }
}
