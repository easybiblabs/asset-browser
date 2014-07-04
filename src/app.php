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
    $twig->addFunction(
        new Twig_SimpleFunction(
            'dev_shortcuts',
            function ($path) {

                $github = 'https://github.com/%s/%s/pull/%d';
                $travis = 'https://magnum.travis-ci.com/%s/%s';
                $link = '<a class="btn-xs btn %s" href="%s">%s</a>';

                if (false === strpos($path, 'travis-ci')) {
                    return '';
                }

                if (substr($path, -1) == '/') {
                    $path = substr($path, 0, -1);
                }
                $matches = explode('/', $path);

                if (count($matches) < 4) {
                    return '';
                }

                $response = sprintf(
                    $link,
                    'btn-info',
                    sprintf($github, $matches[0], $matches[1], $matches[3]),
                    'GitHub PR'
                );

                if (count($matches) == 5) {
                    $response .= '&nbsp;';
                    $response .= sprintf(
                        $link,
                        'btn-warning',
                        sprintf($travis, $matches[0], $matches[1]),
                        'Travis-CI'
                    );
                }

                return $response;
            }
        )
    );
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
