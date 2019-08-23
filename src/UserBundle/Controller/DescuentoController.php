<?php

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\Descuento;
use UserBundle\Form\Type\DescuentoType;

/**
 * Descuento controller.
 *
 * @Route("/descuento")
 */
class DescuentoController extends Controller
{
    /**
     * Lists all Descuento entities.
     *
     * @Route("/", name="descuento")
     * @Method("GET")
     * @Template("UserBundle:Descuento:indexDescuento.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('UserBundle:Descuento')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Descuento entity.
     *
     * @Route("/", name="descuento_create")
     * @Method("POST")
     * @Template("UserBundle:Descuento:newDescuento.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Descuento();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('descuento_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Descuento entity.
     *
     * @param Descuento $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Descuento $entity)
    {
        $form = $this->createForm(DescuentoType::class, $entity, array(
            'action' => $this->generateUrl('descuento_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Descuento entity.
     *
     * @Route("/new", name="descuento_new")
     * @Method("GET")
     * @Template("UserBundle:Descuento:newDescuento.html.twig")
     */
    public function newAction()
    {
        $entity = new Descuento();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Descuento entity.
     *
     * @Route("/{id}", name="descuento_show")
     * @Method("GET")
     * @Template("UserBundle:Descuento:showDescuento.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Descuento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Descuento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Descuento entity.
     *
     * @Route("/{id}/edit", name="descuento_edit")
     * @Method("GET")
     *@Template("UserBundle:Descuento:editDescuento.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Descuento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Descuento entity.');
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
     * Creates a form to edit a Descuento entity.
     *
     * @param Descuento $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Descuento $entity)
    {
        $form = $this->createForm(DescuentoType::class, $entity, array(
            'action' => $this->generateUrl('descuento_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Descuento entity.
     *
     * @Route("/{id}", name="descuento_update")
     * @Method("PUT")
     * @Template("UserBundle:Descuento:editDescuento.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Descuento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Descuento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('descuento_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Descuento entity.
     *
     * @Route("/{id}", name="descuento_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('UserBundle:Descuento')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Descuento entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('descuento'));
    }

    /**
     * Creates a form to delete a Descuento entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('descuento_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit',  SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
