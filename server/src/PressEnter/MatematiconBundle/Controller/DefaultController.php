<?php

namespace PressEnter\MatematiconBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Edufw\services\educar\api\ApiCommunication;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        var_dump($this->getUser());
        exit();
        return $this->render('PressEnterMatematiconBundle:Default:index.html.twig');
    }

    public function loginAction()
    {
        $usr_id = 'testprueba2@educ.ar';
       $usr_pswd = '12345678';
       
       // Instancia de ApiComunication que permitirá realizar peticiones a la API de educar
       ApiCommunication::setApiData(); 
       $api_object = new ApiCommunication();
       // Logueo al usuario
       $user_login = $api_object::RestActions()->loginUser($usr_id, $usr_pswd);
       // Pregunto si hubo un error en la petición
       var_dump($user_login);
       if($user_login->error)
       {
           // Obtengo el token de login con el cual realizaré todas las peticiones de api que lo necesiten
           $login_token = $user_login->data['login_token'];
           var_dump($api_object::RestActions()->getUserData($login_token));
       } else {
           // Obtengo el codigo y mensaje de error
           $error_code = $user_login->getApiErrorCode();
           $error_msg = $user_login->getApiMessage();
       }
       die('aca');
    }
}
