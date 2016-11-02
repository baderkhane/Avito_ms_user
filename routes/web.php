<?php


$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('/loginFb', 'FbController@loginFb');

