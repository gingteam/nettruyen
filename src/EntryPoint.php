<?php

use OpenApi\Attributes\Contact;
use OpenApi\Attributes\Info;
use OpenApi\Attributes\Tag;

#[Info(
    title: 'Nettruyen',
    version: '1.0',
    description: 'Crawl from Nettruyen',
    contact: new Contact('GingTeam', 'https://github.com/gingteam/nettruyen')
)]
#[Tag('Read')]
#[Tag('Detail')]
class EntryPoint
{
}