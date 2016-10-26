<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\RegistroHorasPresupuesto;
use AppBundle\Form\Type\RegistroHorasPresupuestoType;
use AppBundle\Form\Type\RegistroHorasPresupuestoEditType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * RegistroHorasPresupuesto controller.
 *
 * @Security("is_granted('ROLE_USER')")
 * @Route("/horaspresupuesto")
 */
class RegistroHorasPresupuestoController extends Controller
{
    /**
     * Lists all RegistroHorasPresupuesto entities.
     *
     * @Route("/", name="horaspresupuesto")
     * @Security("is_granted('ROLE_VER_PRESUPUESTOS')")
     * @Method("GET")
     * @Template("AppBundle:RegistroHorasPresupuesto:indexRegistroHorasPresupuesto.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:RegistroHorasPresupuesto')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new RegistroHorasPresupuesto entity.
     *
     * @Route("/", name="horaspresupuesto_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREAR_PRESUPUESTOS')")
     * @Template("AppBundle:RegistroHorasPresupuesto:newRegistroHorasPresupuesto.html.twig")
     */
    public function createAction(Request $request)
    {
        $usuario = $this->getUser();
        if (!is_object($usuario) || !$usuario instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $entity = new RegistroHorasPresupuesto();

        $entity->setIngresadoPor($usuario);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('horaspresupuesto_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a RegistroHorasPresupuesto entity.
     *
     * @param RegistroHorasPresupuesto $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(RegistroHorasPresupuesto $entity)
    {
        $form = $this->createForm(new RegistroHorasPresupuestoType(), $entity, array(
            'action' => $this->generateUrl('horaspresupuesto_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new RegistroHorasPresupuesto entity.
     *
     * @Route("/new", name="horaspresupuesto_new")
     * @Method("GET")
     * @Security("is_granted('ROLE_CREAR_PRESUPUESTOS')")
     * @Template("AppBundle:RegistroHorasPresupuesto:newRegistroHorasPresupuesto.html.twig")
     */
    public function newAction()
    {
        $entity = new RegistroHorasPresupuesto();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a RegistroHorasPresupuesto entity.
     *
     * @Route("/{id}", name="horaspresupuesto_show")
     * @Method("GET")
     * @Security("is_granted('ROLE_VER_PRESUESTOS')")
     * @Template("AppBundle:RegistroHorasPresupuesto:showRegistroHorasPresupuesto.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:RegistroHorasPresupuesto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RegistroHorasPresupuesto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Finds and displays a RegistroHorasPresupuesto entity.
     *
     * @Route("/{id}", name="horaspresupuesto_show_plain")
     * @Method("GET")
     * @Security("is_granted('ROLE_VER_PRESUPUESTOS')")
     * @Template("AppBundle:RegistroHorasPresupuesto:showRegistroHorasPresupuestoPlain.html.twig")
     */
    public function showPlainAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:RegistroHorasPresupuesto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RegistroHorasPresupuesto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing RegistroHorasPresupuesto entity.
     *
     * @Security("is_granted('ROLE_EDITAR_PRESUPUESTO')")
     * @Route("/{id}/edit", name="horaspresupuesto_edit")
     * @Method("GET")
     *@Template("AppBundle:RegistroHorasPresupuesto:editRegistroHorasPresupuesto.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:RegistroHorasPresupuesto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RegistroHorasPresupuesto entity.');
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
     * Creates a form to edit a RegistroHorasPresupuesto entity.
     *
     * @param RegistroHorasPresupuesto $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(RegistroHorasPresupuesto $entity)
    {
        $form = $this->createForm(new RegistroHorasPresupuestoEditType(), $entity, array(
            'action' => $this->generateUrl('horaspresupuesto_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing RegistroHorasPresupuesto entity.
     *
     * @Security("is_granted('ROLE_ELIMINAR_PRESUPUESTOS')")
     * @Route("/{id}", name="horaspresupuesto_update")
     * @Method("PUT")
     * @Template("AppBundle:RegistroHorasPresupuesto:editRegistroHorasPresupuesto.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:RegistroHorasPresupuesto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RegistroHorasPresupuesto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('horaspresupuesto_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a RegistroHorasPresupuesto entity.
     *
     * @Security("is_granted('ROLE_ELIMINAR_HORAS')")
     * @Route("/{id}", name="horaspresupuesto_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:RegistroHorasPresupuesto')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find RegistroHorasPresupuesto entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('horaspresupuesto'));
    }

    /**
     * Creates a form to delete a RegistroHorasPresupuesto entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(null, array('attr' => array('id' => 'delete-form')))
            ->setAction($this->generateUrl('horaspresupuesto_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
