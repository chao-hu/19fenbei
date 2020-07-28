<?php


/**
 * froentend  windows buy
 */
$app->post('/alipay_notify', function () use ($app) {

//计算得出通知验证结果
    $aliPayConfig = $app->config('alipay');
    $notify = new AliNotify($aliPayConfig);
    $verify_result = $notify->verifyNotify();

    if ($verify_result) { //验证成功
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //请在这里加上商户的业务逻辑程序代


        //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

        //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

        //商户订单号

        $out_trade_no = $_POST['out_trade_no'];

        //支付宝交易号

        $trade_no = $_POST['trade_no'];

        //交易状态
        $trade_status = $_POST['trade_status'];


        if ($_POST['trade_status'] == 'WAIT_BUYER_PAY') {
            //该判断表示买家已在支付宝交易管理中产生了交易记录，但没有付款

            //判断该笔订单是否在商户网站中已经做过处理
            //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
            //如果有做过处理，不执行商户的业务程序

            echo "success"; //请不要修改或删除

            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        } else if ($_POST['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
            //该判断表示买家已在支付宝交易管理中产生了交易记录且付款成功，但卖家没有发货

            //判断该笔订单是否在商户网站中已经做过处理
            //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
            //如果有做过处理，不执行商户的业务程序

            echo "success"; //请不要修改或删除

            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        } else if ($_POST['trade_status'] == 'WAIT_BUYER_CONFIRM_GOODS') {
            //该判断表示卖家已经发了货，但买家还没有做确认收货的操作

            //判断该笔订单是否在商户网站中已经做过处理
            //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
            //如果有做过处理，不执行商户的业务程序

            echo "success"; //请不要修改或删除

            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        } else if ($_POST['trade_status'] == 'TRADE_FINISHED') {
            //该判断表示买家已经确认收货，这笔交易完成

            //判断该笔订单是否在商户网站中已经做过处理
            //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
            //如果有做过处理，不执行商户的业务程序

            echo "success"; //请不要修改或删除

            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        } else {
            //其他状态判断
            echo "success";

            //调试用，写文本函数记录程序运行情况是否正常
            //logResult ("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }

        //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    } else {
        //验证失败
        echo "fail";

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
    return;

})->name('alipay_notify');

/**
 * froentend  windows buy
 */
$app->get('/alipay_return', function () use ($app) {

    $aliPayConfig = $app->config('alipay');
    $notify = new AliNotify($aliPayConfig);
    $verify_result = $notify->verifyNotify();
    if ($verify_result) { //验证成功
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //请在这里加上商户的业务逻辑程序代码

        //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
        //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

        //商户订单号

        $out_trade_no = $_GET['out_trade_no'];

        //支付宝交易号

        $trade_no = $_GET['trade_no'];

        //交易状态
        $trade_status = $_GET['trade_status'];


        if ($_GET['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
            //判断该笔订单是否在商户网站中已经做过处理
            //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
            //如果有做过处理，不执行商户的业务程序
        } else {
            echo "trade_status=" . $_GET['trade_status'];
        }

        echo "验证成功<br />";
        echo "trade_no=" . $trade_no;

        //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    } else {
        //验证失败
        //如要调试，请看alipay_notify.php页面的verifyReturn函数
        echo "验证失败";
    }

    return;
})->name('alipay_return');

