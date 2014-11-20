<?php

namespace PressEnter\MatematiconBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PressEnter\MatematiconBundle\Entity\Drawing;

class ObjectsController extends Controller
{
  public function saveAction(Request $request)
  { 
    $em = $this->getDoctrine()->getManager();
    if($request->get('id') != '')
    {
      $drawing = $em->getRepository('PressEnterMatematiconBundle:Drawing')->find($request->get('id'));
    }
    else
    {
      $drawing = new Drawing();
    }
    $drawing->setTitle($request->get('title'));
    $drawing->setJson ($request->get('json'));
    $drawing->setImage($request->get('thumb'));
    $scene_id = $request->get('scene_id'); // Chars after '_' is internal id TODO: improve
    $tmp = explode('_', $scene_id);
    $scene = $em->getRepository('PressEnterMatematiconBundle:Scene')->find($tmp[1]);
    $drawing->setScene($scene);

    $em->persist($drawing);
    $em->flush();
    return $this->render('PressEnterMatematiconBundle:Objects:save.json.twig', array('drawing' => $drawing));
  }
  
  public function createAction()
  {   
    return $this->render('PressEnterMatematiconBundle:Objects:create.json.twig');
  }

  public function imageAction($item = '')
  {
    $em = $this->getDoctrine()->getManager();
    $drawing = $em->getRepository('PressEnterMatematiconBundle:Drawing')->find($item);
    
    $headers = array(
        'Content-Type'     => 'image/png',
        'Cache-Control' => 'no-store, no-cache, must-revalidate',
        'Content-Disposition' => 'inline; filename="'.$item.'.png"');

    $tmp = explode(',', $drawing->getImage());
    $response = new Response(base64_decode($tmp[1]), 200, $headers);
    return $response;
  }

  public function getAction($item = '')
  {
    $em = $this->getDoctrine()->getManager();
    $drawing = $em->getRepository('PressEnterMatematiconBundle:Drawing')->find($item);
    
    return $this->render('PressEnterMatematiconBundle:Objects:get.json.twig', array('json' => $drawing->getJson()));
  }

  public function listAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    
    $page = $request->get('page', 0);

    $scene_id = $request->get('scene_id', 'scene_1'); // Chars after '_' is internal id TODO: improve
    $tmp = explode('_', $scene_id);
    $scene = $em->getRepository('PressEnterMatematiconBundle:Scene')->find($tmp[1]);
    
    $qb = $em->createQueryBuilder();
    $qb->select('t')
        ->from('PressEnterMatematiconBundle:Drawing', 't')
        ->andWhere('t.scene = :scene')
        ->setParameter('scene', $scene)
        ->orderBy('t.id')
        ->setFirstResult($page * 3)
        ->setMaxResults(3); 
    $q = $qb->getQuery();
    return $this->render('PressEnterMatematiconBundle:Objects:list.json.twig', array('drawings' => $q->getResult()));
  }

  public function deleteAction($item = '')
  {
    return $this->render('PressEnterMatematiconBundle:Objects:delete.json.twig');
  }

}
