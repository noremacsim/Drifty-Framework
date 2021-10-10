<?php
namespace Drifty\controller;

class welcomeController extends controller {
    const modal = 'welcome';

    public function welcome()
    {
        echo $this->view->render('welcome.twig');
    }
}