<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\AsignacionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\UsuarioRelacionado;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Asignacion Usuarios controller.
 *
 * @Security("is_granted('ROLE_USER')")
 */
class AsignacionUsuariosController extends Controller
{
    /**
   * @Route("/asignar/usuarios", name="asignar_usuarios")
   *
   * @param  Request $request
   */
    public function asginarUsuariosAction(Request $request)
    {
        $form = $this->createForm(AsignacionType::class, null, array('user' => $this->getUser()));

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $usuarios = $data['usuarios'];
            $usuarioAsignar = $data['usuarioAsignar'];
            //revisar que no esté ya asignado
            $revisionUsuarios = $usuarioAsignar->getMisUsuariosRelacionados()->toArray();

            foreach ($usuarios->toArray() as $usuario) {
                foreach ($revisionUsuarios as $revision) {
                    if ($revision->getUsuarioPertenece() === $usuario) {
                        $this->addFlash('error', 'El usuario ya estaba asignado');

                        return $this->redirectToRoute('asignar_usuarios');
                    }
                }
            }
            foreach ($usuarios as $usuario) {
                $relacion = new UsuarioRelacionado($usuarioAsignar, $usuario);
                $em->persist($relacion);
            }
            $em->flush();
            $this->addFlash('success', 'Se ha guardado la asginación exitosamente');

            $form = $this->createForm(AsignacionType::class, null, array('user' => $this->getUser()));

            return $this->redirectToRoute('asignar_usuarios');
        }
        return $this->render('AppBundle:Asignacion:newAsignacion.html.twig', [
      'form' => $form->createView(),
      'usuariosAsignados' => $this->getUser()->getMisusuariosRelacionados(),
    ]);
    }
    /**
     * @Route("remover/usuario/{usuarioRemover}/{usuarioPertenece}", name="remover_usuario")
     *
     * @param  Request $request
     */
    public function removerUsuarioAction($usuarioRemover, $usuarioPertenece)
    {
        $em = $this->getDoctrine()->getManager();
        $relacion = $em->getRepository('UserBundle:UsuarioRelacionado')
                  ->findOneBy(['usr' => $usuarioRemover, 'usuarioPertenece' => $usuarioPertenece]);
        $em->remove($relacion);
        $em->flush();

        return $this->redirectToRoute('asignar_usuarios');
    }
}
