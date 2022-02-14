<?php
/** @var $route */

use Drifty\Controllers\welcome;

$route->respond('GET', '/', array(new welcome, 'welcome'));