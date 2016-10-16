<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Response;

class CustomLoginController extends Controller
{
    /**
     * @Route("/api/login", name="apiLogin")
     * @Method({"POST", "GET"})
     */
    public function customLoginAction(Request $request)
    {
        $apiKey = $request->get('SessionId');
        $apiSecret = $request->get('SessionKey');
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('UserBundle:Usuario')->findOneBy(array('apiKey' => $apiKey));
        if ($user === null) {
            return new JsonResponse(['valid'=> false, 'message' => 'Wrong id']);
        }

        if ($user->getPassword() === $apiSecret) {
            // Authenticating user
            $token = new UsernamePasswordToken(
                $user, 
                $user->getPassword(), 
                'main', 
                $user->getRoles()
            );
            $this->get('security.context')->setToken($token);
          

            return new JsonResponse(['valid'=> true]);
        }
        return new JsonResponse(['valid'=> false, 'message' => 'Wrong key']);
    }

    /**
     * @Route("/api/logout", name="apiLogout")
     * @Method({"POST", "GET"})
     */
    public function customLogoutAction(Request $request)
    {
       return $this->redirectToRoute('fos_user_security_login', ['logout' => true]);
    }
}
