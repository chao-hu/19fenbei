<?php
/**
 * Created by PhpStorm.
 * User: huchao
 * Date: 14-4-27
 * Time: 下午6:15
 */

class User extends Model
{

    public static $_table = 'user';

    public function orders() {
        return $this->has_many('Order');
    }

    public function comments() {
        return $this->has_many('Comment');
    }

    public function address()
    {
        return $this->has_one('Address');
    }
}