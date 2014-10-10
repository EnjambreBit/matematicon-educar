<?php
/* 
 * Aquí se definen las rutas parametrizadas para el proyecto.
 * Importante:
 * - Se usan expresiones regulares.
 * - Se deben ordenar las reglas de los mas especifico a lo mas general. Esta condición de puede determinar en base a 
 *   la cantidad de "/" y subcadenas, que se encuentren en el patron.
 *   Ej. La regla '/\//' es mas general que '/\/(\w+){1}\/(\w+){1}/', porque...
 *      -- cant "/" de '/\//' = 1 y cant subcadenas = 0
 *      -- cant "/" de '/\/(\w+){1}\/(\w+){1}/' es 2 y cant subcadenas = 2
 *      -- Total1 '/\//' = 1 + 0 = 1
 *      -- Total2 '/\/(\w+){1}\/(\w+){1}/' = 2 + 2 = 4
 *      Resultado: Total2 > Total1, por lo tanto; el patron '/\//' es mas general que el patron '/\/(\w+){1}\/(\w+){1}/'
 * - Si dos patrones tienen el mismo orden, solo difieren en su semantica.
 *   Ej. "//\admin/\login/" y "//\admin/\logout/" tienen el mismo orden, pero difieren en la 2da subcadena
 *      en cuanto a semantica.
 *
 */

//Reglas por omisión
$routes = array();
