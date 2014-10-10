<?php

namespace Edufw\security;

use Edufw\core\EWebApp;

/**
 * Clase para validar.
 *
 * @author pgambetta
 * @version 20111011
 */
class Validacion {
    
    /**
     * Valida si el valor es integer o no
     * 
     * @param <type> $value
     * 
     * @return <boolean> true, false
     */
    public static function isInteger($value){
        return is_int($value);
    }
    
    /**
     * Valida si un string tiene al menos $len caracteres
     * 
     * @param <String> $strValue
     * 
     * @return <Integer> $len
     */
    public static function isMinLength($strValue, $len){
        if (strlen($strValue) >= $len) {
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * Valida si un tiene como maximo $len caracteres
     * 
     * @param <String> $strValue
     * 
     * @return <Integer> $len
     */
    public static function isMaxLength($strValue, $len){
        if (strlen($strValue) <= $len) {
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * Valida si el valor es alphabetic (FALTA HACER, DEVUELVE TRUE)
     * 
     * @param <type> $value
     * 
     * @return <boolean> true, false
     */
    public static function isAlphabetic($value){
      return true;
    }
    
    /**
     * Valida si el valor es alphanumeric (FALTA HACER, DEVUELVE TRUE)
     * 
     * @param <type> $value
     * 
     * @return <boolean> true, false
     */
    public static function isAlphaNumeric($value){
        return true;
    }
    
    /**
     * Valida si el sexo posee el formato necesario para insertar en base de datos al sexo de la persona
     * 
     * @param <type> $value
     * 
     * @return <boolean> true, false
     */
    public static function isSexoValid($value){
        if($value === "M" || $value ==="F"){
            return true;
        }
        return false;
    }
    
    /**
     * Valida si la fecha posee el formato de fecha necesario para insertar en base de datos
     * 
     * @param <type> $strDate
     * 
     * @return <boolean> true, false
     */
    public static function isDate($strDate){
        $arr = explode("/", $strDate);
        if(count($arr) != 3){
            return false;
        }
        if(intval($arr[0]) < 0 || intval($arr[0]) > 31 ){
            return false;
        }
        if(intval($arr[1]) < 0 || intval($arr[1]) > 12 ){
            return false;
        }
        if(intval($arr[2]) < 1900 || intval($arr[2]) > 2100){
            return false;
        }
        return true;
    }
    
    /**
     * Valida si el valor es vacio o null
     * 
     * @param <type> $value
     * 
     * @return <boolean> true, false
     */
    public static function isEmpty($value){
        if(($value === '') || ($value === null)){
            return true;
        }
        return false;
    }

    /**
     * Valida si la pregunta o respuesta posee el formato necesario para ser insertado en base de datos (FALTA HACER, DEVUELVE TRUE)
     * 
     * @param <type> $value
     * 
     * @return <boolean> true, false
     */
    public static function isValidQuestionOrResponse($value){
        return true;
    }
    
    /**
     * Valida si el email cumple con el regex establecido
     * 
     * @param <type> $value
     * 
     * @return <boolean> true, false
     */
    public static function validarEmail($value){
        $valor = preg_match("/" . EWebApp::conf()->getEmailEducar . "/", $value, $matches);
        // Usuario educar
        if((int) $valor !== 0)
        {
            if(preg_match("/" . EWebApp::conf()->emailEducarExpresion . "/", $matches[1])){
                return true;
            }
        } 
        // Usuario mail propio
        else {
            $length = strlen(substr($value, 0, strpos($value, '@')));
            if($length >= 1 && $length <= 30){
                if(preg_match("/" . EWebApp::conf()->emailExpresion . "/", $value)){
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Valida si el email cumple con el regex establecido y devuelve un mensaje de error
     * 
     * @param <type> $value
     * 
     * @return <boolean> true, false
     */
    public static function obtenerError($value, $mensaje){
        $valor = preg_match("/" . EWebApp::conf()->getEmailEducar . "/", $value, $matches);
        // Usuario educar
        if((int) $valor !== 0)
        {
            $length = strlen($matches[1]);
            if($length < 6 || $length > 30){
                return $mensaje . ' debe contener entre 6 y 30 caracteres';
            }
        
            if(!preg_match("/" . EWebApp::conf()->emailEducarExpresion . "/", $matches[1])){
                return $mensaje . ' sólo puede contener letras y números en minúscula.';
            }
        } 
        // Usuario mail propio
        else {
            $length = strlen(substr($value, 0, strpos($value, '@')));
            if($length < 6 || $length > 30){
                return $mensaje . ' debe contener entre 6 y 30 caracteres';
            }
            
            if(!preg_match("/" . EWebApp::conf()->emailExpresion . "/", $value)){
                return $mensaje . ' sólo puede contener letras, números, puntos, guiones y guiones bajos.';
            }
        }
        
        return 'Hubo un problema en el sistema de validaciones.';
    }
    
    /**
     * Valida si el password cumple o no con nuestras restricciones (FALTA COMPLETAR EL VALIDADOR CON UN REGEX)
     * 
     * @param <type> $pswd
     * @param <type> $pswd2
     * 
     * @return <boolean> true, false
     */
    public static function validarPswd($pswd, $pswd2, $onlyBool = false) {
        if($onlyBool){
            return (!empty($pswd) || $pswd===$pswd2) ? true : false;
        }
        $error = FALSE;
        if (empty($pswd))  { return "Por favor ingrese una contraseña";  }
        $badPassword = function($pswd) { return (preg_match("/" . EWebApp::conf()->pswdExpresion . "/", $pswd) != 1); };
        if ($badPassword($pswd)) { return "Sólo se permiten letras (a-z) ASCII, números (0-9), y puntos (.)"; }
        $badPassword = strlen($pswd) < EWebApp::conf()->pswdMinLenght;
        if ($badPassword) { return "La contraseña no puede tener menos de " . EWebApp::conf()->pswdMinLenght . " caracteres"; }
        $badPassword = (empty($pswd2) || $pswd!==$pswd2); //reingreso password vacio o no coincide con password
        if ($badPassword) { return "La contraseña no coincide"; }
        return FALSE;
    }
    
    /**
     * Valida si el captcha es correcto
     * 
     * @param <type> $server
     * @param <type> $value
     * @param <type> $response
     * 
     * @return <type> Devuelve array de recaptcha, string con mensaje de error o false
     */
    public static function validarCaptcha($server, $challenge, $response) {
        EWebApp::loadClass('util/recaptcha');
        if (!isset($response)) {  return "No se ingresó el código captcha"; }
        //si ingresó mal el captcha.
        $resp = recaptcha_check_answer(EWebApp::conf()->captcha_privatekey, $server, $challenge, $response);
        if (!$resp->is_valid) { return "Captcha inválido";    }
        else {    return "No se ingresó el código captcha";   }
        return FALSE;
    }

    /**
     * Valida diponibilidad del email representando al username
     * 
     * Nota: Previo a llamar a este metodo es conveniente que se valide el email
     * 
     * @param <type> $email
     * 
     * @return <boolean> true, false
     */
    public static function validarDisponibilidad($email) {
        if( $email == "" || $email == null ) {
            return "error";
        }
        $sql = 'SELECT  COUNT (usr_id) AS count FROM usuario WHERE usr_id = :usr_id';
        $parameters[] = array('usr_id',$email);
        $result = EDbQuery::executeQuery($sql, null, $parameters);
        if($result[0]["count"] == 0){
            return false;
        } else{
            return true;
        }
    }

    // PHP's strip_tags() function will remove tags, but it
    // doesn't remove scripts, styles, and other unwanted
    // invisible text between tags.  Also, as a prelude to
    // tokenizing the text, we need to insure that when
    // block-level tags (such as <p> or <div>) are removed,
    // neighboring words aren't joined.
    public static function strip_html_tags($text){
        $text = trim($text);
        $text = preg_replace(
            array(
                // Remove invisible content
                '@<head[^>]*?>.*?</head>@siu',
                '@<style[^>]*?>.*?</style>@siu',
                '@<script[^>]*?.*?</script>@siu',
                '@<object[^>]*?.*?</object>@siu',
                '@<embed[^>]*?.*?</embed>@siu',
                '@<applet[^>]*?.*?</applet>@siu',
                '@<noframes[^>]*?.*?</noframes>@siu',
                '@<noscript[^>]*?.*?</noscript>@siu',
                '@<noembed[^>]*?.*?</noembed>@siu',

                // Add line breaks before & after blocks
                '@<((br)|(hr))@iu',
                '@</?((address)|(blockquote)|(center)|(del))@iu',
                '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
                '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
                '@</?((table)|(th)|(td)|(caption))@iu',
                '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
                '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
                '@</?((frameset)|(frame)|(iframe))@iu',
            ),
            array(
                ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
                "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
                "\n\$0", "\n\$0",
            ),
            $text
        );

        // Remove all remaining tags and comments and return.
        return strip_tags($text);
    }
    
    public static function validate_html_tags($desc, $noException = false)
    {
        $trimDesc = trim($desc);
        $length = strlen($trimDesc);
        $valid = true;
        if($length == 0)
          $valid = false;
        $stripDesc = Validacion::strip_html_tags($trimDesc);
        $lengthStrip = strlen($stripDesc);
        if($length > $lengthStrip)
          $valid = false;
        if(!$noException && !$valid)
          throw new EException('El texto recibido no superó el control', -1);
        return $valid;
    }
    
    /**
     * Valida la versión del navegador del usuario
     * 
     * @param <array> $supported_browsers [OPCIONAL] Es un array asociativo (clave, valor), donde clave 
     * es el nombre del navegador y valor es el mínimo número de versión aceptado (null, para 
     * todas las versiones)
     * 
     * Por default: array('chrome' => null, 'firefox' => 4);
     * 
     * @return <array> success => boolean, browser => browser data
     */
    public static function supportedBrowser($supported_browsers = array('chrome' => null, 'firefox' => 4)){
        //Detección del navegador del usuario
        $known = array('msie', 'firefox', 'chrome', 'opera');
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9]+(?:\.[0-9]+)?)#';
        preg_match_all($pattern, $agent, $matches);
        $i = count($matches['browser'])-1;
        $browser = array($matches['browser'][$i] => $matches['version'][$i]);
        foreach ($supported_browsers as $supported_browser => $version) {
            if(isset($browser[$supported_browser])){
                if($version == null || $browser[$supported_browser] >= $version){
                    return array('success' => true, 'browser' => $browser);
                }
            }
        }
        return array('success' => false, 'browser' => $browser);
    }

}