<?php


/**
 * froentend
 */
$app->get('/404', function () use ($app) {

    $app->view()->display('404.html');
    return;
});

/**
 * froentend
 */
$app->get('/error', function () use ($app) {

    $data = session('error');
    $app->view()->display('success.html', $data);
    return;
});

/**
 * froentend
 */
$app->get('/success', function () use ($app) {

    $data = session('success');
    $app->view()->display('success.html', $data);
    return;
});


/**
 * froentend index
 */
$app->get('/', function () use ($app) {


    $app->view()->display('index.html');
})->name('');

/**
 * froentend  windows index
 */
$app->get('/windows', function () use ($app) {

    $app->view()->display('windows/overview.html');
})->name('windows');
$app->get('/windows/overview', function () use ($app) {

    $app->view()->display('windows/overview.html');
})->name('windows-overview');
$app->get('/windows/design', function () use ($app) {

    $app->view()->display('windows/design.html');
})->name('windows-design');

$app->get('/windows/environmental', function () use ($app) {

    $app->view()->display('windows/environmental.html');
})->name('windows-environmental');

$app->get('/windows/keepwarm', function () use ($app) {

    $app->view()->display('windows/keepwarm.html');
})->name('windows-keepwarm');

$app->get('/windows/parameter', function () use ($app) {

    $app->view()->display('windows/parameter.html');
})->name('windows-parameter');

$app->get('/windows/soundproof', function () use ($app) {

    $app->view()->display('windows/soundproof.html');
})->name('windows-soundproof');


/**
 * froentend  sun-room index
 */
$app->get('/sunroom', function () use ($app) {

    $app->view()->display('sunroom/overview.html');
})->name('sunroom');
$app->get('/sunroom/overview', function () use ($app) {

    $app->view()->display('sunroom/overview.html');
})->name('sunroom-overview');


$app->get('/sunroom/design', function () use ($app) {

    $app->view()->display('sunroom/design.html');
})->name('sunroom-design');

$app->get('/sunroom/material', function () use ($app) {

    $app->view()->display('sunroom/material.html');
})->name('sunroom-material');

$app->get('/sunroom/parameter', function () use ($app) {

    $app->view()->display('sunroom/parameter.html');
})->name('sunroom-parameter');

$app->get('/sunroom/performance', function () use ($app) {

    $app->view()->display('sunroom/performance.html');
})->name('sunroom-performance');

$app->get('/sunroom/vision', function () use ($app) {

    $app->view()->display('sunroom/vision.html');
})->name('sunroom-vision');


/**
 * froentend  check
 */
$app->post('/check', function () use ($app) {


    return;
})->name('check');


/**
 * froentend  captcha show
 */
$app->get('/captcha', function () use ($app) {

    echo Image::buildImageVerify();
    die;
})->name('captcha');

/**
 * froentend  sms send
 */
$app->post('/smssend', function () use ($app) {

    $mobile = $app->request->params('mobile');
    $smshash = $app->request->params('sms_hash');

    $ret = array("error_code" => 1, "error_msg" => null);

    if ($smshash != session('sms_hash')) {
        $ret['error_code'] = "-1";
        $ret['error_msg'] = "非法请求";
    } elseif (!Util::isMobile($mobile)) {

        $ret['error_code'] = "-2";
        $ret['error_msg'] = "手机号码格式不对";
    } else {

        $smsCode = new SmsCode();

        if (!$smsCode->isCanSend($mobile)) {
            $ret['error_code'] = "-3";
            $ret['error_msg'] = "请稍后再发送!间隔未到!";
        } else {

            $sms = new Sms();
            $code = String::randString(4, 1);
            $r = $sms->sendCode($mobile, $code);
            if ($r) {
                $ret['error_msg'] = "发送成功!";
            } else {
                $ret['error_code'] = $sms->getErrorCode();
                $ret['error_msg'] = "发送失败!" . $sms->getErrorMsg();
            }
        }
    }

    echo Util::toJson($ret);
    return;
})->name('smssend');


$app->get('/t', function () use ($app) {

    $mobile = $app->request->params('mobile', '13641028825');

    $sms = new Sms();
    $code = String::randString(4, 1);

    //$r = $sms->sendCode($mobile, $code);

    //var_dump($r, $sms);

})->name('test');