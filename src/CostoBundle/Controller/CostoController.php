<?php

namespace CostoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CostoBundle\Entity\Costo;
use CostoBundle\Form\CostoType;

/**
 * Costo controller.
 *
 * @Route("/costo")
 */
class CostoController extends Controller
{

    /**
     * Lists all Costo entities.
     *
     * @Route("/", name="costo")
     * @Method("GET")
     * @Template("CostoBundle:Costo:indexCosto.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CostoBundle:Costo')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Costo entity.
     *
     * @Route("/", name="costo_create")
     * @Method("POST")
     * @Template("CostoBundle:Costo:newCosto.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Costo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $fechaInicio = $form['fechaInicio']->getData();
            $fechaFinal = $form['fechaFinal']->getData();
            $usuario = $form['usuario']->getData();
            $costo = $this->costoAction($fechaInicio,$fechaFinal,$usuario);
            $entity->setCosto($costo);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('costo_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Costo entity.
     *
     * @param Costo $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Costo $entity)
    {
        $form = $this->createForm(new CostoType(), $entity, array(
            'action' => $this->generateUrl('costo_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Costo entity.
     *
     * @Route("/new", name="costo_new")
     * @Method("GET")
     * @Template("CostoBundle:Costo:newCosto.html.twig")
     */
    public function newAction()
    {
        $entity = new Costo();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Costo entity.
     *
     * @Route("/{id}", name="costo_show")
     * @Method("GET")
     * @Template("CostoBundle:Costo:showCosto.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CostoBundle:Costo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Costo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Costo entity.
     *
     * @Route("/{id}/edit", name="costo_edit")
     * @Method("GET")
     * @Template("CostoBundle:Costo:editCosto.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CostoBundle:Costo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Costo entity.');
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
    * Creates a form to edit a Costo entity.
    *
    * @param Costo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Costo $entity)
    {
        $form = $this->createForm(new CostoType(), $entity, array(
            'action' => $this->generateUrl('costo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Costo entity.
     *
     * @Route("/{id}", name="costo_update")
     * @Method("PUT")
     * @Template("CostoBundle:Costo:editCosto.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CostoBundle:Costo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Costo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('costo_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Costo entity.
     *
     * @Route("/{id}", name="costo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CostoBundle:Costo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Costo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('costo'));
    }

    /**
     * Creates a form to delete a Costo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('costo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    public function costoAction($fechaInicio, $fechaFin,$usuario)
    {
        $datosPrestaciones = $usuario->getDatosPrestaciones()->last();
        $totalIngreso = $datosPrestaciones->getSueldo() +
                 $datosPrestaciones->getBonificacionIncentivo() +
                 $datosPrestaciones->getBonificacionLey() +
                 $datosPrestaciones->getGasolina() +
                 $datosPrestaciones->getPrestacionesSobreSueldo() +
                 $datosPrestaciones->getOtrasPrestaciones() +
                 $datosPrestaciones->getOtros() +
                 $datosPrestaciones->getIndemnizacion() +
                 $datosPrestaciones->getAguinaldo() +
                 $datosPrestaciones->getBono14()+
                 $datosPrestaciones->getIgss();
        //ahora busco todas las horas ingresadas por el usuario
        //en el período seleccionado
        $repository = $this->getDoctrine()->getRepository('AppBundle:RegistroHoras');
        $totalHorasPorPeriodo = $repository->createQueryBuilder('registro')
            ->select('SUM(registro.horasInvertidas)')
            ->Where('registro.fechaHoras >= :fechaInicio')
            ->andWhere('registro.fechaHoras < :fechaFin')
            ->andWhere('registro.ingresadoPor = :usuario')
            ->setParameter('fechaInicio', $fechaInicio)
            ->setParameter('fechaFin', $fechaFin)
            ->setParameter('usuario', $usuario)
            ->getQuery()
            ->getSingleScalarResult();
        dump($totalIngreso);
        dump($totalHorasPorPeriodo);
        $costo = 0;
        if ($totalHorasPorPeriodo != 0) {
            $costo = $totalIngreso/$totalHorasPorPeriodo;
        }
        return $costo;

    }
}
