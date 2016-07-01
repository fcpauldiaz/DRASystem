<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use UserBundle\Entity\Departamento;
use UserBundle\Form\Type\DepartamentoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Departamento controller.
 *
 * @Security("is_granted('ROLE_USER')") 
 * @Route("/departamento")
 */
class DepartamentoController extends Controller
{
    /**
     * Lists all Departamento entities.
     *
     * @Route("/", name="departamento")
     * @Method("GET")
     * @Security("is_granted('ROLE_VER_DEPARTAMENTO')") 
     * @Template("UserBundle:Departamento:indexDepartamento.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('UserBundle:Departamento')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Departamento entity.
     *
     * @Route("/", name="departamento_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREAR_DEPARTAMENTO')") 
     * @Template("UserBundle:Departamento:newDepartamento.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Departamento();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            if ($form->get('submitAndSave')->isClicked()) {
                return $this->redirectToRoute('departamento_new');
            }

            return $this->redirect($this->generateUrl('departamento_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Departamento entity.
     *
     * @param Departamento $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Departamento $entity)
    {
        $form = $this->createForm(new DepartamentoType(), $entity, array(
            'action' => $this->generateUrl('departamento_create'),
            'method' => 'POST',
        ));

        $form->add('submitAndSave', 'submit', [
                    'label' => 'Guardar e ingresar otro',
                    'attr' => [
                        'class' => 'btn btn-primary btn-block',
                    ],
            ]);
        $form->add('submit', 'submit', [
                    'label' => 'Guardar y ver detalle',
                    'attr' => [
                        'class' => 'btn btn-primary btn-block',
                    ],
            ]);

        return $form;
    }

    /**
     * Displays a form to create a new Departamento entity.
     *
     * @Route("/new/", name="departamento_new")
     * @Method("GET")
     * @Security("is_granted('ROLE_CREAR_DEPARTAMENTO')") 
     * @Template("UserBundle:Departamento:newDepartamento.html.twig")
     */
    public function newAction()
    {
        $entity = new Departamento();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Departamento entity.
     *
     * @Route("/{id}", name="departamento_show")
     * @Method("GET")
     * @Security("is_granted('ROLE_VER_DEPARTAMENTO')") 
     * @Template("UserBundle:Departamento:showDepartamento.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Departamento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Departamento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Departamento entity.
     *
     * @Route("/{id}/edit", name="departamento_edit")
     * @Method("GET")
     * @Security("is_granted('ROLE_EDITAR_DEPARTAMENTO')") 
     * @Template("UserBundle:Departamento:editDepartamento.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Departamento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Departamento entity.');
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
     * Creates a form to edit a Departamento entity.
     *
     * @param Departamento $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Departamento $entity)
    {
        $form = $this->createForm(new DepartamentoType(), $entity, array(
            'action' => $this->generateUrl('departamento_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Departamento entity.
     *
     * @Route("/{id}", name="departamento_update")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDITAR_DEPARTAMENTO')") 
     * @Template("UserBundle:Departamento:editDepartamento.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Departamento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Departamento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('departamento_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Departamento entity.
     *
     * @Route("/{id}", name="departamento_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_ELIMINAR_DEPARTAMENTO')") 
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('UserBundle:Departamento')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Departamento entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('departamento'));
    }

    /**
     * Creates a form to delete a Departamento entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('departamento_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
