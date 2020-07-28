<?php


/**
 * froentend  windows buy
 */
$app->get('/choose', function () use ($app) {


    $app->view()->display('choose.html');

})->name('buy');


/**
 * froentend  windows buy
 */
$app->get('/windows-buy', function () use ($app) {


    $app->view()->display('windows-buy.html');

})->name('windows-buy');

/**
 * froentend  sun-room buy
 */
$app->get('/sunroom-buy', function () use ($app) {


    $app->view()->display('sunroom-buy.html');
})->name('sunroom-buy');


/**
 * froentend  order post show
 */
$app->post('/order', function () use ($app) {

    // 生成订单, 并跳转至支付网关
    var_dump($_POST);
    return;
})->name('order-post');

/**
 * froentend  buy post show
 */
$app->post('/buy-confirm', function () use ($app) {

    $params = $app->request->post();

    $app->view()->appendData($params);

    $app->view()->display('buy.html');
    return;
})->name('buy-confirm');
