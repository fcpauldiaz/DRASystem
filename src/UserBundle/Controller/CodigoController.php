<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use UserBundle\Entity\Codigo;
use UserBundle\Form\CodigoType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Codigo controller.
 *
 * @Route("/codigo")
 */
class CodigoController extends Controller
{

   
    /**
     * Creates a new Codigo entity.
     *
     * @Route("/", name="codigo_create")
     * @Method("POST")
     * @Template("UserBundle:Codigo:newCodigo.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Codigo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $entities = $em->getRepository('UserBundle:Codigo')->findAll();
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


    }

    /**
     * Creates a form to create a Codigo entity.
     *
     * @param Codigo $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Codigo $entity)
    {
        $form = $this->createForm(new CodigoType(), $entity, array(
            'action' => $this->generateUrl('codigo_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Codigo entity.
     *
     * @Route("/new", name="codigo_new")
     * @Method("GET")
     * @Template("UserBundle:Codigo:newCodigo.html.twig")
     */
    public function newAction()
    {
        $entity = new Codigo();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Codigo entity.
     *
     * @Route("/{id}", name="codigo_show")
     * @Method("GET")
     * @Template("UserBundle:Codigo:showCodigo.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Codigo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Codigo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Codigo entity.
     *
     * @Route("/{id}/edit", name="codigo_edit")
     * @Method("GET")
     *@Template("UserBundle:Codigo:editCodigo.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Codigo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Codigo entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Codigo entity.
    *
    * @param Codigo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Codigo $entity)
    {
        $form = $this->createForm(new CodigoType(), $entity, array(
            'action' => $this->generateUrl('codigo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Codigo entity.
     *
     * @Route("/{id}", name="codigo_update")
     * @Method("PUT")
     * @Template("UserBundle:Codigo:editCodigo.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Codigo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Codigo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('codigo_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
   
}
