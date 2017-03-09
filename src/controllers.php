<?php

use Symfony\Component\HttpFoundation\Request;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

$app->get('/', function (Request $request) use ($app) {

    return $app->json('Hello Hub!', 200);
});

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }
});
