<?php

namespace PressEnter\MatematiconBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use PressEnter\MatematiconBundle\Entity\Denuncia;

class DenunciaAdminController extends Controller
{
    /**
     * @param id Id del SharedDrawing
     */ 
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('t')
            ->from('PressEnterMatematiconBundle:Denuncia', 't')
            ->join('t.shared_drawing', 'sd')
            ->join('sd.drawing', 'd')
            ->andWhere('t.fecha_resolucion IS NULL')
            ->orderBy('t.id');

        $denuncias = $qb->getQuery()->getResult();
        return $this->render('PressEnterMatematiconBundle:DenunciaAdmin:index.html.twig', array(
            'denuncias' => $denuncias,
        ));
    }
  
  public function desestimarAction(Request $request, $denuncia_id)
  {
    $em = $this->getDoctrine()->getManager();
    $denuncia = $em->getRepository('PressEnterMatematiconBundle:Denuncia')->find($denuncia_id);
    if(!$denuncia)
    {
        throw $this->createNotFoundException('Unable to find Denuncia entity.');
    }

    $now = new \DateTime;
    $denuncia->setFechaResolucion($now);
    $em->persist($denuncia);
    $em->flush();
    return $this->redirect($this->generateUrl('denuncia_admin_index'));
  }
  
  public function despublicarAction(Request $request, $denuncia_id)
  {
    $em = $this->getDoctrine()->getManager();
    $denuncia = $em->getRepository('PressEnterMatematiconBundle:Denuncia')->find($denuncia_id);
    if(!$denuncia)
    {
        throw $this->createNotFoundException('Unable to find Denuncia entity.');
    }

    $now = new \DateTime;
    $em->remove($denuncia->getSharedDrawing());
    $em->flush();

    

    return $this->redirect($this->generateUrl('denuncia_admin_index'));
  }
}
