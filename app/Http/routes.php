<?php

$app->get('/', function () use ($app) {
    return view('index');
});

$app->post('/invite', function() use ($app) {
    // TODO: implement logic
});
