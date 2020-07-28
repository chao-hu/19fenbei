<?php
/**
 * Created by PhpStorm.
 * User: huchao
 * Date: 14-4-27
 * Time: 下午6:15
 */

class SmsCode extends Model
{

    public static $_table = 'sms_code';

    const INTERVAL = 120; // 2分钟 间隔

    public function log($mobile, $code)
    {

        $smscode = Model::factory('SmsCode')->create();
        $smscode->mobile = $mobile;
        $smscode->code = $code;
        $smscode->status = 0;
        $smscode->createdtime = time();
        $smscode->save();

        return;
    }

    public function isCanSend($mobile)
    {

        $smscode = Model::factory('SmsCode')
            ->where_equal("mobile", $mobile)
            ->where_equal("status", 0)
            ->order_by_desc("id")
            ->find_one();

        if (!$smscode || $smscode->createdtime + self::INTERVAL < time()) {

            return true;
        }

        return;
    }

    public function verify($mobile, $code)
    {

        $smscode = Model::factory('SmsCode')
            ->where_equal('mobile', $mobile)
            ->where_equal('status', 0)
            ->order_by_desc("id")
            ->find_one();

        if ($smscode && $smscode->code == $code) {
            $smscode->status = 1;
            $smscode->save();
            return true;
        }
        return false;
    }
}