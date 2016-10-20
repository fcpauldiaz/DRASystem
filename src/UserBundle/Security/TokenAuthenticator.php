<?php

namespace UserBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Doctrine\ORM\EntityManager;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $em;
    private $container;
    private $user;

    public function __construct(EntityManager $em, Container $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    /**
     * Called on every request. Return whatever credentials you want,
     * or null to stop authentication.
     */
    public function getCredentials(Request $request)
    {
        if ($auth = $request->headers->get('Authorization')) {
            
            $auth = substr($auth, strrpos($auth, 'BASIC')+6, strlen($auth));
            if ($auth === $this->container->getParameter('temporize_auth_key')) {
                return [ 'auth' => $auth ];
            }
        }

        if (!$token = $request->headers->get('X-AUTH-TOKEN')) {
            // no token? Return null and no other methods will be called
            return;
        }
        if (!$password = $request->headers->get('X-AUTH-TOKEN-PASS')) {
            // no token? Return null and no other methods will be called
            return;
        }

        // What you return here will be passed to getUser() as $credentials
        return array(
            'token' => $token,
            'password' => $password,
        );
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $validator = false;
        if (array_key_exists('token', $credentials)) {
            $username = $credentials['token'];
            $validator = true;
        }
        if ($validator === false && array_key_exists('auth', $credentials) ) {
            $username = $this->container->getParameter('api_user');

        }
        // if null, authentication will fail
        // if a User object, checkCredentials() is called
        return $this->em->getRepository('UserBundle:Usuario')
            ->findOneBy(array('username' => $username));
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $factory = $this->container->get('security.encoder_factory');
        $validator = false;
        if (array_key_exists('password', $credentials)) {
              $password = $credentials['password'];
              $validator = true;
        }
      
        if ($validator === false && array_key_exists('auth', $credentials)) {
            $password = $this->container->getParameter('api_password');
        }
        $encoder = $factory->getEncoder($user);
        $encodedPassword = $encoder->encodePassword($password, $user->getSalt());

        // check credentials - e.g. make sure the password is valid
        // no credential check is needed in this case
        if ($user->getPassword() === $encodedPassword) {
            $this->user = $user;
            return true;
        }

        // return true to cause authentication success
        throw new CustomUserMessageAuthenticationException('Contraseña incorrecta');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {

        // on success, let the request continue
        return ;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = array(
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        );

        return new JsonResponse($data, 403);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = array(
            // you might translate this message
            'message' => 'Authentication Required',
        );

        return new JsonResponse($data, 401);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
