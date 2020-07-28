<?php

/*
|--------------------------------------------------------------------------
| Register The Composer Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so that we do not have to worry about the
| loading of any our classes "manually". Feels great to relax.
|
*/
require ROOT . '/app/helper/common.php';
require ROOT . '/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Register The RedSlim Auto Loader
|--------------------------------------------------------------------------
|
| We register an auto-loader "behind" the Composer loader that can load
| model classes on the fly.
|
*/

// Autoloader to load classes in /app/models/ /app/helper
spl_autoload_register(function ($class) {

    $paths = array(
        '/app/lib/Paris/',
        '/app/lib/Alipay/',
        '/app/models/',
        '/app/helper/',
        '/app/middleware/',
    );

    foreach ($paths as $path) {
        if (is_file($file = ROOT .$path . $class . '.php')) {
            require $file;
        }
    }
});

