<?php

namespace UserBundle\Listener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Routing\Router;

class AccessDeniedListener
{
    protected $_request;
    public function __construct(Session $session, Router $router, RequestStack $requestStack)
    {
        $this->_session = $session;
        $this->_router = $router;
        $this->_request = $requestStack;
    }
    public function onAccessDeniedException(GetResponseForExceptionEvent $event)
    {
        if ($event->getException()->getMessage() == 'Access Denied') {
            $this->_session->setFlash('error', 'Access Denied. You do not have permission to access this page.');

            if ($this->_request->headers->get('referer')) {
                $route = $this->_request->headers->get('referer');
            } else {
                $route = $this->_router->generate('homepage');
            }

            $event->setResponse(new RedirectResponse($route));

            return $this->redirect(
                $this->generateUrl('homepage')
            );
        }
    }
}
