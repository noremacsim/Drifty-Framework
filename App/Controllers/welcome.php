<?php
namespace Drifty\Controllers;

class welcome extends controller {
    protected $config = [
      'modal' => 'welcome',
    ];
    protected $primaryKey = 'id';

    public function welcome()
    {
        return $this->render('welcome.twig');
    }

}