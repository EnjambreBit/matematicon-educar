<?php

namespace PressEnter\MatematiconBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PressEnterMatematiconBundle:Default:index.html.twig');
    }
}
