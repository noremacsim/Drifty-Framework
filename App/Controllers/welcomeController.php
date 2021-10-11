<?php
namespace Drifty\controller;

class welcomeController extends controller {
    protected $config = [
      'modal' => 'welcome',
    ];

    public function welcome()
    {
        echo $this->view->render('welcome.twig');
    }
}