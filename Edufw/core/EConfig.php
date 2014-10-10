<?php

namespace Edufw\core;

/**
 * Clase para la configuracion de aplicacion WEB.
 *
 * @author Gustavo Seip
 * @version 2.0
 */
class EConfig
{

    /** 
     * Establece las propiedades de la clase con valores de configuracion 
     * global.
     * @param array $values Arreglo que define valores de configuracion
     */
    public function set($values)
    {   
        foreach ($values as $name => $value) {
            $this->$name = $value;
        }   
    }
}
