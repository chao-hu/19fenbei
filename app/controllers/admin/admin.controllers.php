<?php


/**
 * froentend  buy help
 */
$app->get('/admin/', function () use ($app) {

    $app->view()->display('admin/index.html');
    return;
})->name('amdin');
$app->get('/admin/index', function () use ($app) {

    $app->view()->display('admin/index.html');
})->name('amdin-index');

$app->get('/admin/login', function () use ($app) {

    $app->view()->display('admin/login.html');
    return;
})->name('amdin-login');


