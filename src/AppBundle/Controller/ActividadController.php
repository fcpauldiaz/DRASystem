<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Actividad;
use AppBundle\Form\Type\ActividadType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Actividad controller.
 *
 * @Security("is_granted('ROLE_USER')")
 * @Route("/actividad")
 */
class ActividadController extends Controller
{
    /**
     * Lists all Actividad entities.
     *
     * @Route("/", name="actividad")
     * @Method("GET")
     * @Security("is_granted('ROLE_VER_ACTIVIDADES')")
     * @Template("AppBundle:Actividad:indexActividad.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em
        ->getRepository('AppBundle:Actividad')
        ->findBy([], ['id'=>'DESC'], 100);

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Actividad entity.
     *
     * @Route("/", name="actividad_create")
     * @Security("is_granted('ROLE_CREAR_ACTIVIDADES')")
     * @Method("POST")
     * @Template("AppBundle:Actividad:newActividad.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Actividad();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            if ($form->get('submitAndSave')->isClicked()) {
                return $this->redirectToRoute('actividad_new');
            }

            return $this->redirect($this->generateUrl('actividad_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Actividad entity.
     *
     * @param Actividad $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Actividad $entity)
    {
        $form = $this->createForm(ActividadType::class, $entity, array(
            'action' => $this->generateUrl('actividad_create'),
            'method' => 'POST',
        ));

        $form->add('submitAndSave', SubmitType::class, [
                    'label' => 'Guardar e ingresar otro',
                    'attr' => [
                        'class' => 'btn btn-primary btn-block',
                    ],
            ]);
        $form->add('submit', SubmitType::class, [
                    'label' => 'Guardar y ver detalle',
                    'attr' => [
                        'class' => 'btn btn-primary btn-block',
                    ],
            ]);

        return $form;
    }

    /**
     * Displays a form to create a new Actividad entity.
     *
     * @Route("/new", name="actividad_new")
     * @Security("is_granted('ROLE_CREAR_ACTIVIDADES')")
     * @Method("GET")
     * @Template("AppBundle:Actividad:newActividad.html.twig")
     */
    public function newAction()
    {
        $entity = new Actividad();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Actividad entity.
     *
     * @Route("/{id}", name="actividad_show")
     * @Method("GET")
     * @Security("is_granted('ROLE_VER_ACTIVIDADES')")
     * @Template("AppBundle:Actividad:showActividad.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Actividad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Actividad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Actividad entity.
     *
     * @Route("/{id}/edit", name="actividad_edit")
     * @Method("GET")
     * @Security("is_granted('ROLE_EDITAR_ACTIVIDADES')")
     * @Template("AppBundle:Actividad:editActividad.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Actividad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Actividad entity.');
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
     * Creates a form to edit a Actividad entity.
     *
     * @param Actividad $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Actividad $entity)
    {
        $form = $this->createForm(ActividadType::class, $entity, array(
            'action' => $this->generateUrl('actividad_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Actividad entity.
     *
     * @Route("/{id}", name="actividad_update")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDITAR_ACTIVIDADES')")
     * @Template("AppBundle:Actividad:editActividad.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Actividad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Actividad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('actividad_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Actividad entity.
     *
     * @Route("/{id}", name="actividad_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_ELIMINAR_ACTIVIDADES')")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Actividad')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Actividad entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('actividad'));
    }

    /**
     * Creates a form to delete a Actividad entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('actividad_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
