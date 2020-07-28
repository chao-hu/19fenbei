<?php
/*
 * (Slim) Application Config
 */

define('SLIM_MODE_DEV', 'development');
define('SLIM_MODE_PRO', 'production');
define('SLIM_MODE', SLIM_MODE_DEV);

return array(
    'mode' => SLIM_MODE,
    'view' => new \Slim\Views\Twig(),
    'templates.path' => ROOT . '/app/views/',

    'debug' => SLIM_MODE === SLIM_MODE_DEV,
    'log.enabled' => SLIM_MODE === SLIM_MODE_DEV,
    'log.writer' => new \Slim\Extras\Log\DateTimeFileWriter(array(
            'path' => ROOT . '/app/storage/logs',
            'name_format' => 'Y-m-d',
            'message_format' => '%label% - %date% - %message%'
        )),
    //'static.domains' => array('http://19fb.cn', 'http://19fb.cn'),
    'site'=> array(
        'online_service' => 'http://wpa.qq.com/msgrd?v=3&uin=997528792&site=qq&menu=yes',
        'tel400' => '4006-1949-19',
        'tel'=>'010-50976419',
        'address'=>'北京市昌平区回龙观镇科星西路106号院国风美唐6号楼1406室',
        'post'=>'102209',
        'email'=>'service@19fenbei.com',
        'weibo' => '',
        'weixin' => '',
    ),
    // alipay relation info
    'alipay'=>array(
        'partner' => '',
        'key' => '',
        'sign_type' => 'MD5',
        'input_charset' => 'utf-8',
        'cacert' => ROOT . '/app/lib/Alipay/cacert.pem',
        'transport' => 'http',
    ),
);



