<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Entity\UsuarioTrabajador;
use PUGX\MultiUserBundle\Controller\RegistrationManager;

class RegisterTrabajadorController extends Controller
{
    public function registerAction()
    {
        return $this->container
                    ->get('pugx_multi_user.registration_manager')
                    ->register(UsuarioTrabajador::class);
    }
}
