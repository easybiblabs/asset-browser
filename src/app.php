<?php

use Aws\S3\S3Client;
use Igorw\Stack;
use ImagineEasy\Service;
use Silex\Application;
use Silex\Provider;
use Symfony\Component\HttpFoundation;

$app = new Application();
$app->register(new Provider\UrlGeneratorServiceProvider());
$app->register(new Provider\ValidatorServiceProvider());
$app->register(new Provider\ServiceControllerServiceProvider());

$app->register(new Provider\TwigServiceProvider());
$app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
}));

require dirname(__DIR__) . '/config/local.php';

$app['aws.s3'] = $app->share(
    function () use ($app) {
        return S3Client::factory();
    }
);

$app['service.navigation'] = $app->share(
    function() {
        return new Service\NavigationService;
    }
);

$app['service.s3'] = $app->share(
    function() use ($app) {
        return new Service\S3Service(
            $app['aws.s3'],
            $app['aws.s3.bucket']
        );
    }
);

return $app;
