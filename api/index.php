<?php

use FrameworkX\App;
use FrameworkX\FilesystemHandler;
use OpenApi\Generator;
use React\Http\Message\Response;

define('ROOT_DIR', dirname(__DIR__));

require ROOT_DIR.'/vendor/autoload.php';

$app = new App();

$app->get('/', new FilesystemHandler(ROOT_DIR.'/public/index.html'));
$app->get('/openapi', fn() => Response::plaintext(Generator::scan([ROOT_DIR.'/src'])->toYaml()));

// Hentaivn
$app->get('/proxy-hentaivn', FilterMiddleware::class, ProxyHentaiVNController::class);

// Nettruyen
$app->get('/detail', FilterMiddleware::class, DetailController::class);
$app->get('/read', FilterMiddleware::class, ReadController::class);
$app->get('/proxy-nettruyen', FilterMiddleware::class, ProxyNettruyenController::class);


$app->run();
