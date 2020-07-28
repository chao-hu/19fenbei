<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 15-2-1
 * Time: 下午11:12
 */


/**
 * session管理函数
 * @param string|array $name session名称 如果为数组则表示进行session设置
 * @param mixed $value session值
 * @return mixed
 */
function session($name,$value='') {
    $prefix   =  "";
    if('' === $value){
        if(0===strpos($name,'?')){ // 检查session
            $name   =  substr($name,1);
            if($prefix) {
                return isset($_SESSION[$prefix][$name]);
            }else{
                return isset($_SESSION[$name]);
            }
        }elseif(is_null($name)){ // 清空session
            if($prefix) {
                unset($_SESSION[$prefix]);
            }else{
                $_SESSION = array();
            }
        }elseif($prefix){ // 获取session
            return isset($_SESSION[$prefix][$name])?$_SESSION[$prefix][$name]:null;
        }else{
            return isset($_SESSION[$name])?$_SESSION[$name]:null;
        }
    }elseif(is_null($value)){ // 删除session
        if($prefix){
            unset($_SESSION[$prefix][$name]);
        }else{
            unset($_SESSION[$name]);
        }
    }else{ // 设置session
        if($prefix){
            if (!is_array($_SESSION[$prefix])) {
                $_SESSION[$prefix] = array();
            }
            $_SESSION[$prefix][$name]   =  $value;
        }else{
            $_SESSION[$name]  =  $value;
        }
    }
}
