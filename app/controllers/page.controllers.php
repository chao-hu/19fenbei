<?php


/**
 * froentend  buy help
 */
$app->get('/buy-help', function () use ($app) {

    $app->view()->display('buy-help.html');
    return;
})->name('buy-help');

/**
 * froentend  about
 */
$app->get('/about', function () use ($app) {

    $app->view()->display('about.html');
    return;
})->name('about');

/**
 * froentend  contact us
 */
$app->get('/contactus', function () use ($app) {

    $app->view()->display('contactus.html');
    return;
})->name('contactus');

/**
 * froentend  join us
 */
$app->get('/joinus', function () use ($app) {

    $app->view()->display('joinus.html');
 return;
})->name('joinus');
/**
 * froentend  advantage
 */
$app->get('/price', function () use ($app) {

    $app->view()->display('price.html');
    return;
})->name('price');

/**
 * froentend  advantage
 */
$app->get('/price-windows', function () use ($app) {

    $app->view()->display('price-windows.html');
    return;
})->name('price-windows');

/**
 * froentend  advantage
 */
$app->get('/price-sunroom', function () use ($app) {

    $app->view()->display('price-sunroom.html');
    return;
})->name('price-sunroom');
/**
 * froentend  after service
 */
$app->get('/after-service', function () use ($app) {

    $app->view()->display('after-service.html');
    return;
})->name('after-service');

/**
 * froentend  payment
 */
$app->get('/payment', function () use ($app) {

    $app->view()->display('payment.html');
    return;
})->name('payment');

/**
 * froentend  payment
 */
$app->get('/privacy', function () use ($app) {

    $app->view()->display('privacy.html');
    return;
})->name('privacy');

