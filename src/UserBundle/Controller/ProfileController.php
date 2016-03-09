<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfileController extends Controller
{
    public function editAction()
    {
        $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
        $claseActual = $discriminator->getClass();

          //Se necesita saber cual es el tipo de Usuario Actual para saber a donde dirigirlo.
          if ($claseActual == "UserBundle\Entity\UsuarioTrabajador") {
              return $this->container
                    ->get('pugx_multi_user.profile_manager')
                    ->edit('UserBundle\Entity\UsuarioTrabajador');
          }

        return  $this->container
                    ->get('pugx_multi_user.profile_manager')
                    ->edit('UserBundle\Entity\UsuarioSocio');
    }

    public function showAction()
    {
        $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
        $claseActual = $discriminator->getClass();

          //Se necesita saber cual es el tipo de Usuario Actual para saber a donde dirigirlo.
          if ($claseActual == "UserBundle\Entity\UsuarioTrabajador") {
              return $this->container
                    ->get('pugx_multi_user.profile_manager')
                    ->show('UserBundle\Entity\UsuarioTrabajador');
          }

        return $this->container
                    ->get('pugx_multi_user.profile_manager')
                    ->show('UserBundle\Entity\UsuarioSocio');
    }
}
