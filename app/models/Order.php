<?php

/**
 * Created by PhpStorm.
 * User: huchao
 * Date: 14-4-27
 * Time: 下午6:15
 */
class Order extends Model
{

    static $_table = 'order';

    CONST STATUS_NEW = '1';
    CONST STATUS_PAYED = '2';
    CONST STATUS_CANCEL = '-1';
    CONST STATUS_DELETED = '0';

    /*
     *
     *  self::$statusNew => '待支付',
        self::$statusPayed => '已支付',
        self::$statusCancel => '已取消',
        self::$statusDeleted => '已删除',

     *  */

    static $statusMap = array(
        self::STATUS_NEW => "待支付",
        self::STATUS_CANCEL => "已取消",
        self::STATUS_PAYED => "已支付",
        self::STATUS_DELETED => "已删除",
    );

    static function getState($status)
    {
        return  isset(self::$statusMap[$status]) ? self::$statusMap[$status] : "未定义";
    }

    static function generateOrderNum()
    {
        $ret = date("YmdHis");
        $ret .= rand(1000, 9999);
        return $ret;
    }
}