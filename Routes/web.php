<?php
/** @var $route */

$route->respond('GET', '/', array(new \Drifty\controller\welcomeController, 'welcome'));