<?php

namespace PressEnter\MatematiconBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;


class ApiController extends Controller
{
  public function testAction($nombre = '')
  {
    
    return $this->render('PressEnterMatematiconBundle:Api:test.html.twig', array(
      'nombre' => $nombre,
    ));
  }
}
