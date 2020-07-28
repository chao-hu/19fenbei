<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 15-2-2
 * Time: 下午10:42
 */

class LoginMiddleware extends \Slim\Middleware
{
    public function call()
    {

        $isUserLoginedMaps = array(

            '/member',
            '/order',
            '/check',
            '/buy-confirm',
            '/alipay_return',
        );

        $isAdminLoginedMaps = array(

        );

        //The Slim application
        $app = $this->app;

        //The Environment object
        $env = $app->environment;

        //The Request object
        $req = $app->request;

        //The Response object
        $res = $app->response;

        if (!empty($_SESSION['userinfo'])) {
            $app->view()->appendData(array('userinfo' => $_SESSION['userinfo']));
        }

        // static 资源 域名
        $staticDomains  = $app->config('static.domains');
        if (empty($staticDomains)) {
            $app->view()->appendData(array('static_domain' =>$req->getUrl()));
        } else {
            $staticDomain = $staticDomains[array_rand($staticDomains)];
            $app->view()->appendData(array('static_domain' => $staticDomain));
        }

        // 网站配置
        $app->view()->appendData(array('site' => $app->config('site')));

        $pathinfo = $env['PATH_INFO'];
        // user is logined
        foreach ($isUserLoginedMaps as $find) {

            if (stripos ($pathinfo, $find) !== false) {

                if (!$this->checkUserIsLogined()) {
                    $_SESSION['after_login_url'] = $pathinfo;
                    $app->redirect('/login');
                    return;
                }
            }
        }

        // admin is need logined
        foreach ($isAdminLoginedMaps as $find) {

            if (stripos ($pathinfo, $find) !== false) {
                if (!$this->checkAdminIsLogined()) {
                    $_SESSION['after_login_url'] = $pathinfo;
                    $app->redirect('/admin/login');
                    return;
                }
            }
        }

        $this->next->call();
    }

    public function checkUserIsLogined()
    {
        return isset($_SESSION['uid']) ? true : false;
    }

    public function checkAdminIsLogined()
    {
        return isset($_SESSION['admin_uid']) ? true : false;
    }
}