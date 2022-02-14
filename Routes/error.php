<?php
/** @var $route */

$route->respond('GET', '/forbidden', function ($request) {
    $pageController = new \Drifty\Controllers\controller();
    return $pageController->render('error/403.tpl');
});