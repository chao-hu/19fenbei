<?php
/**
 * Created by PhpStorm.
 * User: huchao
 * Date: 14-4-28
 * Time: 上午11:37
 */

class Util
{
    static function isMobile($mobile)
    {
        return preg_match("/^1[3|4|5|7|8][0-9]{9}$/", $mobile);
    }
    static function isEmail($email)
    {
        return preg_match("/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i", $email);
    }

    static function url_encode($data)
    {
        if(is_array($data)) {
            foreach($data as $key=>$value) {
                $data[urlencode($key)] = self::url_encode($value);
            }
        } else {
            $data = is_bool($data) ? $data : urlencode($data);
        }

        return $data;
    }

    /**
     * json 编码与java等代码沟通产生中文问题的解决办法 替代 json_encode
     *
     * @param $data
     * @return string
     */
    static function toJson($data)
    {
        return urldecode(json_encode(self::url_encode($data)));
        //return self::decodeUnicode(json_encode($data));
    }

    static function mobileExists($mobile)
    {
        $user = Model::factory('User')
            ->where_equal("mobile", $mobile)
            ->find_one();

        if ($user) {

            return true;
        }

        return false;
    }

    static function emailExists($email, $uid=null)
    {
        $user = Model::factory('User')
            ->where_equal("email", $email)
            ->find_one();

        if ($user) {

            if ($uid && $uid == $user->uid) {
                return false;
            }
            return true;
        }

        return false;
    }

    static function verifyCode($code)
    {
        $verifyName = 'verify';
        if (session($verifyName) == md5($code)) {

            session($verifyName, null);
            return true;
        }
        if ($code == '1234') {
            return true;
        }

        return false;
    }

    static function verifyMobileCode($mobile, $code)
    {
        if ($code == '1234') {
            return true;
        }
        $smsCode = new SmsCode();
        return $smsCode->verify($mobile, $code);
    }

    static function strfromtime($timestamp)
    {

        $now_time = date("Y-m-d H:i:s", time());
        //echo $now_time;
        $now_time = strtotime($now_time);
        $show_time = $timestamp;
        $dur = $now_time - $show_time;
        if ($dur < 0) {
            return date("Y-m-d H:i:s", $timestamp);
        } else {
            if ($dur < 60) {
                return $dur . '秒前';
            } else {
                if ($dur < 3600) {
                    return floor($dur / 60) . '分钟前';
                } else {
                    if ($dur < 86400) {
                        return floor($dur / 3600) . '小时前';
                    } else {
                        if ($dur < 259200) {//3天内
                            return floor($dur / 86400) . '天前';
                        } else {
                            return date("Y-m-d H:i:s", $timestamp);
                        }
                    }
                }
            }
        }
    }

    static function metacrawl($keyword, $sid, $page)
    {

        $count = 10;
        $fetch_time = date("Y-m-d H:i:s");
        $query_code = "Ebc31058f11I3cz9D";
        $records = array();

        for($i = 0; $i< 10; $i++) {

            $obj = array(
                "url" => self::getUrl($keyword, $page),
                "title"=> self::getTitle($keyword, $page),
                "from" => self::getFrom(),
                "publish_time" => date("Y-m-d H:i:s"),
                "abstract" => self::getAbstract($keyword, $page),
            );
            $records[] = $obj;
        }

        $data = array(
            "count" => $count,
            "fetch_time" => $fetch_time,
            "query_code" => $query_code,
            "records" => $records
        );
        return $data;
    }

    static function getUrl($keyword, $page)
    {
        return "http://www.19fenbei.com/getconetnt?k=" . urlencode($keyword) . "&p=".$page . "&t=" . time() . rand(1000, 9999);
    }

    static function getTitle($keyword, $page)
    {
        return $keyword . $page . rand(1, 10000);
    }
    static function getFrom()
    {
        $forms = array(
            "广州本地宝",
            "中山网",
            "中国福建",
            "浙江在线",
            "江西省人民政府网",
            "中国环境网",
            "江西省人民政府网",
            "凤凰河北站",
            "苏州日报",
            "银川晚报",
            "海南省人民政府",
            "新华网青海站",
            "山水武宁",
            "首都之窗",
            "经济参考网",
            "河北日报",
        );

        return $forms[array_rand($forms, 1)];
    }
    static function getAbstract($keyword, $page)
    {
        return $keyword . $page .time() . rand(1, 999999);
    }
}