<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\Type\AsignacionType;
use UserBundle\Entity\UsuarioRelacionado;

/**
 * Asignacion Usuarios controller.
 *
 * @Security("is_granted('ROLE_USER')")
 */
class AsignacionUsuariosController extends Controller
{
  /**
   * @Route("/asignar/usuarios", name="asignar_usuarios")
   * @param  Request $request 
   */
  public function asginarUsuariosAction(Request $request)
  {
    $form = $this->createForm(new AsignacionType($this->getUser()));

    $form->handleRequest($request);
    if ($form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $data = $form->getData();
      $usuarios = $data['usuarios'];
      $usuarioAsignar = $data['usuarioAsignar'];
      foreach($usuarios as $usuario) {
        $relacion = new UsuarioRelacionado($usuarioAsignar, $usuario);
        $em->persist($relacion);
      }
      $em->flush();
      $this->addFlash('success', 'Se ha guardado la asginación exitosamente');

      $form = $this->createForm(new AsignacionType($this->getUser()));

      return $this->redirectToRoute('asignar_usuarios');
    }

    return $this->render('AppBundle:Asignacion:newAsignacion.html.twig', [
      'form' => $form->createView(),
      'usuariosAsignados' => $this->getUser()->getUsuarioRelacionado(),
    ]);

  }
  /**
   * @Route("remover/usuario/{usuarioRemover}/{usuarioPertenece}", name="remover_usuario")
   * @param  Request $request 
   */
  public function removerUsuarioAction ($usuarioRemover,  $usuarioPertenece)
  {
    $em = $this->getDoctrine()->getManager();
    $relacion = $em->getRepository('UserBundle:UsuarioRelacionado')
    ->findOneBy(['usr' => $usuarioRemover, 'usuarioPertenece' => $usuarioPertenece]);
    $em->remove($relacion);
    $em->flush();
    return $this->redirectToRoute('asignar_usuarios');
  }


}