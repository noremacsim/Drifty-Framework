<?php

include 'Core/preDispatch.php';
$startTime = microtime(true);
require_once 'Core/entryPoint.php';
ob_start();