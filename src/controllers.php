<?php

use ImagineEasy\Controller;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html', []);
})
->bind('homepage')
;

/**
 * Controllers
 */
$app['browse.controller'] = $app->share(
    function () use ($app) {
        return new Controller\BrowseController(
            $app['service.s3'],
            $app['service.navigation'],
            $app['twig']
        );
    }
);

$app['action.controller'] = $app->share(
    function () use ($app) {
        return new Controller\ActionController(
            $app['service.s3'],
            $app['url_generator']->generate('browse')
        );
    }
);

require __DIR__ . '/routing.php';

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = [
        'errors/'.$code.'.html',
        'errors/'.substr($code, 0, 2).'x.html',
        'errors/'.substr($code, 0, 1).'xx.html',
        'errors/default.html',
    ];

    return new HttpFoundation\Response($app['twig']->resolveTemplate($templates)->render(['code' => $code]), $code);
})
;
