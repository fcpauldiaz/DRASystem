<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use UserBundle\Entity\Puesto;
use UserBundle\Form\Type\PuestoType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Puesto controller.
 *
 * @Security("is_granted('ROLE_USER')")
 * @Route("/puesto")
 */
class PuestoController extends Controller
{
    /**
     * Lists all Puesto entities.
     *
     * @Route("/", name="puesto")
     * @Method("GET")
     * @Template("UserBundle:Puesto:indexPuesto.html.twig")
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
        if ($claseActual == "UserBundle\Entity\UsuarioTrabajador") {
            $entities = $usuario->getPuestos();
        } else {
            $em = $this->getDoctrine()->getManager();

            $entities = $em->getRepository('UserBundle:Puesto')->findAll();
        }

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Puesto entity.
     *
     * @Route("/", name="puesto_create")
     * @Method("POST")
     * @Template("UserBundle:Puesto:newPuesto.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Puesto();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        $referer = $request->headers->get('referer');
        $redirect = false;
        if (stripos($referer, 'confirmed') !== false) {
            $redirect = true;
        }

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $data = $form->getData();
            $usuario = $data->getUsuario();
            foreach ($usuario->getRoles() as $role) {
                $usuario->removeRole($role);
            }
            
            $tipoPuesto = $data->getTipoPuesto();
            $permisos = $tipoPuesto->getPermisos();
            foreach ($permisos as $permiso) {
                $usuario->addRole($permiso->getPermiso());
            }
            $em->persist($entity);
            $em->persist($usuario);
            $em->flush();

            if ($redirect === true) {
                return $this->forward('UserBundle:DatosPrestaciones:new');
            }
            if ($usuario === $this->getUser()) {
                $this->addFlash('success', 'Se ha cerrado la sesión para aplicar los nuevos permisos');

                return $this->redirectToRoute('fos_user_security_logout');
            }

            return $this->redirectToRoute('puesto_show', ['id' => $entity->getId()]);
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Puesto entity.
     *
     * @param Puesto $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Puesto $entity)
    {
        $form = $this->createForm(new PuestoType($this->getUser()), $entity, array(
            'action' => $this->generateUrl('puesto_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary btn-block')));

        return $form;
    }

    /**
     * Displays a form to create a new Puesto entity.
     *
     * @Route("/new", name="puesto_new")
     * @Method("GET")
     * @Template("UserBundle:Puesto:newPuesto.html.twig")
     */
    public function newAction()
    {
        $entity = new Puesto();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Puesto entity.
     *
     * @Route("/{id}", name="puesto_show")
     * @Method("GET")
     * @Template("UserBundle:Puesto:showPuesto.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Puesto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Puesto entity.');
        }

        return array(
            'entity' => $entity,
        );
    }
    /**
     * Finds and displays a Puesto entity.
     *
     * @Route("/{id}", name="puesto_show_plain")
     * @Method("GET")
     * @Template("UserBundle:Puesto:showPuestoPlain.html.twig")
     */
    public function showPlainAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Puesto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Puesto entity.');
        }

        return array(
            'entity' => $entity,
        );
    }

    /**
     * Displays a form to edit an existing Puesto entity.
     *
     * @Route("/{id}/edit", name="puesto_edit")
     * @Method("GET")
     * @Template("UserBundle:Puesto:editPuesto.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Puesto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Puesto entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Puesto entity.
     *
     * @param Puesto $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Puesto $entity)
    {
        $form = $this->createForm(new PuestoType($this->getUser()), $entity, array(
            'action' => $this->generateUrl('puesto_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Puesto entity.
     *
     * @Route("/{id}", name="puesto_update")
     * @Method("PUT")
     * @Template("UserBundle:Puesto:editPuesto.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Puesto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Puesto entity.');
        }
        

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $data = $editForm->getData();
            $usuario = $data->getUsuario();
            foreach ($usuario->getRoles() as $role) {
                $usuario->removeRole($role);
            }
            $tipoPuesto = $data->getTipoPuesto();
            $permisos = $tipoPuesto->getPermisos();
            foreach ($permisos as $permiso) {
                $usuario->addRole($permiso->getPermiso());
            }
            $em->persist($entity);
            $em->persist($usuario);
            $em->flush();
            if ($usuario === $this->getUser()) {
                $this->addFlash('success', 'Se ha cerrado la sesión para aplicar los nuevos permisos');

                return $this->redirectToRoute('fos_user_security_logout');
            }

            return $this->redirect($this->generateUrl('puesto_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        );
    }
    /**
     * Deletes a Puesto entity.
     *
     * @Route("/{id}", name="puesto_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_ELIMINAR_PUESTO_Y_TIPO')")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('UserBundle:Puesto')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Puesto entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('puesto'));
    }

    /**
     * Creates a form to delete a Puesto entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('puesto_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
