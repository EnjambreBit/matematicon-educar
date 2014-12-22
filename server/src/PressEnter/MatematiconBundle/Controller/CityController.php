<?php

namespace PressEnter\MatematiconBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PressEnter\MatematiconBundle\Entity\Drawing;
use PressEnter\MatematiconBundle\Entity\SharedDrawing;

class CityController extends Controller
{
  /**
   * Return shared drawing image
   */
  public function imageAction($shared_drawing_id)
  {
    $em = $this->getDoctrine()->getManager();
    $shared_drawing = $em->getRepository('PressEnterMatematiconBundle:SharedDrawing')->find($shared_drawing_id);
    
    $headers = array(
        'Content-Type'     => 'image/png',
        'Cache-Control' => 'no-store, no-cache, must-revalidate',
        'Content-Disposition' => 'inline; filename="'.$shared_drawing_id.'.png"');

    $tmp = explode(',', $shared_drawing->getImage());
    $response = new Response(base64_decode($tmp[1]), 200, $headers);
    return $response;
  }

  /**
   * Return objets to create a city for the drawing with id = $drawing_id
   */
  public function createAction($drawing_id = '')
  {
    $em = $this->getDoctrine()->getManager();
    $drawing = $em->getRepository('PressEnterMatematiconBundle:Drawing')->find($drawing_id);
    $shared_drawing = $em->getRepository('PressEnterMatematiconBundle:SharedDrawing')->findOneBy(array('drawing' => $drawing));

    $drawing_data = json_decode($drawing->getJson());
    $result = array();
    $result[] = array('id' => $shared_drawing->getId(), 'zone' => $drawing_data->zone, 'title' => $drawing->getTitle());

    // Retrive 25 more objetcts for the same scene
    $qb = $em->createQueryBuilder();
    $qb->select('count(t.id)')
        ->from('PressEnterMatematiconBundle:SharedDrawing', 't')
        ->join('t.drawing', 'd')
        ->andWhere('d.scene = :scene')
        ->andWhere('d.id != :this_id')
        ->setParameter('scene', $drawing->getScene())
        ->setParameter('this_id', $drawing->getId());
    $q = $qb->getQuery();
    $count = $q->getSingleScalarResult();

    
    for($i=0; $i<25 && $i < $count; $i++)
    {
        $qb = $em->createQueryBuilder();
        $qb->select('t')
            ->from('PressEnterMatematiconBundle:SharedDrawing', 't')
            ->join('t.drawing', 'd')
            ->andWhere('d.scene = :scene')
            ->andWhere('d.id != :this_id')
            ->setParameter('scene', $drawing->getScene())
            ->setParameter('this_id', $drawing->getId())
            ->setMaxResults(1)
            ->setFirstResult(rand(0, $count-1));
        $sd = $qb->getQuery()->getSingleResult();
        
        $drawing_data = json_decode($sd->getDrawing()->getJson());
        $result[] = array('id' => $sd->getId(), 'zone' => $drawing_data->zone, 'title' => $sd->getDrawing()->getTitle());
    }
    return $this->render('PressEnterMatematiconBundle:City:create.json.twig', array('json' => json_encode($result)));
  }

  public function listAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    
    $page = $request->get('page', 0);

    $scene_id = $request->get('scene_id', 'scene_1'); // Chars after '_' is internal id TODO: improve
    $tmp = explode('_', $scene_id);
    $scene = $em->getRepository('PressEnterMatematiconBundle:Scene')->find($tmp[1]);
    
    return $this->render('PressEnterMatematiconBundle:Objects:list.json.twig', array('drawings' => $q->getResult()));
  }

}
