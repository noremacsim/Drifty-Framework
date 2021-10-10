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

namespace Drifty\route;

use Drifty\controller;

class router extends \Klein\Klein {

    public function setRoutes()
    {
        $this->dispatch();
    }

}

