<?php
namespace Drifty\Controllers;

class welcome extends controller {
    public function welcome()
    {
        return $this->render('welcome.tpl');
    }

}