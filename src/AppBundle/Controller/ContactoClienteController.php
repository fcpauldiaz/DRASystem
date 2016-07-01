<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\ContactoCliente;
use AppBundle\Form\Type\ContactoClienteType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * ContactoCliente controller.
 *
 * @Security("is_granted('ROLE_USER')")
 * @Route("/contactocliente")
 */
class ContactoClienteController extends Controller
{
    /**
     * Lists all ContactoCliente entities.
     *
     * @Route("/", name="contactocliente")
     * @Method("GET")
     * @Template("AppBundle:ContactoCliente:indexContactoCliente.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:ContactoCliente')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new ContactoCliente entity.
     *
     * @Route("/", name="contactocliente_create")
     * @Method("POST")
     * @Template("AppBundle:ContactoCliente:newContactoCliente.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new ContactoCliente();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $entities = $em->getRepository('AppBundle:ContactoCliente')->findAll();
            foreach ($entities as $entity) {
                $response1[] = [
                    'key' => $entity->__toString(),
                    // other fields
                ];
                $response2[] = [
                    'value' => $entity->getId(),
                    // other fields
                ];
            }

            return new JsonResponse(([$response1, $response2]));
        } else {

            //llega aquí cuando no cumple la validación del formulario
            return new JsonResponse(['error' => $form->getErrorsAsString()], 400);
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a ContactoCliente entity.
     *
     * @param ContactoCliente $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ContactoCliente $entity)
    {
        $form = $this->createForm(new ContactoClienteType(), $entity, array(
            'action' => $this->generateUrl('contactocliente_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ContactoCliente entity.
     *
     * @Route("/new", name="contactocliente_new")
     * @Method("GET")
     * @Template("AppBundle:ContactoCliente:newContactoCliente.html.twig")
     */
    public function newAction()
    {
        $entity = new ContactoCliente();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a ContactoCliente entity.
     *
     * @Route("/{id}", name="contactocliente_show")
     * @Method("GET")
     * @Template("AppBundle:ContactoCliente:showContactoCliente.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:ContactoCliente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ContactoCliente entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ContactoCliente entity.
     *
     * @Route("/{id}/edit", name="contactocliente_edit")
     * @Method("GET")
     * @Template("AppBundle:ContactoCliente:editContactoCliente.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:ContactoCliente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ContactoCliente entity.');
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
     * Creates a form to edit a ContactoCliente entity.
     *
     * @param ContactoCliente $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(ContactoCliente $entity)
    {
        $form = $this->createForm(new ContactoClienteType(), $entity, array(
            'action' => $this->generateUrl('contactocliente_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ContactoCliente entity.
     *
     * @Route("/{id}", name="contactocliente_update")
     * @Method("PUT")
     * @Template("AppBundle:ContactoCliente:editContactoCliente.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:ContactoCliente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ContactoCliente entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('contactocliente_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a ContactoCliente entity.
     *
     * @Route("/{id}", name="contactocliente_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:ContactoCliente')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ContactoCliente entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('contactocliente'));
    }

    /**
     * Creates a form to delete a ContactoCliente entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contactocliente_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
