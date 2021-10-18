<?php
/*
 * Drifty FrameWork by noremacsim(Cameron Sim)
 *
 * This File has been created by noremacsim(Cameron Sim) under the Drifty FrameWork
 * And will follow all the Drifty FrameWork Licence Terms which can be found under Licence
 *
 * @author     Cameron Sim <mrcameronsim@gmail.com>
 * @author     noremacsim <noremacsim@github>
 */

/** @var $config */

set_include_path(
    __DIR__ .'/..'.PATH_SEPARATOR.
    get_include_path()
);

if (!defined('PHP_VERSION_ID')) {
    $version_array = explode('.', phpversion());
    define('PHP_VERSION_ID', ($version_array[0] * 10000 + $version_array[1] * 100 + $version_array[2]));
}

$subject = new stdClass();// Model Objects
$driftyApp = new stdClass();// Core System Variables;
$driftyApp->starttTime     = microtime(true);
$driftyApp->drifty_version   = 'v0.8.0';
$driftyApp->drifty_flavor   = 'Lime Pie';

/*
 *  Require Config
 */
if (file_exists('config.php')) {
    require_once 'config.php';
} else {
    require_once 'config.example.php';
}

foreach ($config as $configItemKey => $configItemArray) {
    $driftyApp->{$configItemKey} = (object)$configItemArray;
}

$BASE_DIR = realpath(dirname(__DIR__));
$autoloader = $BASE_DIR.'/vendor/autoload.php';
if (file_exists($autoloader)) {
    require_once $autoloader;
} else {
    die('Composer autoloader not found. please run "composer install"');
}

require_once 'Core/Drifty/dotEnv.php';
require_once 'Core/Drifty/driftyApplication.php';

(new DotEnv( '.env'))->load();


if (getenv('DEBUG') === 'true') {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}


/*
 *  Require Controllers
 */
foreach (glob(Drifty\Controllers\controller::controller_dir . "/*.php") as $filename)
{
    require_once $filename;
}

/*
 *  Require Models
 */
foreach (glob(Drifty\Models\model::model_dir . "/*.php") as $filename)
{
    require_once $filename;
    $moduleFile = explode('.php', explode(Drifty\Models\model::model_dir . '/', $filename)[1]);
    $thisclass  = 'Drifty\Models\\' . $moduleFile[0];

    $subject->{$moduleFile[0]} = new $thisclass;
    unset($moduleFile);
    unset($thisclass);
}

$user = new \Drifty\Models\user();

unset($config);
unset($configItemKey);
unset($configItemArray);
unset($version_array);

/*
 * Register Routes
 */
$route = new Drifty\Routes\router;
foreach (glob("Routes/*.php") as $filename)
{
    require_once $filename;
}
$route->setRoutes();
