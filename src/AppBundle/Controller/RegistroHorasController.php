<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\RegistroHoras;
use AppBundle\Form\Type\RegistroHorasType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * RegistroHoras controller.
 * @Security("is_granted('ROLE_USER')")
 * @Route("/registrohoras")
 */
class RegistroHorasController extends Controller
{
    /**
     * Lists all RegistroHoras entities.
     *
     * @Route("/", name="registrohoras")
     * @Method("GET")
     * @Template("AppBundle:RegistroHoras:indexRegistroHoras.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:RegistroHoras')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new RegistroHoras entity.
     *
     * @Route("/", name="registrohoras_create")
     * @Method("POST")
     * @Template("AppBundle:RegistroHoras:newRegistroHoras.html.twig")
     */
    public function createAction(Request $request)
    {
        $usuario = $this->getUser();
        if (!is_object($usuario) || !$usuario instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
 
        $entity = new RegistroHoras();
        $entity->setIngresadoPor($usuario);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('registrohoras_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a RegistroHoras entity.
     *
     * @param RegistroHoras $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(RegistroHoras $entity)
    {
        $form = $this->createForm(new RegistroHorasType($this->getUser()), $entity, array(
            'action' => $this->generateUrl('registrohoras_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new RegistroHoras entity.
     *
     * @Route("/new", name="registrohoras_new")
     * @Method("GET")
     * @Template("AppBundle:RegistroHoras:newRegistroHoras.html.twig")
     */
    public function newAction()
    {
        $entity = new RegistroHoras();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a RegistroHoras entity.
     *
     * @Route("/{id}", name="registrohoras_show")
     * @Method("GET")
     * @Template("AppBundle:RegistroHoras:showRegistroHoras.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:RegistroHoras')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RegistroHoras entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing RegistroHoras entity.
     *
     * @Route("/{id}/edit", name="registrohoras_edit")
     * @Method("GET")
     * @Template("AppBundle:RegistroHoras:editRegistroHoras.html.twig")
     * @Security("is_granted('ROLE_GERENTE')")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:RegistroHoras')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RegistroHoras entity.');
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
     * Creates a form to edit a RegistroHoras entity.
     *
     * @param RegistroHoras $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(RegistroHoras $entity)
    {
        $form = $this->createForm(new RegistroHorasType($this->getUser()), $entity, array(
            'action' => $this->generateUrl('registrohoras_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing RegistroHoras entity.
     *
     * @Route("/{id}", name="registrohoras_update")
     * @Method("PUT")
     * @Template("AppBundle:RegistroHoras:editRegistroHoras.html.twig")
     * @Security("is_granted('ROLE_GERENTE')")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:RegistroHoras')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RegistroHoras entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('registrohoras_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a RegistroHoras entity.
     *
     * @Route("/{id}", name="registrohoras_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_GERENTE')")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:RegistroHoras')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find RegistroHoras entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('registrohoras'));
    }

    /**
     * Creates a form to delete a RegistroHoras entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('registrohoras_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
