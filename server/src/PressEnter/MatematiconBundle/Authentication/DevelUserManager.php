<?php

namespace PressEnter\MatematiconBundle\Authentication;

use FOS\UserBundle\Doctrine\UserManager as DoctrineUserManager;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Util\CanonicalizerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class DevelUserManager extends DoctrineUserManager
{
    protected $api;
    /**
     * Constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     * @param CanonicalizerInterface  $usernameCanonicalizer
     * @param CanonicalizerInterface  $emailCanonicalizer
     * @param ObjectManager           $om
     * @param string                  $class
     */
    public function __construct(EncoderFactoryInterface $encoderFactory, CanonicalizerInterface $usernameCanonicalizer, CanonicalizerInterface $emailCanonicalizer, ObjectManager $om, $class, $api)
    {
        $this->api = $api;;
        parent::__construct($encoderFactory, $usernameCanonicalizer, $emailCanonicalizer, $om, $class);
    }

    public function findUserByUsername($username)
    {
        // Obtener datos via webservice
        $data = $this->api->RestActions()->obtenerUsuarioPublico($username);
        
        if($data->error)
        {
            return null;
        }
        
        $dbUser = parent::findUserByUsername($username);
        if($dbUser === null)
        {
            $ref = new \ReflectionClass($this->getClass());
            $dbUser = $ref->newInstance();
            $dbUser->setUsername($username);
            $dbUser->setEmail($username);
            $dbUser->setEnabled(true);
            $dbUser->addRole('ROLE_USER');
            $dbUser->setPassword('xxxxxx');
        }

        $dbUser->setNombre($data->data['usuario']['usr_nombre']);
        $dbUser->setApellido($data->data['usuario']['usr_apellido']);
        $tmp = explode('/', $data->data['usuario']['usr_fecha_nacimiento']);
        if(count($tmp) == 3)
        {
            $fecha = new \DateTime($tmp[2].'-'.$tmp[1].'-'.$tmp[0]);
        }
        else
        {
            $fecha = null;
        }
        $dbUser->setFechaNacimiento($fecha);
        $dbUser->setPais($data->data['usuario']['usr_pais']['desc']);
        $dbUser->setProvincia(str_replace('Ciudad Autónoma de Buenos Aires', 'CABA', $data->data['usuario']['usr_provincia']['desc']));

        $this->updateUser($dbUser);
        
        return parent::findUserByUsername($username);
    }
}
