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

class view {

    public function render($name, array $context = [])
    {
        global $page;
        $loader             = new \Twig\Loader\FilesystemLoader('App/Views/');
        $twig               = new \Twig\Environment($loader);
        $this->view         = $twig;
        $twig->addGlobal('page', $page);
        return $twig->render($name, $context);
    }

}