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

namespace Drifty\controller;

class controller {
    public $view;
    public $model;
    const controller_dir = 'App/Controllers';

    public function __construct() {
        $loader             = new \Twig\Loader\FilesystemLoader('App/Views/');
        $twig               = new \Twig\Environment($loader);
        $this->view         = $twig;

        $name               = $this->config['modal'];
        $path               = 'App/Models/' . $name . 'Model.php';
        if(file_exists($path)) {
            require $path;
            $modelName      = $name . 'Model';
            $this->model    = new $modelName();
        }
    }
}