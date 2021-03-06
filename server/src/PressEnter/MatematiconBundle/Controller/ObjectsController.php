<?php

namespace PressEnter\MatematiconBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PressEnter\MatematiconBundle\Entity\Drawing;
use PressEnter\MatematiconBundle\Entity\SharedDrawing;

class ObjectsController extends Controller
{
    //TODO: faltan muchos chequeos de seguridad
  public function insertAction(Request $request)
  { 
    $em = $this->getDoctrine()->getManager();
    $drawing = $em->getRepository('PressEnterMatematiconBundle:Drawing')->find($request->get('id'));
    if(!$drawing || $drawing->getUser() != $this->getUser())
    {
        throw $this->createNotFoundException('Unable to find Drawing entity.');
    }
    $shared_drawing = $em->getRepository('PressEnterMatematiconBundle:SharedDrawing')->findOneBy(array('drawing' => $drawing));
    
    if(!$shared_drawing)
    {
        $shared_drawing = new SharedDrawing();
        $shared_drawing->setDrawing($drawing);
    }
    $shared_drawing->setImage($drawing->getImage());
    $em->persist($shared_drawing);
    $em->flush();

    return $this->render('PressEnterMatematiconBundle:Objects:insert.json.twig', array());
  }
  
  public function saveAction(Request $request)
  { 
    $em = $this->getDoctrine()->getManager();
    if($request->get('id') != '')
    {
      $drawing = $em->getRepository('PressEnterMatematiconBundle:Drawing')->find($request->get('id'));
      if($drawing->getUser() != $this->getUser())
      {
         throw $this->createNotFoundException('Unable to find Drawing entity.');
      }
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
    $drawing->setUser($this->getUser());
    $em->persist($drawing);
    $em->flush();
    return $this->render('PressEnterMatematiconBundle:Objects:save.json.twig', array('drawing' => $drawing));
  }
  
  public function imageAction($item = '')
  {
    $em = $this->getDoctrine()->getManager();
    $drawing = $em->getRepository('PressEnterMatematiconBundle:Drawing')->find($item);
    if(!$drawing || $drawing->getUser() != $this->getUser())
    {
        throw $this->createNotFoundException('Unable to find Drawing entity.');
    }
    
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
    
    if(!$drawing || $drawing->getUser() != $this->getUser())
    {
        throw $this->createNotFoundException('Unable to find Drawing entity.');
    }
    
    $headers = array(
        'Content-Type'     => 'text/json',
        'Cache-Control' => 'no-store, no-cache, must-revalidate',
        'Content-Disposition' => 'attachment; filename="'.$item.'.json"');

    $response = new Response($drawing->getJson(), 200, $headers);
    return $response;
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
        ->andWhere('t.user = :user')
        ->setParameter('scene', $scene)
        ->setParameter('user', $this->getUser())
        ->orderBy('t.id')
        ->setFirstResult($page * 3)
        ->setMaxResults(3); 
    $q = $qb->getQuery();
    return $this->render('PressEnterMatematiconBundle:Objects:list.json.twig', array('drawings' => $q->getResult()));
  }

  public function deleteAction($item = '')
  {
    $em = $this->getDoctrine()->getManager();
    $drawing = $em->getRepository('PressEnterMatematiconBundle:Drawing')->find($item);
    if(!$drawing || $drawing->getUser() != $this->getUser())
    {
        throw $this->createNotFoundException('Unable to find Drawing entity.');
    }
    $shared_drawing = $em->getRepository('PressEnterMatematiconBundle:SharedDrawing')->findOneBy(array('drawing' => $drawing));
    if($shared_drawing)
    {
        $em->remove($shared_drawing);
    }
    $em->remove($drawing);
    $em->flush();
    return $this->render('PressEnterMatematiconBundle:Objects:delete.json.twig');
  }

}
