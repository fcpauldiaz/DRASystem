<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfileTrabajadorController extends Controller
{
    public function editAction()
    {
        return $this->container
                    ->get('pugx_multi_user.profile_manager')
                    ->edit('UserBundle\Entity\UsuarioTrabajador');
    }
}
