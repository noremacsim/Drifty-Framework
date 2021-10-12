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

use Drifty\Routes;

class driftyApplication {

    public $drifty_footer       = false;
    public $default_action      = 'welcome';
    public $default_controller  = 'welcomeController';

    public function start()
    {
        $router = new Routes\router();
        $router->setRoutes();
    }
}