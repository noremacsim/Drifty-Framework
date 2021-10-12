<?php
namespace Drifty\Controllers;

class welcomeController extends controller {
    protected $config = [
      'modal' => 'welcome',
    ];

    public function welcome()
    {
        echo $this->view->render('welcome.twig');
        //print_r($this->model->properties);
    }

}