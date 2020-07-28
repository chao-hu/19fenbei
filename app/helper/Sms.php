<?php
/**
 * Created by PhpStorm.
 * User: huchao
 * Date: 14-4-28
 * Time: 上午11:37
 */

class Sms
{
    const CODE_TPL =  "您好!您的验证码是: %s, 【19分贝】";
    const SEND_URL = "http://dx.10659com.com:83/ApiService.asmx/Send";
    const QUERY_URL = "http://dx.10659com.com:83/ApiService.asmx/GetYuE";
    const ACCOUNT = "1048418244";
    const PWD = "a1048418244";
    const CODE_PRODUCT = "15";

    protected $errorCode = null;
    protected $errorMsg = null;

    public function getErrorCode()
    {
        return $this->errorCode;
    }
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }
    public  function sendCode($mobile, $code)
    {

        $sms = sprintf(self::CODE_TPL, $code);
        $data = array(
            "account" => self::ACCOUNT,
            'pwd' => self::PWD,
            'product' => self::CODE_PRODUCT,
            'message' => iconv("UTF-8", "GBK", $sms),
            'mobile' => $mobile
        );

        $res = HttpClient::quickPost(self::SEND_URL, $data);

        $ret = $this->parserSend($res);

        $this->logCode($mobile, $code);
        $this->logSms($mobile, $sms);

        return ($ret == 200) ? true : false;
    }


    public function logCode($mobile, $code)
    {
        $smsCode= new SmsCode();
        $smsCode->log($mobile, $code);
    }

    public function logSms($mobile, $sms)
    {
        $smsLog= new SmsLog();
        $smsLog->log($mobile, $sms);
    }

    public function queryCode()
    {
        $data = array(
            "account" => self::ACCOUNT,
            'pwd' => self::PWD,
            'product' => self::CODE_PRODUCT,
        );

        $res = HttpClient::quickPost(self::QUERY_URL, $data);

        $ret = $this->parserQuery($res);
        return $ret;
    }

    public  function parserSend($str)
    {
        try {
            $obj = simplexml_load_string($str);

            $arr = (array)$obj;
            $res = $arr[0];
            $ret = explode(",", $res);
            $this->errorCode = $ret[0];
            $this->errorMsg = $this->getErrorMessage($ret[0]);
            return $ret[0];
        } catch (Exception $e) {

            $this->errorCode = 0;
            $this->errorMsg = "解析xml错误";
        }

        return false;
    }
    public  function parserQuery($str)
    {
        try {
            $obj = simplexml_load_string($str);

            $arr = (array)$obj;
            $res = $arr[0];
            $ret = explode(",", $res);
            $this->errorCode = $ret[0];
            $this->errorMsg = $this->getErrorMessage($ret[0]);
            return $ret;
        } catch (Exception $e) {

            $this->errorCode = 0;
            $this->errorMsg = "解析xml错误";
        }

        return false;
    }

    public function getErrorMessage($code) {

        $map = array(
            "200" => '发送成功',
            "-100"=> '账号密码不合法',
            "-101"=> '关键词不合法',
            "-102"=> '产品不存在',
            "-103"=> '数据表过长',
            "-104"=> '无手机号码或手机号码不足最少个数',
            "-105"=> '内容超长',
            "-106"=> '无发送额度',
            "-107"=> '系统维护中',
            "-108"=> '未知错误',
            "0"=> '未知错误',
        );

        return isset($map[$code]) ? $map[$code] : "未知错误";
    }

}