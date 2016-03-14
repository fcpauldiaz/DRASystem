<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use UserBundle\Entity\Departamento;
use UserBundle\Entity\TipoPuesto;
use UserBundle\Form\Type\DepartamentoType;
use UserBundle\Form\Type\TipoPuestoType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * CombinarPuesto controller.
 *
 * @Security("is_granted('ROLE_USER')") 
 */
class CombinarPuestoController extends Controller
{

      /**
     * Muestra el template para un nuevo tipo de contacto
     *
     * @Route("tipopuesto/new", name="tipopuesto_new")
     * @Method("GET")
     * @Template("UserBundle:TipoPuesto:newTipoPuestoAjax.html.twig")
     */
    public function newTipoPuestoAction()
    {
        $entity = new TipoPuesto();
        $form = $this->creatTipoPuestoForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

	 /**
     * Crear un nuevo tipo puesto via ajax
     *
     * @Route("tipopuesto/nuevo", name="tipopuesto_nuevo")
     * @Method("POST")
     * @Template("UserBundle:TipoPuesto:newTipoPuestoAjax.html.twig")
     */
    public function crearTipoPuestoAction(Request $request)
    {
        $entity = new TipoPuesto();
        $form = $this->creatTipoPuestoForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $entities = $em->getRepository('UserBundle:TipoPuesto')->findAll();
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
     * Creates a form to create a TipoPuesto entity.
     *
     * @param TipoPuesto $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function creatTipoPuestoForm(TipoPuesto $entity)
    {
        $form = $this->createForm(new TipoPuestoType(), $entity, array(
            'action' => $this->generateUrl('tipopuesto_nuevo'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

      /**
     * Muestra el template para un nuevo tipo de contacto
     *
     * @Route("departamento/new", name="departamento_new")
     * @Method("GET")
     * @Template("UserBundle:Departamento:newDepartamentoAjax.html.twig")
     */
    public function newDepartamentoAction()
    {
        $entity = new Departamento();
        $form = $this->crearDepartamentoForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

     /**
     * Crear un nuevo tipo puesto via ajax
     *
     * @Route("departamento/nuevo", name="departamento_nuevo")
     * @Method("POST")
     * @Template("UserBundle:Departamento:newDepartamentoAjax.html.twig")
     */
    public function creatDepartamentoAction(Request $request)
    {
        $entity = new Departamento();
        $form = $this->crearDepartamentoForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $entities = $em->getRepository('UserBundle:Departamento')->findAll();
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
     * Creates a form to create a TipoPuesto entity.
     *
     * @param TipoPuesto $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function crearDepartamentoForm(Departamento $entity)
    {
        $form = $this->createForm(new DepartamentoType(), $entity, array(
            'action' => $this->generateUrl('departamento_nuevo'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }


}