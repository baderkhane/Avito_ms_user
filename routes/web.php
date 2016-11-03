<?php


$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('/loginFb', 'loginController@loginFb');

$app->post('/login', 'loginController@loginSimple');

$app->post('/createClient', 'loginController@createClient');
