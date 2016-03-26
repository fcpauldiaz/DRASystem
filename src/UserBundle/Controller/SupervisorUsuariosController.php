<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use UserBundle\Entity\TipoPuesto;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * CombinarPuesto controller.
 *
 * @Security("is_granted('ROLE_USER')") 
 */
class SupervisorUsuariosController extends Controller
{
    /**
     * Este método tiene como intención mostrarle a un usuario los demás usuarios que están
     * bajo su control. Por ejemplo, si su puesto es tipo gerente puede ver a los usuarios tipo
     * asistente y encargado.
     * 
     *
     * @return Usuario Array de usuarios
     *
     * @Route("/mostrar/usuarios", name="mostrar_usuarios")
     */
    public function usuariosPermisosAction()
    {
        $em = $this->getDoctrine()->getManager();

        $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
        $claseActual = $discriminator->getClass();

          //Se necesita saber cual es el tipo de Usuario Actual para saber a donde dirigirlo.
          if ($claseActual == "UserBundle\Entity\UsuarioSocio") {
              $usuarios = $em->getRepository('UserBundle:UsuarioTrabajador')->findAll();

              return $this->render('UserBundle:Puesto:showUsuarioPermisos.html.twig',
                [
                    'usuarios' => $usuarios,
                ]
            );
          }

        $usuarioActual = $this->getUser();

        $arrayPuestos = $usuarioActual->getPuestos();
        //se utiliza solamente el último puesto porque es se toma como el más reciente
        $puestoActual = $arrayPuestos->last();
        //array
        $tipoPuestosJerarquia = $puestoActual->getTipoPuesto()->getPuestos();

        $returnArray = [];
        foreach ($tipoPuestosJerarquia as $tipoPuesto) {
            $puesto = $em->getRepository('UserBundle:Puesto')->findOneBy(['tipoPuesto' => $tipoPuesto]);

            $usuarios = $em->getRepository('UserBundle:UsuarioTrabajador')->findAll();
            foreach ($usuarios as $usuario) {
                $puestosUsuario = $usuario->getPuestos();
                $lastPuesto = $puestosUsuario->last();
                if ($lastPuesto == $puesto && $lastPuesto != null) {
                    $returnArray[] = $usuario;
                }
            }
        }

        return $this->render('UserBundle:Puesto:showUsuarioPermisos.html.twig',
            [
                'usuarios' => $returnArray,
            ]
        );
    }
}
