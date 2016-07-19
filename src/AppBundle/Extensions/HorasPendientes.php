<?php
namespace AppBundle\Extensions;

use Symfony\Component\HttpFoundation\Session;
use Doctrine\ORM\EntityManager;
use UserBundle\Entity\Usuario;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use AppBundle\Extensions\Notification;

class HorasPendientes
{
    protected $session;
    protected $em;
     /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    public function __construct(TokenStorage $tokenStorage, EntityManager $em )
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    public function getHoras()
    {
        $usuario = $this->tokenStorage->getToken()->getUser();
        $usuariosRelacionados = $usuario->getMisUsuariosRelacionados();
        $cantidad = 0;
        $arrayUsuarios = [];
        foreach($usuariosRelacionados as $usuario) {
            $horasPorUsuario = 0;
            $registro = $this->em->getRepository('AppBundle:RegistroHoras')->findBy(
                [
                'ingresadoPor' => $usuario, 
                'aprobado' => false
                ]
            );
            if (!empty($registro)) {
                foreach($registro as $horas) {
                    $horasPorUsuario += $horas->getHorasInvertidas();

                }
                $arrayUsuarios[] = [$usuario, $horasPorUsuario];
                $cantidad += 1;
            }
        }
        $notificacion = new Notification($cantidad, $arrayUsuarios);
        return $notificacion;
    }
}