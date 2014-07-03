<?php
use Symfony\Component\HttpFoundation;

$app->get('/login',
    function (HttpFoundation\Request $request) use ($app) {
        return $app->redirect($app['url_generator']->generate('home'));
    }
)->bind('login');

$stack = (new Stack\Builder())
    ->push('Stack\Session')
    ->push(
        'Igorw\Stack\OAuth',
        [
            'key' => $app['github.key'],
            'secret' => $app['github.secret'],
            'callback_url' => $app['github.callback'],
            'success_url' => $app['url_generator']->generate('homepage'),
            'failure_url'  => '/auth',
            'oauth_service.class' => 'OAuth\OAuth2\Service\GitHub',
        ]
    )
;

$app = $stack->resolve($app);

$request = HttpFoundation\Request::createFromGlobals();
$response = $app->handle($request)->send();
$app->terminate($request, $response);
