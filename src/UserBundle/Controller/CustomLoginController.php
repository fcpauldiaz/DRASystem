<?php

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class CustomLoginController extends Controller
{
    const COOKIE_DELIMITER = ':';

     /**
     * @Route("/api/login/app", name="loginApp")
     */
    public function customLoginAction(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('UserBundle:Usuario')->findOneBy(array('username' => $username));
        if ($user === null) {
            return new JsonResponse(['valid' => false, 'message' => 'Wrong id']);
        }

        if ($user->getPassword() === $password) {
            // Authenticating user
            $token = new UsernamePasswordToken(
                $user,
                $user->getPassword(),
                'main',
                $user->getRoles()
            );

            $response = new JsonResponse();
            $response->headers->setCookie($cookie);
            $response->setData(['valid' => true, 'message' => true]);

            return $response;
        }

        return new JsonResponse(['valid' => false, 'message' => 'Wrong key']);
    }

    /**
     * @Route("/api/login", name="apiLogin")
     */
    public function customLoginAction(Request $request)
    {
        $apiKey = $request->get('SessionId');
        $apiSecret = $request->get('SessionKey');
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('UserBundle:Usuario')->findOneBy(array('apiKey' => $apiKey));
        if ($user === null) {
            return new JsonResponse(['valid' => false, 'message' => 'Wrong id']);
        }

        if ($user->getPassword() === $apiSecret) {
            // Authenticating user
            $token = new UsernamePasswordToken(
                $user,
                $user->getPassword(),
                'main',
                $user->getRoles()
            );

            $options = array(
                'path' => '/',
                'name' => 'REMEMBERME',
                'domain' => null,
                'secure' => false,
                'httponly' => true,
                'lifetime' => 31557600, // 1 year
                'always_remember_me' => true,
                'remember_me_parameter' => '_remember_me',
                'secret' => $this->container->getParameter('secret'),
            );
            $response = new Response();
            $expires = time() + $options['lifetime'];
            //gen
            $value = $this->generateCookieValue(get_class($user), $user->getUsername(), $expires, $user->getPassword(), $options['secret']);

            $cookie = new Cookie(
                $options['name'],
                $value,
                $expires,
                $options['path'],
                $options['domain'],
                $options['secure'],
                $options['httponly']
            );

            $this->get('security.token_storage')->setToken($token);
            //Real automatic login
            $event = new InteractiveLoginEvent($request, $token);
            $this->get('event_dispatcher')->dispatch('security.interactive_login', $event);
            $response = new JsonResponse();
            $response->headers->setCookie($cookie);
            $response->setData(['valid' => true, 'message' => true]);

            return $response;
        }

        return new JsonResponse(['valid' => false, 'message' => 'Wrong key']);
    }

    /**
     * Generates the cookie value.
     *
     * @param string $class
     * @param string $username The username
     * @param int    $expires  The Unix timestamp when the cookie expires
     * @param string $password The encoded password
     *
     * @return string
     */
    protected function generateCookieValue($class, $username, $expires, $password, $secret)
    {
        // $username is encoded because it might contain COOKIE_DELIMITER,
        // we assume other values don't
        return $this->encodeCookie(array(
            $class,
            base64_encode($username),
            $expires,
            $this->generateCookieHash($class, $username, $expires, $password, $secret),
        ));
    }

    /**
     * Generates a hash for the cookie to ensure it is not being tempered with.
     *
     * @param string $class
     * @param string $username The username
     * @param int    $expires  The Unix timestamp when the cookie expires
     * @param string $password The encoded password
     *
     * @return string
     */
    protected function generateCookieHash($class, $username, $expires, $password, $secret)
    {
        return hash_hmac('sha256', $class.$username.$expires.$password, $secret);
    }

    /**
     * Encodes the cookie parts.
     *
     * @param array $cookieParts
     *
     * @return string
     *
     * @throws \InvalidArgumentException When $cookieParts contain the cookie delimiter. Extending class should either remove or escape it
     */
    protected function encodeCookie(array $cookieParts)
    {
        foreach ($cookieParts as $cookiePart) {
            if (false !== strpos($cookiePart, self::COOKIE_DELIMITER)) {
                throw new \InvalidArgumentException(sprintf('$cookieParts should not contain the cookie delimiter "%s"', self::COOKIE_DELIMITER));
            }
        }

        return base64_encode(implode(self::COOKIE_DELIMITER, $cookieParts));
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
