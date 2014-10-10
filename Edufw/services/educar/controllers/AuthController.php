<?php
namespace Edufw\services\educar\controllers;
use \Edufw\services\educar\controllers\BloquesController;


/**
 * AuthController
 *
 * @author pgambetta
 */
class AuthController extends BloquesController {
    
//     /**
//      * Propiedad que posee datos de session.
//      * Puede ser consultada desde cualquier controlador que extienda de AuthController.
//      *
//      * @var Array
//      */
    public $sessionData = array('usr_id' => null, 'login_token' => null);
    
//     //Propiedades privadas de la clase
//     private $protectedMethod; //lista de metodos que estan protegidos por ACL
    private $controller;
    
//     /**
//      * Instancia de la SessionUtilities
//      *
//      * @var SessionUtilities
//      */
    public $sessionObj;
    
//     /**
//      * @method __construct
//      * 
//      * Es el constructor de la clase, recibe dos parámetros:
//      * 
//      * @param string $bloque Es el alias del bloque a donde estoy parado
//      * @param boolean $obtener_estructura true=>para obtener la estructura del sitio, false=>para lo contrario
//      * @param array $protected Si se desea proteger a todo el controlador no ingresar nada, array('method1, methodN) para proteger a los métodos deseados y null si no se desea proteger a ningún método
//      * 
//      */
    public function __construct() {
    	parent::__construct();
        //$this->sessionObj = new SessionUtilities(null, $this->local_config['SESSION_NAME']);
        //$this->protectedMethod = ($protected === false) ? array('noMethodProtected') : $this->protectedMethod = $protected;
    }
    
//     /**
//      * @method logout
//      * 
//      * Método que saca al usuario de la sesión.
//      * 
//      */
//     public function logout (){
//         try{
//             $this->RestActions()->logout($this->sessionData['login_token']);
//         } catch (Exception $e){
//             ELogger::log('[Auth/logout] No se pudo desloguear al usuario del repositorio -> ' . $this->sessionData['usr_id'], ELoggerLevel::LEVEL_WARN);
//         }
//         $this->sessionObj->logout();
//         ERouter::redirect($this->local_config['LOGOUT_CALLBACK_URL'], false);
//     }
    
//     public function beforeRunAction($controller, $action) {
//         $this->_data['logged'] = false;
//         $this->_data['usr_id'] = "";
//         $this->_data['login_url'] = $this->local_config['LOGIN_URL'];
//         $this->_data['registro_url'] = $this->global_config['REGISTRO_URL'];
//         $this->_data['logout_url'] = $this->local_config['LOGOUT_URL'];
//         if($this->sessionObj->issetData('login_token') && $this->sessionObj->issetData('usr_id')){
//             $this->setSessionVariables($controller);
//         } else if($this->authCheckUserLogged()){
//             $this->setSessionVariables($controller);
//         } else {
//             $controller = substr($controller, 0, strlen($controller) - 10);
//             $continuar = $this->buildCallbackForLogin($controller, $action);
//             $url = $this->global_config['LOGIN_ERROR'] . $continuar;
//             $this->protectedMethod[] = 'login';
//             if(!empty($this->protectedMethod) && $this->protectedMethod != '*'){
//                 foreach ($this->protectedMethod as $value) {
//                     if($value === $action) {
//                         if(!$this->_data['logged']){
//                             ERouter::redirect($url, false);
//                         }
//                     }
//                 }
//             } else if($this->protectedMethod == '*') {
//                 if(!$this->_data['logged']){
//                     ERouter::redirect($url, false);
//                 }
//             }
//         }
//     }
    
//     public function cheqLoginAjax() {
//         $response = array();
//         $response['msg'] = 'Por favor, inicie sesión para realizar esta acción.';
//         $success = false;
//         if ($this->sessionData['login_token'] !== null) {
//             $success = true;
//             $response['msg'] = 'El usuario está en sesión';
//         }
//         if (isset($this->params['numero'])) {
//             $response['numero'] = $this->params['numero'];
//         }
//         $this->backenYui->outputYuiJson($success, $response);
//     }   
    
//     public function cheqLogin() {
//         return ($this->sessionData['login_token'] !== null) ? true : false;
//     }
    
//     public function buildCallbackForLogin($controller, $action){
//         $subContinuar = EWebApp::conf()->APP_URL . $controller . "/loginCallBack?method=" . $action;
//         foreach ($_REQUEST as $key => $value){
//             if($key !== 'r' && $key !== '__params'){
//                 $subContinuar .= '&' . $key . '=' . $value;
//             }
//             else if($key == '_params'){
//                 $variables = json_decode($value, true);
//                 foreach ($variables as $secKey => $secValue) {
//                     $subContinuar .= '&' . $secKey . '=' . $secValue;
//                 }
//             }
//         }
//         return "?servicio=" . $this->local_config['servicio'] . "&continuar=" . urlencode($subContinuar);
//     }
    
//     public function setPerfilUrl($continuar){
//         $token = '?login_token= ' . $this->sessionData['login_token'];
//         $servicio='&servicio=educar';
//         $url = urlencode(EWebApp::conf()->APP_URL . $continuar);
//         $continuar = '&continuar=' . $url;
//         $params = $token . $servicio . $continuar;
//         return $this->global_config['DOMAIN_LOGIN'] . 'cuentas/PerfilController/index' . $params;
//     }
    
//     // Metodo protegido login
//     public function login(){}
    
//     // Login callback generico
//     public function loginCallBack(){
//         ERouter::redirect($this->controller . '/index', true);
//     }
    
//     private function setSessionVariables($controller){
//         $this->controller = str_replace('Controller', '', $controller);
//         $this->_data['logged'] = true;
//         $this->sessionData['usr_id'] = $this->sessionObj->getSessionData('usr_id');
//         $this->sessionData['login_token'] = $this->sessionObj->getSessionData('login_token');
//         $this->_data['usr_id'] = $this->sessionData['usr_id'];
        
//     }
    
//     private function authCheckUserLogged (){
//         try{
//             if(isset($_REQUEST["login_token"])){
//                 $login_token = $_REQUEST["login_token"];
//                 $recibedData = $this->RestActions()->checkUserLogged($login_token);
//                 if(!$recibedData->error){
//                     $this->sessionObj->addDataWithoutUser(array('usr_id'=>$recibedData->data['usr_id'], 'login_token' => $login_token));
//                     return true;
//                 }
//             }
//         } catch (Exception $e){
//             ELogger::log('[Auth/checkUserLogged] usuario no autenticado -> ' . $recibedData->data['usr_id'] . 'ERROR: (' . $e->getMessage() . ')', ELoggerLevel::LEVEL_WARN);
//         }
//         return false;
//     }
    
//     private function noMethodProtected(){/*No hay ningun metodo protegido...*/}
    
}

