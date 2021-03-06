<?php

namespace UserBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use UserBundle\Entity\DatosPrestaciones;
use UserBundle\Form\Type\DatosPrestacionesType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * DatosPrestaciones controller.
 *
 * @Security("is_granted('ROLE_USER')")
 * @Route("/datosprestaciones")
 */
class DatosPrestacionesController extends Controller
{
    /**
     * Lists all DatosPrestaciones entities.
     *
     * @Route("/", name="datosprestaciones")
     * @Method("GET")
     * @Template("UserBundle:DatosPrestaciones:indexDatosPrestaciones.html.twig")
     */
    public function indexAction()
    {
        $usuario = $this->getUser();
        if (!is_object($usuario) || !$usuario instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
        $claseActual = $discriminator->getClass();

        //Se necesita saber cual es el tipo de Usuario Actual para saber a donde dirigirlo.
        if ($claseActual == "UserBundle\Entity\UsuarioTrabajador" && !$this->isGranted('ROLE_ADMIN')) {
            $entities = $usuario->getDatosPrestaciones();
        } elseif ($claseActual == "UserBundle\Entity\UsuarioTrabajador" && $this->isGranted('ROLE_ADMIN')) {
            //Si es admin muestra sus datos y sus asignados.
            $em = $this->getDoctrine()->getManager();
            $sql = 'SELECT d.id
                FROM datos_prestaciones d
                INNER JOIN usuario_relacionado r ON r.usuario_pertenece_id = d.usuario_id
                WHERE r.usuario_id = ?';

            $stmt = $em->getConnection()->prepare($sql);
            $stmt->bindValue(1, $usuario->getId());
            $stmt->execute();
            $res = $stmt->fetchAll();
            $ids = [];
            foreach ($res as $innerRes) {
                $ids[] = $innerRes['id'];
            }

            $entities = $this
                ->getDoctrine()
                ->getRepository('UserBundle:DatosPrestaciones')
                ->findById($ids)
            ;
            $datos = $usuario->getDatosPrestacionesActuales();
            if (isset($datos)) {
                $entities[] = $datos;
            }
        } else {
            $em = $this->getDoctrine()->getManager();

            $entities = $em->getRepository('UserBundle:DatosPrestaciones')->findAll();
        }

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new DatosPrestaciones entity.
     *
     * @Route("/", name="datosprestaciones_create")
     * @Method("POST")
     * @Template("UserBundle:DatosPrestaciones:newDatosPrestaciones.html.twig")
     */
    public function createAction(Request $request)
    {
        $referer = $request->headers->get('referer');
        $redirect = false;
        if (stripos($referer, 'puesto') !== false) {
            $redirect = true;
        }
        $usuario = $this->getUser();
        if (!is_object($usuario) || !$usuario instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $entity = new DatosPrestaciones();

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $usuarioActual = $data->getUsuario();
            $em->persist($entity);
            $em->flush();

            if ($redirect === true && $usuarioActual === $usuario) {
                $this->addFlash('success', 'Se ha cerrado la sesión para aplicar los nuevos permisos');

                return $this->redirect($this->generateUrl('fos_user_security_logout'));
            }

            return $this->redirect($this->generateUrl('datosprestaciones_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }
    /**
     * Creates a form to create a DatosPrestaciones entity.
     *
     * @param DatosPrestaciones $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(DatosPrestaciones $entity)
    {
        $form = $this->createForm(DatosPrestacionesType::class, $entity, array(
            'action' => $this->generateUrl('datosprestaciones_create'),
            'method' => 'POST',
            'user' => $this->getUser()
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary btn-block')));

        return $form;
    }

    /**
     * Displays a form to create a new DatosPrestaciones entity.
     *
     * @Route("/new", name="datosprestaciones_new")
     * @Method("GET")
     * @Template("UserBundle:DatosPrestaciones:newDatosPrestaciones.html.twig")
     */
    public function newAction()
    {
        $entity = new DatosPrestaciones();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a DatosPrestaciones entity.
     *
     * @Route("/{id}", name="datosprestaciones_show")
     * @Method("GET")
     * @Template("UserBundle:DatosPrestaciones:showDatosPrestaciones.html.twig")
     */
    public function showAction($id)
    {
        $usuario = $this->getUser();
        if (!is_object($usuario) || !$usuario instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:DatosPrestaciones')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DatosPrestaciones entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'form' => $deleteForm,

        );
    }

    /**
     * Displays a form to edit an existing DatosPrestaciones entity.
     *
     * @Route("/{id}/edit", name="datosprestaciones_edit")
     * @Method("GET")
     * @Template("UserBundle:DatosPrestaciones:editDatosPrestaciones.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:DatosPrestaciones')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DatosPrestaciones entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a DatosPrestaciones entity.
     *
     * @param DatosPrestaciones $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(DatosPrestaciones $entity)
    {
        $form = $this->createForm(DatosPrestacionesType::class, $entity, array(
            'action' => $this->generateUrl('datosprestaciones_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'user' => $this->getUser()
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing DatosPrestaciones entity.
     *
     * @Route("/{id}", name="datosprestaciones_update")
     * @Method("PUT")
     * @Template("UserBundle:DatosPrestaciones:editDatosPrestaciones.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:DatosPrestaciones')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DatosPrestaciones entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('datosprestaciones_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a DatosPrestaciones entity.
     *
     * @Route("/{id}", name="datosprestaciones_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('UserBundle:DatosPrestaciones')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find DatosPrestaciones entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('datosprestaciones'));
    }

    /**
     * Creates a form to delete a DatosPrestaciones entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('datosprestaciones_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
