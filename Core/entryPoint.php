<?php

$GLOBALS['starttTime']      = microtime(true);
$GLOBALS['drifty_version']   = 'v0.1';
$GLOBALS['drifty_flavor']    = 'Lime Pie';

set_include_path(
    __DIR__ .'/..'.PATH_SEPARATOR.
    get_include_path()
);

if (!defined('PHP_VERSION_ID')) {
    $version_array = explode('.', phpversion());
    define('PHP_VERSION_ID', ($version_array[0] * 10000 + $version_array[1] * 100 + $version_array[2]));
}

$BASE_DIR = realpath(dirname(__DIR__));
$autoloader = $BASE_DIR.'/vendor/autoload.php';
if (file_exists($autoloader)) {
    require_once $autoloader;
} else {
    die('Composer autoloader not found. please run "composer install"');
}

require_once 'Core/Drifty/controller.php';
require_once 'Core/Drifty/model.php';
require_once 'Core/Drifty/mysql.php';
require_once 'Core/Drifty/router.php';
require_once 'Core/Drifty/driftyApplication.php';

//TODO: Check if in debug,dev mode first
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

/*
 *  Require Controllers
 */
foreach (glob(Drifty\controller\controller::controller_dir . "/*.php") as $filename)
{
    require_once $filename;
}

$drifty = new driftyApplication();
$drifty->start();

