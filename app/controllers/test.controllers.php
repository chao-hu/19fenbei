<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 15-2-9
 * Time: 下午11:05
 */


// 缩略图功能
$app->get('/metacrawl', function () use ($app) {

    $keyword = $app->request->params('keyword');
    $sid = $app->request->params('sid');
    $page = $app->request->params('page');


    $data = Util::metacrawl($keyword,$sid, $page);

    echo Util::toJson($data);
    return;
})->name('metacrawl');


$app->get('/getcontent', function() use ($app) {

        $k = $app->request->params('k');
        $p = $app->request->params('p');
        $t = $app->request->params('t');

        echo "$k $p $t";
        return;
    }
)->name("getconetnt");
