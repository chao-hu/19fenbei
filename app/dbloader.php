<?php

/*
|--------------------------------------------------------------------------
| Create Redbean DAO
|--------------------------------------------------------------------------
|
| Create the loader class R to read the connection parameters and setup
| the connection.
|
*/

$dbconfig = require ROOT . '/app/config/database.php';

foreach ($dbconfig as $name => $config) {
    foreach ($config as $k=>$v) {
        ORM::configure($k, $v, $name);
    }
}
ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));



