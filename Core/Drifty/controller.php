<?php

namespace Drifty\controller;

class controller {
    public $view;
    public $model;

    const controller_dir = 'App/Controllers';

    public function __construct() {
        $loader             = new \Twig\Loader\FilesystemLoader('App/Views/');
        $twig               = new \Twig\Environment($loader);
        $this->view         = $twig;
        $name = static::modal;
        $path = 'App/Models/' . $name . 'Model.php';
        if(file_exists($path)) {
            require $path;
            $modelName      = $name . 'Model';
            $this->model    = new $modelName();
        }
    }
}