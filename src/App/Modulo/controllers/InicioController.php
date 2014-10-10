<?php

namespace App\Modulo\controllers;

use Edufw\core\ERouter;
use Edufw\core\EController;


class InicioController extends EController
{

  public function inicio()
  {
    $this->render('inicio/inicio', 'principal');
  }

}
