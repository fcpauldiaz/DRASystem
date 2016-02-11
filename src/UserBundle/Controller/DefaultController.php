<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("")
 */
class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('UserBundle:Default:index.html.twig');
    }
}
