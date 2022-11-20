<?php

use FrameworkX\App;
use FrameworkX\FilesystemHandler;

require __DIR__.'/vendor/autoload.php';

$app = new App();

$app->get('/proxy', FilterMiddleware::class, ProxyController::class);
$app->get('/detail', FilterMiddleware::class, DetailController::class);
$app->get('/read', FilterMiddleware::class, ReadController::class);
$app->get('/resources/{path:.*}', new FilesystemHandler(__DIR__.'/public'));
$app->get('/', new FilesystemHandler(__DIR__.'/public/index.html'));

$app->run();
