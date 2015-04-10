<?php
namespace Edufw\security;

/**
 * Funcion de la clase:
 * - Encriptar en RC4 y codificar en BASE64
 * - Decodificar en BASE64 y desencriptar en RC4
 * - Encriptar en RC4 y codificar en HEXADECIMAL
 * - Decodificar en HEXADECIMAL y desencriptar en RC4
 *
 * @author gseip
 * @version 20080507
 */
class CodecCrypt {

    /**
     * Encripta en RC4 y codifica a BASE64, una cadena
     * @param <string> $cad Cadena a aplicar la funcion
     * @param <string> $pass Clave de encriptacion
     * @return <string> hash encriptado
     */
    public static function codecRC4_BASE64($cad, $pass) {
        return base64_encode(mcrypt_encrypt(MCRYPT_ARCFOUR,$pass,$cad,MCRYPT_MODE_STREAM));
    }

    /**
     * Encripta en RC4 y codifica a HEXADECIMAL, una cadena
     * @param <string> $cad Cadena a aplicar la funcion
     * @param <string> $pass Clave de encriptacion
     * @return <string> hash encriptado
     */
    public static function codecRC4_HEX($cad, $pass) {
        return bin2hex(mcrypt_encrypt(MCRYPT_ARCFOUR,$pass,$cad,MCRYPT_MODE_STREAM, null));
    }

    /**
     * Decodifica en BASE64 y desencripta en RC4
     * @param <string> $cad Cadena a aplicar la funcion
     * @param <string> $pass Clave de encriptacion
     * @return <string> cadena original
     */
    public static function decodeBASE64_RC4($cad, $pass) {
        $decoding = base64_decode($cad);
        return mcrypt_decrypt(MCRYPT_ARCFOUR,$pass, $decoding ,MCRYPT_MODE_STREAM);
    }

    /**
     * Decodifica en HEXADECIMAL y desencripta en RC4
     * @param <string> $cad Cadena a aplicar la funcion
     * @param <string> $pass Clave de encriptacion
     * @return <string> cadena original
     */
    public static function decodeHEX_RC4($cad, $pass) {
        $decoding = pack("H*", $cad);
        return mcrypt_decrypt(MCRYPT_ARCFOUR,$pass, $decoding ,MCRYPT_MODE_STREAM, null);
    }

}
?>
