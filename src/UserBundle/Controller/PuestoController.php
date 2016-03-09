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

/**
 * Puesto controller.
 *
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
        $usuario = $this->getUser();
        if (!is_object($usuario) || !$usuario instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $entity = new Puesto();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $entity->setUsuario($usuario);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $data = $form->getData();
            foreach ($usuario->getRoles() as $role) {
                $usuario->removeRole($role);
            }
            if ($data->getTipoPuesto() == 'Asistente') {
                $usuario->addRole('ROLE_ASISTENTE');
            } elseif ($data->getTipoPuesto() == 'Supervisor') {
                $usuario->addRole('ROLE_SUPERVISOR');
            } elseif ($data->getTipoPuesto() == 'Gerente') {
                $usuario->addRole('ROLE_GERENTE');
            }
            $em->persist($usuario);
            $em->flush();

            //return new JsonResponse([$key,$value]);

            return $this->forward('UserBundle:DatosPrestaciones:new');
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
        $form = $this->createForm(new PuestoType(), $entity, array(
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

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Puesto entity.
     *
     * @Route("/{id}/edit", name="puesto_edit")
     * @Method("GET")
     * @Template("UserBundle:Puesto:showPuesto.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Puesto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Puesto entity.');
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
     * Creates a form to edit a Puesto entity.
     *
     * @param Puesto $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Puesto $entity)
    {
        $form = $this->createForm(new PuestoType(), $entity, array(
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

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('puesto_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Puesto entity.
     *
     * @Route("/{id}", name="puesto_delete")
     * @Method("DELETE")
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
