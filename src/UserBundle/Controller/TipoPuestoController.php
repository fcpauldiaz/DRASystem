<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use UserBundle\Entity\TipoPuesto;
use UserBundle\Form\Type\TipoPuestoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * TipoPuesto controller.
 *
 * @Security("is_granted('ROLE_USER')") 
 * @Route("/tipopuesto")
 */
class TipoPuestoController extends Controller
{
    /**
     * Lists all TipoPuesto entities.
     *
     * @Route("/", name="tipopuesto")
     * @Security("is_granted('ROLE_VER_PUESTO')") 
     * @Template("UserBundle:TipoPuesto:indexTipoPuesto.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('UserBundle:TipoPuesto')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new TipoPuesto entity.
     *
     * @Route("/create", name="tipopuesto_create")
     * @Security("is_granted('ROLE_CREAR_TIPO_PUESTO')") 
     * @Template("UserBundle:TipoPuesto:newTipoPuesto.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new TipoPuesto();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            if ($form->get('submitAndSave')->isClicked()) {
                return $this->redirectToRoute('tipopuesto_new_post');
            }

            return $this->redirect($this->generateUrl('tipopuesto_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a TipoPuesto entity.
     *
     * @param TipoPuesto $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TipoPuesto $entity)
    {
        $form = $this->createForm(new TipoPuestoType(), $entity, array(
            'action' => $this->generateUrl('tipopuesto_create'),
            'method' => 'POST',
        ));
        $form->add('submitAndSave', 'submit', [
                'label' => 'Guardar y crear otro',
                'attr' => [
                    'class' => 'btn btn-block',
                ],
            ]);
        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => ['class' => 'btn btn-block']));

        return $form;
    }

    /**
     * Displays a form to create a new TipoPuesto entity.
     *
     * @Route("/new", name="tipopuesto_new_post")
     * @Security("is_granted('ROLE_CREAR_TIPO_PUESTO')")
     * @Template("UserBundle:TipoPuesto:newTipoPuesto.html.twig")
     */
    public function newAction()
    {
        $entity = new TipoPuesto();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a TipoPuesto entity.
     *
     * @Route("/{id}", name="tipopuesto_show")
     * @Security("is_granted('ROLE_VER_PUESTO')") 
     * @Template("UserBundle:TipoPuesto:showTipoPuesto.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:TipoPuesto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoPuesto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing TipoPuesto entity.
     *
     * @Route("/{id}/edit", name="tipopuesto_edit")
     * @Security("is_granted('ROLE_EDITAR_PUESTO')") 
     *@Template("UserBundle:TipoPuesto:editTipoPuesto.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:TipoPuesto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoPuesto entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        $puestos = $em->getRepository('UserBundle:Puesto')->findBy(['tipoPuesto' => $entity]);

        //se coloca en un string los puestos
        //para mostrarlos en un modal en la UI.
        $puestoString = '<br>';
        foreach ($puestos as $puesto) {
            $puestoString .= $puesto->__toString().'<br> ';
        }

        return array(
            'puestos' => $puestoString,
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a TipoPuesto entity.
     *
     * @param TipoPuesto $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(TipoPuesto $entity)
    {
        $form = $this->createForm(new TipoPuestoType(), $entity, array(
            'action' => $this->generateUrl('tipopuesto_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing TipoPuesto entity.
     *
     * @Route("/{id}/update", name="tipopuesto_update")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDITAR_PUESTO')") 
     * @Template("UserBundle:TipoPuesto:editTipoPuesto.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:TipoPuesto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoPuesto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tipopuesto_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a TipoPuesto entity.
     *
     * @Route("/{id}/delete", name="tipopuesto_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_ELIMINAR_PUESTO')") 
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('UserBundle:TipoPuesto')->find($id);
            $permisos = $entity->getPermisos();
            foreach ($permisos as $permiso) {
                $entity->removePermiso($permiso);
            }
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TipoPuesto entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tipopuesto'));
    }

    /**
     * Creates a form to delete a TipoPuesto entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tipopuesto_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => ['class' => 'btn btn-danger']))
            ->getForm()
        ;
    }
}
