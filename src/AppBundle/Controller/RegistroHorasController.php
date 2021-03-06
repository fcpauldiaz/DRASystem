<?php

namespace AppBundle\Controller;

use AppBundle\Entity\RegistroHoras;
use AppBundle\Form\Type\RegistroHorasEditType;
use AppBundle\Form\Type\RegistroHorasType;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\Type\ConsultaRegistrosType;

/**
 * RegistroHoras controller.
 *
 * @Security("is_granted('ROLE_USER')")
 * @Route("/registrohoras")
 */
class RegistroHorasController extends Controller
{
    /**
     * Lists all RegistroHoras entities.
     *
     * @Route("/", name="registrohoras")
     * @Method("GET")
     * @Security("is_granted('ROLE_VER_LISTADO_GENERAL')")
     * @Template("AppBundle:RegistroHoras:indexRegistroHoras.html.twig")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();


        $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
        $claseActual = $discriminator->getClass();

        $form = $this->createForm(ConsultaRegistrosType::class, null, array(
            'action' => $this->generateUrl('registrohoras'),
            'method' => 'GET',
        ));
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $data = $form->getData();

                $fechaInicio = $data['fechaInicio'];
                $fechaFinal = $data['fechaFinal'];
                $cliente = $data['cliente'];
                $horasExtraordinarias = $data['horas_extraordinarias'];
                if ($cliente === null) {
                     $entities = $em
                        ->getRepository('AppBundle:RegistroHoras')
                        ->findByFechaAndUsuario(
                            $fechaInicio,
                            $fechaFinal,
                            $usuario,
                            50
                        );
                } else {
                    $entities = $em
                        ->getRepository('AppBundle:RegistroHoras')
                        ->findByFechaClienteAndUsuario(
                            $fechaInicio,
                            $fechaFinal,
                            $cliente,
                            $usuario,
                            $horasExtraordinarias,
                            50
                        );
                }

            return array(
                'entities' => $entities,
                'form' => $form->createView()
            );
        }
        if ($claseActual == "UserBundle\Entity\UsuarioSocio"
        && $this->isGranted('ROLE_ADMIN')) {
            $entities = $em
                ->getRepository('AppBundle:RegistroHoras')
                ->findBy([], ['fechaHoras' => 'DESC'], 50);
        } else {
            $entities = $em
                ->getRepository('AppBundle:RegistroHoras')
                ->findBy(['ingresadoPor' => $usuario], ['fechaHoras' => 'DESC'], 50);
        }


        return array(
            'entities' => $entities,
            'form' => $form->createView()
        );
    }

    /**
    * Lists all RegistroHoras entities.
    *
    * @Route("/actividad/area", name="registrohoras_por_actividad_area")
    * @Method("POST")
    * @Security("is_granted('ROLE_VER_LISTADO_GENERAL')")
    */
    public function ActividadesPorAreaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        $area = $request->request->get('appbundle_registrohoras')['area'];
        $entities = $em->getRepository('AppBundle:Actividad')->findBy(
            [
                'area' => $area
            ]
        );
        $data_serialized = [];
        foreach ($entities as $entity) {
            $data_serialized[$entity->getId()] = $entity->__toString();
        }
        return new JsonResponse($data_serialized);
    }

    /**
     * Creates a new RegistroHoras entity.
     *
     * @Route("/", name="registrohoras_create")
     * @Method("POST")
     *
     * @Template("AppBundle:RegistroHoras:newRegistroHoras.html.twig")
     */
    public function createAction(Request $request)
    {
        $usuario = $this->getUser();
        if (!is_object($usuario) || !$usuario instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $entity = new RegistroHoras();
        $entity->setIngresadoPor($usuario);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $user = $data->getIngresadoPor();

            $entity->setAprobado(true);
            $horasActividad = $data->getHorasActividad();


            $val_request = $request->request->get('appbundle_registrohoras');
            $entities = [];
            if (!array_key_exists('horasActividad', $val_request)) {
                $this->addFlash('error', 'Necesita agregar una actividad mínimo');
                // if no data is submitted
                return array(
                  'entity' => $entity,
                  'form' => $form->createView(),
                );
            }
            $values = $val_request['horasActividad'];
            if ($data->getFechaHoras() === null) {
                $this->addFlash('error', 'Hubo un error en la fecha');
                 return array(
                  'entity' => $entity,
                  'form' => $form->createView(),
                );
            }
            foreach ($values as $registro) {
                $entity = new RegistroHoras();
                $entity->setFechaHoras($data->getFechaHoras());
                $entity->setHorasInvertidas($registro['horasInvertidas']);
                $actividad = $em->getRepository('AppBundle:Actividad')->find($registro['actividad']);
                $entity->setActividad($actividad);
                $entity->setCliente($data->getCliente());
                $entity->setIngresadoPor($usuario);
                $entity->setProyectoPresupuesto($data->getProyectoPresupuesto());
                $entity->setAprobado($data->getAprobado());

                $extra = array_key_exists('horasExtraordinarias', $registro) ?
                    $registro['horasExtraordinarias']: false;
                $entity->setHorasExtraordinarias($extra);
                $em->persist($entity);

                $entities[] = $entity;
            }
            $em->flush();
            if ($form->get('submitAndSave')->isClicked()) {
                return $this->redirectToRoute('registrohoras_new');
            }
            $form = $this->createForm(ConsultaRegistrosType::class, null, array(
                'action' => $this->generateUrl('registrohoras'),
                'method' => 'GET',
            ));
            return $this->render('AppBundle:RegistroHoras:indexRegistroHoras.html.twig', [
                'entities' => $entities,
                'form' => $form->createView()
            ]);
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

     /**
     * Gets the client for a budget
     *
     * @Route("/presupuesto/cliente", name="registrohoras_client_budget")
     * @Method("POST")
     */
    public function getClientForBudget(Request $request)
    {
        $usuario = $this->getUser();
        if (!is_object($usuario) || !$usuario instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $em = $this->getDoctrine()->getManager();
        $budget_id = $request->request->get('appbundle_registrohoras')['presupuesto'];
        $budget = $em->getRepository('AppBundle:ProyectoPresupuesto')->findOneById($budget_id);
        if ($budget !== null) {
            foreach ($budget->getPresupuestoIndividual() as $individualBudget) {
                if ($individualBudget->getUsuario() === $usuario) {
                    return new JsonResponse($individualBudget->getCliente()->getId());
                }
            }
            $budgetClients = $budget->getClientes();
            if (count($budgetClients) === 1) {
                return new JsonResponse($budgetClients[0]->getId());
            }
        }
        return new JsonResponse(false);
    }


    /**
     * Creates a form to create a RegistroHoras entity.
     *
     * @param RegistroHoras $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(RegistroHoras $entity)
    {
        $form = $this->createForm(RegistroHorasType::class, $entity, array(
            'action' => $this->generateUrl('registrohoras_create'),
            'method' => 'POST',
            'user' => $this->getUser()
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
     * Displays a form to create a new RegistroHoras entity.
     *
     * @Route("/new", name="registrohoras_new")
     * @Method("GET")
     *
     * @Template("AppBundle:RegistroHoras:newRegistroHoras.html.twig")
     */
    public function newAction()
    {
        $entity = new RegistroHoras();
        $form = $this->createCreateForm($entity);
        $em = $this->getDoctrine()->getManager();
        $actividades = $em->getRepository('AppBundle:Actividad')->findAll();
        $returnArray = [];
        foreach ($actividades as $actividad) {
            $returnArray[] = $actividad->__toString();
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'actividades' => $returnArray,
        );
    }

    /**
     * Finds and displays a RegistroHoras entity.
     *
     * @Route("/{id}", name="registrohoras_show", options={"expose"=true})
     * @Method("GET")
     * @Template("AppBundle:RegistroHoras:showRegistroHoras.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:RegistroHoras')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RegistroHoras entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing RegistroHoras entity.
     *
     * @Route("/{id}/edit", name="registrohoras_edit", options={"expose"=true})
     * @Method("GET")
     * @Security("is_granted('ROLE_EDITAR_HORAS')")
     * @Template("AppBundle:RegistroHoras:editRegistroHoras.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:RegistroHoras')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RegistroHoras entity.');
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
     * Creates a form to edit a RegistroHoras entity.
     *
     * @param RegistroHoras $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(RegistroHoras $entity)
    {
        $form = $this->createForm(RegistroHorasType::class, $entity, array(
            'action' => $this->generateUrl('registrohoras_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'user' => $this->getUser()
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing RegistroHoras entity.
     *
     * @Route("/{id}", name="registrohoras_update")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDITAR_HORAS')")
     * @Template("AppBundle:RegistroHoras:editRegistroHoras.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:RegistroHoras')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RegistroHoras entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('registrohoras_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a RegistroHoras entity.
     *
     * @Route("/{id}", name="registrohoras_delete")
     * @Security("is_granted('ROLE_ELIMINAR_HORAS')")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:RegistroHoras')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find RegistroHoras entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('registrohoras'));
    }

    /**
     * Creates a form to delete a RegistroHoras entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('registrohoras_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete', 'attr' => array('class' => 'hide-submit')))
            ->getForm()
        ;
    }
}
