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

namespace Drifty\Controllers;
use Drifty\Models;

class controller extends view {
    public $view;
    public $model;
    const controller_dir = 'App/Controllers';

    public function __construct() {
        parent::__construct();
        $this->loadModel();
    }

    /**
     * Load Related Model to Controller
     *
     * @param string $modelName
     */
    public function loadModel($modelName = '')
    {
        if ($modelName === '')
        {
            $modelClass = str_replace('Controller', 'Model', get_class($this));
            if (class_exists($modelClass)) {
                $this->model    = new $modelClass();
            }
        } else {
            $this->model    = new $modelName();
        }
    }
}