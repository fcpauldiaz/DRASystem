<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\ProyectoPresupuesto;
use AppBundle\Form\Type\ProyectoPresupuestoType;
use AppBundle\Form\Type\ProyectoPresupuestoEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * ProyectoPresupuesto controller.
 *
 * @Security("is_granted('ROLE_USER')")
 * @Route("/proyectopresupuesto")
 */
class ProyectoPresupuestoController extends Controller
{
    /**
     * Lists all ProyectoPresupuesto entities.
     *
     * @Route("/", name="proyectopresupuesto")
     * @Method("GET")
     * @Security("is_granted('ROLE_VER_PRESUPUESTOS')")
     * @Template("AppBundle:ProyectoPresupuesto:indexProyectoPresupuesto.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:ProyectoPresupuesto')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new ProyectoPresupuesto entity.
     *
     * @Route("/", name="proyectopresupuesto_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREAR_PRESUPUESTOS')")
     * @Template("AppBundle:ProyectoPresupuesto:newProyectoPresupuesto.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new ProyectoPresupuesto();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            if ($form->get('submitAndSave')->isClicked()) {
                return $this->redirectToRoute('proyectopresupuesto_new');
            }

            return $this->redirect($this->generateUrl('proyectopresupuesto_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a ProyectoPresupuesto entity.
     *
     * @param ProyectoPresupuesto $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ProyectoPresupuesto $entity)
    {
        $form = $this->createForm(new ProyectoPresupuestoType($this->getUser()), $entity, array(
            'action' => $this->generateUrl('proyectopresupuesto_create'),
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
     * Displays a form to create a new ProyectoPresupuesto entity.
     *
     * @Route("/new", name="proyectopresupuesto_new")
     * @Method("GET")
     * @Security("is_granted('ROLE_CREAR_PRESUPUESTOS')")
     * @Template("AppBundle:ProyectoPresupuesto:newProyectoPresupuesto.html.twig")
     */
    public function newAction()
    {
        $entity = new ProyectoPresupuesto();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a ProyectoPresupuesto entity.
     *
     * @Route("/{id}", name="proyectopresupuesto_show")
     * @Method("GET")
     * @Template("AppBundle:ProyectoPresupuesto:showProyectoPresupuesto.html.twig")
     * @Security("is_granted('ROLE_VER_PRESUPUESTOS')")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:ProyectoPresupuesto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProyectoPresupuesto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ProyectoPresupuesto entity.
     *
     * @Route("/{id}/edit", name="proyectopresupuesto_edit")
     * @Method("GET")
     * @Template("AppBundle:ProyectoPresupuesto:editProyectoPresupuesto.html.twig")
     * @Security("is_granted('ROLE_EDITAR_PRESUPUESTO')")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:ProyectoPresupuesto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProyectoPresupuesto entity.');
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
     * Creates a form to edit a ProyectoPresupuesto entity.
     *
     * @param ProyectoPresupuesto $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(ProyectoPresupuesto $entity)
    {
        $form = $this->createForm(new ProyectoPresupuestoType($this->getUser()), $entity, array(
            'action' => $this->generateUrl('proyectopresupuesto_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ProyectoPresupuesto entity.
     *
     * @Route("/{id}", name="proyectopresupuesto_update")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDITAR_PRESUPUESTO')")
     * @Template("AppBundle:ProyectoPresupuesto:editProyectoPresupuesto.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:ProyectoPresupuesto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProyectoPresupuesto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('proyectopresupuesto_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a ProyectoPresupuesto entity.
     *
     * @Route("/{id}", name="proyectopresupuesto_delete")
     * @Security("is_granted('ROLE_ELIMINAR_PRESUPUESTOS')")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:ProyectoPresupuesto')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ProyectoPresupuesto entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('proyectopresupuesto'));
    }

    /**
     * Creates a form to delete a ProyectoPresupuesto entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('proyectopresupuesto_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('class' => 'hide-submit')))
            ->getForm()
        ;
    }
}
