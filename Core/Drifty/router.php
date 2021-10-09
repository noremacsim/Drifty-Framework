<?php
namespace Drifty\route;

use Drifty\controller\welcomeController;

class router extends \Klein\Klein {

    public function setRoutes()
    {
        $this->respond('GET', '/test', array(new welcomeController,'welcome'));
        $this->dispatch();
    }

}

