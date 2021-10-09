<?php
use Drifty\route;

class driftyApplication {

    public $drifty_footer       = false;
    public $default_action      = 'welcome';
    public $default_controller  = 'welcomeController';

    public function start()
    {
        $router = new route\router();
        $router->setRoutes();
    }
}