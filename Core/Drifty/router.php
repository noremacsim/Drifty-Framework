<?php
namespace Drifty\route;

use Drifty\controller;

class router extends \Klein\Klein {

    public function setRoutes()
    {
        $this->dispatch();
    }

}

