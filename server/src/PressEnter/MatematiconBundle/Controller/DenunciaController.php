<?php

namespace PressEnter\MatematiconBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use PressEnter\MatematiconBundle\Entity\Denuncia;

class DenunciaController extends Controller
{
    /**
     * @param id Id del SharedDrawing
     */ 
    public function denunciarAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        !$shared_drawing = $em->getRepository('PressEnterMatematiconBundle:SharedDrawing')->find($id);
        
        if(!$shared_drawing)
        {
            throw $this->createNotFoundException('Unable to find SharedDrawing entity.');
        }

        $error = false;
        $saved = false;
        $denuncia = null;
        if($request->getMethod() == 'POST')
        {
            if(trim($request->get('motivo')) == '')
            {
                $error = true;
            }
            else
            {
                $denuncia = new Denuncia();
                $denuncia->setSharedDrawing($shared_drawing);
                $denuncia->setMotivo($request->get('motivo'));
                $now = new \DateTime();
                $denuncia->setFechaHora($now);
                $em->persist($denuncia);
                $em->flush();
            }
        }

        return $this->render('PressEnterMatematiconBundle:Denuncia:denunciar.html.twig', array(
            'shared_drawing' => $shared_drawing,
            'error' => $error,
            'denuncia' => $denuncia,
        ));
    }
}
