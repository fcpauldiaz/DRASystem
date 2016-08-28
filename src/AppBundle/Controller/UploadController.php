<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Upload controller.
 *
 * @Security("is_granted('ROLE_USER')")
 */
class UploadController extends Controller
{
    //subir archivo de clientes
}
