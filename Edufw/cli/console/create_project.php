<?php

namespace Edufw\cli\console;

require_once '../bootstrap.php';

use Edufw\core\EWebApp;

class ProjectBuilder
{
    private $projectName;
    private $projectModule;
    private $rootProject;

    private $tpl_webroot_index = <<<END
<?php
\$fwconfig = array();
\$fwconfig['APP_WEBROOT'] = __DIR__;
require_once __DIR__.'/../../bootstrap.php';
END;
    
    private $tpl_config_local = <<<END
<?php
/**
 * Configuracion general del proyecto
 * 
 * En caso de querer incluir configuraciones propias del modulo, 
 * modificar el archivo config.php en PROYECT_NAME/MODULE_NAME/
 */

// MODULO-CONTROLLER-METHOD DEFAULT [OPCIONAL]
\$config['PROYECT_DEFAULT'] = array(
    'module' => 'Ejemplo',
    'controller' => 'Inicio',
    'method' => 'index'
);
END;
    
    private $tpl_config_module = <<<END
<?php
/**
 * Configuracion especifica del modulo
 * 
 * En caso de querer incluir configuraciones generales del 
 * proyecto, modificar el archivo config.php en PROYECT_NAME/
 */

// CONTROLLER-METHOD DEFAULT [OPCIONAL]
\$config['MODULE_DEFAULT'] = array(
    'controller' => 'Inicio',
    'method' => 'index'
);
END;


    public function __construct() {
    }

    private function create_structure() 
    {
        mkdir($this->rootProject);
        mkdir($this->rootProject.DIRECTORY_SEPARATOR.'webroot');
        mkdir($this->rootProject.DIRECTORY_SEPARATOR.'webroot'.DIRECTORY_SEPARATOR.'js');
        mkdir($this->rootProject.DIRECTORY_SEPARATOR.'webroot'.DIRECTORY_SEPARATOR.'css');
        $rootModule = $this->rootProject.DIRECTORY_SEPARATOR.$this->projectModule;
        mkdir($rootModule);
        mkdir($rootModule.DIRECTORY_SEPARATOR.'classes');
        mkdir($rootModule.DIRECTORY_SEPARATOR.'controllers');
        mkdir($rootModule.DIRECTORY_SEPARATOR.'views');
        mkdir($rootModule.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'layouts');
    }

    private function create_templates() {
        $f = $this->rootProject.DIRECTORY_SEPARATOR.'webroot'.DIRECTORY_SEPARATOR.'index.php';
        file_put_contents($f, $this->tpl_webroot_index);
        $f = $this->rootProject.DIRECTORY_SEPARATOR.'config.php';
        file_put_contents($f, $this->tpl_config_local);
        $f = $this->rootProject.DIRECTORY_SEPARATOR.$this->projectModule.DIRECTORY_SEPARATOR.'config.php';
        file_put_contents($f, $this->tpl_config_module);
    }

    public function create_project() 
    {
        $hd = fopen('php://stdin', 'r');
        echo "Ingrese nombre del proyecto: \n";
        $this->projectName = trim(fgets($hd));
        echo "Ingrese nombre del primer modulo: \n";
        $this->projectModule = trim(fgets($hd));
        $this->rootProject = EWebApp::config()->APP_SRC.$this->projectName.'/';
        echo "Creando estructura de proyecto bajo [{$this->rootProject}] ...\n";
        $this->create_structure();
        echo "Creando configuraciones de proyecto ...\n";
        $this->create_templates();
        //@todo gseip
        //echo "Creando controlador inicial ...\n";


        fclose($hd);

    }

}

$pb = new ProjectBuilder();
$pb->create_project();

