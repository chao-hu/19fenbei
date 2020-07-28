<?php
/**
 * Created by PhpStorm.
 * User: huchao
 * Date: 14-4-27
 * Time: 下午6:15
 */

class SmsLog extends Model
{

    public static $_table = 'sms_log';


    public function log($mobile, $sms)
    {

        $smscode = Model::factory('SmsLog')->create();
        $smscode->mobile = $mobile;
        $smscode->sms = $sms;
        $smscode->status = 0;
        $smscode->sendtime = time();
        $smscode->createdtime = time();
        $smscode->updatedtime = time();
        $smscode->save();

        return;
    }
}