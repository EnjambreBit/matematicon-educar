<?php

namespace PressEnter\MatematiconBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;

/**
 * @var Request $request
 * @return array
 * @ Rest\View()
 */
class ApiController extends Controller
{
    public function testAction($name)
    {
        return array('name' => $name);
    }
} //[GET] /test
