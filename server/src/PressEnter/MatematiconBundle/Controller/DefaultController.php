<?php

namespace PressEnter\MatematiconBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Edufw\services\educar\api\ApiCommunication;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $usr_id = 'testprueba2@educ.ar';
       $usr_pswd = '12345678';
       
       // Instancia de ApiComunication que permitirá realizar peticiones a la API de educar
       //ApiCommunication::setApiData(); 
       $api_object = new ApiCommunication();
       // Logueo al usuario
       $user_login = $api_object::RestActions()->loginUser($usr_id, $usr_pswd);
       // Pregunto si hubo un error en la petición
       if(!$user_login->error)
       {
           // Obtengo el token de login con el cual realizaré todas las peticiones de api que lo necesiten
           $login_token = $user_login->data['login_token'];
       } else {
           // Obtengo el codigo y mensaje de error
           $error_code = $user_login->getApiErrorCode();
           $error_msg = $user_login->getApiMessage();
       }
        return $this->render('PressEnterMatematiconBundle:Default:index.html.twig');
    }
}
