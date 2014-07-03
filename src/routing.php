<?php
use Symfony\Component\HttpFoundation;

$app->get('/browse',
    function (HttpFoundation\Request $request) use ($app) {
        return $app['browse.controller']->indexAction(
            $request->get('folder', null)
        );
    })
    ->bind('browse')
;

$app->get('/show',
    function (HttpFoundation\Request $request) use ($app) {
        return $app->json(
            $app['browse.controller']->showAction(
                $request->get('file', null)
            )
        );
    })
    ->bind('show')
;

$app->get('/delete',
    function (HttpFoundation\Request $request) use ($app) {
        $file = $request->get('file');
        if (null !== $file) {
            $url = $app['action.controller']->deleteAction($file);
            return $app->redirect($url);
        }

        $folder = $request->get('folder');
        if (null !== $folder) {
            $url = $app['action.controller']->deleteAction($folder, true);
            return $app->redirect($url);
        }

        throw new \RuntimeException("Missing file or folder parameter.");
    })
    ->bind('delete')
;
