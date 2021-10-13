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
        $this->loadModel();
    }

    public function loadModel($modelName = '')
    {
        if ($modelName === '')
        {
            $model_class = str_replace('Controller', 'Model', get_class($this));
            if (class_exists($model_class)) {
                $this->model    = new $model_class();
            }
        } else {
            $this->model    = new $modelName();
        }
    }
}