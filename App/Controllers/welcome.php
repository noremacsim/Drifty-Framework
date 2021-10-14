<?php
namespace Drifty\Controllers;

class welcome extends controller {
    protected $config = [
      'modal' => 'welcome',
    ];
    protected $primaryKey = 'id';

    public function welcome()
    {
        echo $this->render('welcome.twig');
    }

}