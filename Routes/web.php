<?php
/** @var $route */

$route->respond('GET', '/', array(new \Drifty\Controllers\welcomeController, 'welcome'));