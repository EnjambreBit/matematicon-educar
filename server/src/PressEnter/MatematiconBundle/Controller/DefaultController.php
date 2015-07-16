<?php

namespace PressEnter\MatematiconBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use PressEnter\MatematiconBundle\Entity\Drawing;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('PressEnterMatematiconBundle:Default:index.html.twig', array(
            'user' => $this->getUser()
        ));
    }

    public function downloadAction(Request $request)
    {
        return $this->render('PressEnterMatematiconBundle:Default:download.html.twig', array(
        ));
    }

    /**
     *  publica una ficha de objecto
     */
    public function objectAction($item = '')
    {
      $em = $this->getDoctrine()->getManager();
      $drawing = $em->getRepository('PressEnterMatematiconBundle:Drawing')->find($item);
      if(!$drawing)
      {
          throw $this->createNotFoundException('Unable to find Drawing entity.');
      }

      return $this->render('PressEnterMatematiconBundle:Default:object_share.html.twig', array('item'=> $item, 'drawing' => $drawing));
    }

}
