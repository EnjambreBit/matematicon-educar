<?php

namespace PressEnter\MatematiconBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;


class ObjectsController extends Controller
{
  public function createAction()
  {   
    return $this->render('PressEnterMatematiconBundle:Objects:create.json.twig');
  }

  public function updateAction($item = '')
  {
    return $this->render('PressEnterMatematiconBundle:Objects:update.json.twig');
  }

  public function getAction($item = '')
  {
    return $this->render('PressEnterMatematiconBundle:Objects:get.json.twig');
  }

  public function listAction()
  {
    return $this->render('PressEnterMatematiconBundle:Objects:list.json.twig');
  }

  public function deleteAction($item = '')
  {
    return $this->render('PressEnterMatematiconBundle:Objects:delete.json.twig');
  }

}
