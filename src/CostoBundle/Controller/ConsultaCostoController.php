<?php

namespace CostoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CostoBundle\Form\Type\ConsultaPresupuestoType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CostoBundle\Entity\ConsultaUsuario;
use CostoBundle\Entity\ConsultaCliente;
use CostoBundle\Entity\ConsultaActividad;

/**
 * ConsutlaCosto controller.
 *
 * @Security("is_granted('ROLE_USER')")
 * @Route("/consulta/")
 */
class ConsultaCostoController extends Controller
{
    /**
     * @Route("presupuesto/", name="presupuesto_horas")
     */
    public function consultaPresupuestoAction(Request $request)
    {
        $usuario = $this->get('security.token_storage')->getToken()->getUser();
        if (!is_object($usuario) || !$usuario instanceof UserInterface) {
            throw new AccessDeniedException('El usuario no tiene acceso.');
        }

        $form = $this->createForm(
            ConsultaPresupuestoType::class);

        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->render(
                'CostoBundle:Consulta:consultaPorActividad.html.twig',
                [
                    'verificador' => true, //mandar variable a javascript
                    'nombrePresupuesto' => ' ',
                    'consultasPorActividades' => [],
                    'form' => $form->createView(),
                ]
            );
        }
        $data = $form->getData();
        $proyecto = $data['proyecto'];

        $consultaFiltro = $data['consulta_filtro'];

        if ($consultaFiltro == 'Actividad') {
            return $this->consultaPorActividadAction($proyecto, $form);
        }
        //filtar por usuarios
        //Solo puede ver los que tienen jerarquía.
        if ($consultaFiltro == 'Usuarios') {
            //ahora filtrar los usuarios que están involucrados en el proyecto

            return $this->consultaPorUsuariosAction($proyecto, $form);
        }
        //filtrar por clientes
        if ($consultaFiltro == 'Cliente') {
            return $this->consultaPorClientesAction($proyecto, $form);
        }

        //nunca debería llegar aquí
        throw $this->createNotFoundException('Ha seleccionado un parámetro inexistente de búsqueda');
    }

    /**
     * @Route("presupuesto/actividad/individual/{id}/{fechaInicio}/{fechaFinal}", name="presupuesto_individual")
     */
    public function consultaPresupuestoInidividual($id, $fechaInicio, $fechaFinal)
    {
        $em = $this->getDoctrine()->getManager();
        //obtener el presupuesto Proyecto presupuesto
        $presupuesto = $em->getRepository('AppBundle:RegistroHorasPresupuesto')->findOneById($id);
        //obtener los registros que pertencen al proyecto
        $registros = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:RegistroHoras')
            ->findByProyecto($presupuesto->getProyecto());
        //juntar los registros por actividad
        $registrosFiltrados = $this->filtarRegistrosPorActividad($registros, $presupuesto->getActividad());

        $costoReal = $this->calcularCostoDetalle($registrosFiltrados, $fechaInicio, $fechaFinal);

        return $this->render(
            'CostoBundle:Consulta:consultaDetallePorActividad.html.twig',
            [
                'presupuesto' => $presupuesto->getHorasPresupuestadas(),
                'registros' => $registrosFiltrados,
                'costoReal' => $costoReal,
            ]
        );
    }
    /**
     * Detalle de la consulta por usuarios y proyecto.
     *
     * @param ProyectoPresupuesto $proyecto
     *
     * @return Response
     */
    public function consultaPorUsuariosAction($proyecto, $form)
    {    
        //Obtener todos los registros de presupuesto de un proyecto
        $presupuestosIndividuales = $proyecto->getPresupuestoIndividual();
        //Array de entidad Consulta Usuario
        $consultaUsuario = $this->calcularHorasTotalesUsuarios($presupuestosIndividuales, $proyecto, $form);
        $honorarios = $proyecto->getHonorarios();

        $fechaInicio = 'not defined';
        $fechaFinal = 'not defined';
        if (isset($form->getData()['fechaInicio']) && isset($form->getData()['fechaFinal'])) {
            $fechaInicio = $form->getData()['fechaInicio']->format('Y-m-d');
            $fechaFinal = $form->getData()['fechaFinal']->format('Y-m-d');
        }

        return $this->render(
                'CostoBundle:Consulta:consultaPorUsuarios.html.twig',
                [
                    'honorarios' => $honorarios,
                    'verificador' => false, //mandar variable a javascript
                    'consultaUsuario' => $consultaUsuario,
                    'fechaInicio' => $fechaInicio,
                    'fechaFinal' => $fechaFinal,
                    'nombrePresupuesto' => $proyecto->getNombrePresupuesto(),
                    'form' => $form->createView(),
                ]
            );
    }
    /**
     * Consulta de registros y costos por cliente.
     *
     * @param ProyectoPresupuesto $proyecto [
     * @param ConsultaType        $form
     *
     * @return Response
     */
    public function consultaPorClientesAction($proyecto, $form)
    {
        $presupuestosIndividuales = $proyecto->getPresupuestoIndividual();
        $consultaCliente = $this->calcularHorasTotalesCliente($presupuestosIndividuales, $proyecto, $form);
        $honorarios = $proyecto->getHonorarios();

        $fechaInicio = 'not defined';
        $fechaFinal = 'not defined';
        if (isset($form->getData()['fechaInicio']) && isset($form->getData()['fechaFinal'])) {
            $fechaInicio = $form->getData()['fechaInicio']->format('Y-m-d');
            $fechaFinal = $form->getData()['fechaFinal']->format('Y-m-d');
        }

        return $this->render(
            'CostoBundle:Consulta:consultaPorCliente.html.twig',
            [
                'fechaInicio' => $fechaInicio,
                'fechaFinal' => $fechaFinal,
                'honorarios' => $honorarios,
                'verificador' => false, //mandar variable a javascript
                'consultaCliente' => $consultaCliente,
                'nombrePresupuesto' => $proyecto->getNombrePresupuesto(),
                'form' => $form->createView(),
            ]
            );
    }

    /**
     * Detalle de la consulta por Actividad.
     *
     * @param ProyectoPresupuesto $proyecto
     *
     * @return Response
     */
    public function consultaPorActividadAction($proyecto, $form)
    {
        if (isset($proyecto)) {
            //obtener los registros de presupuesto de un proyecto presupuesto
                $presupuestosIndividuales = $proyecto->getPresupuestoIndividual();

                //calculo de todas las horas por actividad
                $consultasPorActividades = $this->calcularHorasTotales($presupuestosIndividuales, $proyecto, $form);
        }
        $honorarios = $proyecto->getHonorarios();
        $fechaInicio = 'not defined';
        $fechaFinal = 'not defined';
        if (isset($form->getData()['fechaInicio']) && isset($form->getData()['fechaFinal'])) {
            $fechaInicio = $form->getData()['fechaInicio']->format('Y-m-d');
            $fechaFinal = $form->getData()['fechaFinal']->format('Y-m-d');
        }

        return $this->render(
                'CostoBundle:Consulta:consultaPorActividad.html.twig',
                [
                    'honorarios' => $honorarios,
                    'nombrePresupuesto' => $proyecto->getNombrePresupuesto(),
                    'consultasPorActividades' => $consultasPorActividades,
                    'proyecto' => $presupuestosIndividuales,
                    'verificador' => false,  //mandar variable a javascript
                    'fechaInicio' => $fechaInicio,
                    'fechaFinal' => $fechaFinal,
                    'form' => $form->createView(),

                ]
            );
    }

    /**
     * Muestra el detalle de una consulta por usuario.
     * 
     * @Route("presupuesto/usuario/individual/{nombrePresupuesto}/{usuario_id}/{fechaInicio}/{fechaFinal}", name="presupuesto_individual_usuario")
     */
    public function consultaUsuarioIndividualAction($nombrePresupuesto, $usuario_id, $fechaInicio, $fechaFinal)
    {
        $em = $this->getDoctrine()->getManager();
        //obtener el presupuesto Proyecto presupuesto
        $proyecto = $em->getRepository('AppBundle:ProyectoPresupuesto')->findOneBy(['nombrePresupuesto' => $nombrePresupuesto]);
        $usuario = $em->getRepository('UserBundle:UsuarioTrabajador')->findOneById($usuario_id);
        //obtener los registros que pertencen al proyecto
        $registros = $em->getRepository('AppBundle:RegistroHoras')->findBy(['proyectoPresupuesto' => $proyecto]);
        //juntar los registros por actividad
        $registrosFiltrados = $this->filtarRegistrosPorUsuario($registros, $usuario);

        $costoReal = $this->calcularCostoDetalle($registrosFiltrados, $fechaInicio, $fechaFinal);

        return $this->render(
            'CostoBundle:Consulta:consultaDetallePorUsuario.html.twig',
            [
               'costoReal' => $costoReal,
               'presupuesto' => $this->calcularHorasPorUsuarioPresupuesto($usuario, $proyecto->getPresupuestoIndividual()),
               'registros' => $registrosFiltrados,
            ]
        );
    }

    /**
     * Muestra el detalle de una consulta por cliente.
     * 
     * @Route("presupuesto/cliente/individual/{nombrePresupuesto}/{cliente_id}/{fechaInicio}/{fechaFinal}", name="presupuesto_individual_cliente")
     */
    public function consultaClienteIndividualAction($nombrePresupuesto, $cliente_id, $fechaInicio, $fechaFinal)
    {
        $em = $this->getDoctrine()->getManager();

        $proyecto = $em->getRepository('AppBundle:ProyectoPresupuesto')->findOneBy(['nombrePresupuesto' => $nombrePresupuesto]);
        $cliente = $em->getRepository('AppBundle:Cliente')->findOneById($cliente_id);
        //obtener solo los registros asociados al proyecto de presupuesto.
        $registros = $em->getRepository('AppBundle:RegistroHoras')->findBy(['proyectoPresupuesto' => $proyecto]);
        $registrosFiltrados = $this->filtarRegistrosPorCliente($registros, $cliente);

        $costoReal = $this->calcularCostoDetalle($registrosFiltrados, $fechaInicio, $fechaFinal);

        return $this->render(
            'CostoBundle:Consulta:consultaDetallePorCliente.html.twig',
            [
                'costoReal' => $costoReal,
                'presupuesto' => $this->calcularHorasPorClientePresupuesto($cliente, $proyecto->getPresupuestoIndividual()),
                'registros' => $registrosFiltrados,
            ]

            );
    }

    /**
     * @Route("presupuesto/usuario/individual", name="filtrar_presupuesto_usuario")
     * Método para filtrar los registros
     *
     * @return [type] [description]
     */
    private function filtarRegistrosPorUsuario($registros, $usuario)
    {
        $returnArray = [];
        foreach ($registros as $registro) {
            if ($registro->getIngresadoPor() == $usuario) {
                $returnArray[] = $registro;
            }
        }

        return $returnArray;
    }
    /**
     * Devuelve solo los registros ingresados por cliente.
     *
     * @param [type] $registros [description]
     * @param [type] $cliente   [description]
     *
     * @return [type] [description]
     */
    private function filtarRegistrosPorCliente($registros, $cliente)
    {
        $returnArray = [];
        foreach ($registros as $registro) {
            if ($registro->getCliente() == $cliente) {
                $returnArray[] = $registro;
            }
        }

        return $returnArray;
    }

    /**
     * Método que calcula las horas totales de un proyecto de todas las actividades.
     *
     * @param RegistroHorasPresupuesto $presupuestosIndividuales
     * @param ProyectoPresupuesto      $proyecto
     *
     * @return Array con las horas invertidas de todas las actividades de un proyecto 
     */
    private function calcularHorasTotales($presupuestosIndividuales, $proyecto, $form)
    {
        $data = $form->getData();
        //registro horas por proyecto
        $registros = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:RegistroHoras')
            ->findByProyecto($proyecto);
        $returnArray = [];

        foreach ($presupuestosIndividuales as $presupuesto) {
            //obtener cada actividad
            //calcular horas por actividad
            //calcular horas presupuesto por actividad
            //get costo por fecha
            //calcular costo monetario por usuario
            //calcular dif
            $actividad = $presupuesto->getActividad();
            $arrayCostos = $this->calcularHorasPorActividad($presupuesto, $registros, $form);
            $horasPresupuesto = $presupuesto->getHorasPresupuestadas();
            $horas = $arrayCostos[0];
            $costo = $arrayCostos[1];
            $costoPresupuesto = $arrayCostos[2];
            $costoPresupuesto = $costoPresupuesto * $presupuesto->getHorasPresupuestadas();
            $consultaActividad = new ConsultaActividad(
                $actividad,
                $horas,
                $horasPresupuesto,
                $costo,
                $costoPresupuesto
            );
            $consultaActividad->setCliente($presupuesto->getCliente());
            $consultaActividad->setPresupuestoId($presupuesto->getId());
            $consultaActividad->calcularDiferencia();
            $returnArray[] = $consultaActividad;
        }

        return $returnArray;
    }

    /**
     * Método costo de horas por usuario.
     *
     * @param RegistroHoraPresupuesto $presupuestosIndividuales
     * @param ProyectoPresupuesto     $proyecto
     *
     * @return Symfony Response                          
     */
    private function calcularHorasTotalesUsuarios($presupuestosIndividuales, $proyecto, $form)
    {
        $data = $form->getData();
        $usuariosAsignadosPorProyecto = $this->filtrarUsuariosAsignadosPorProyecto($presupuestosIndividuales, $proyecto);

        $registros = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:RegistroHoras')
            ->findByProyecto($proyecto);

        //este ciclo coloca en un array instancias de ConsultaUsuario
        //que guarda el costo por usuario.
        foreach ($usuariosAsignadosPorProyecto as $usuario) {
            $horas = $this->calcularHorasPorUsuario($usuario, $registros);
            //horas presupuestadas de un usuarios asignadas
            $horasPresupuesto = $this->calcularHorasPorUsuarioPresupuesto($usuario, $presupuestosIndividuales);
            $costoPorHora = $this->getDoctrine()
                ->getManager()
                ->getRepository('CostoBundle:Costo')
                ->findByFechaAndUsuario($data['fechaInicio'], $data['fechaFinal'], $usuario);
            $costoPorHora = $costoPorHora['costo'];
            $costoTotal = $this->calcularCostoMonetarioPorUsuario($horas, $costoPorHora);

            $consultaUsuario = new ConsultaUsuario(
                $usuario,
                $horas,
                $horasPresupuesto,
                $costoPorHora,
                $costoTotal
            );
            $costoPresupuesto = $costoPorHora * $horasPresupuesto;
            $consultaUsuario->setCostoPresupuesto($costoPresupuesto);
            $consultaUsuario->calcularDiferencia();

            $returnArray[] = $consultaUsuario;
        }
        //ahora que ya tengo los usuarios del proyecto asignado
        //tengo los registros del proyecto
        //acumulo las horas por el usuario que ha ingresado horas

        return $returnArray;
    }

    /**
     * Calcula las horas totales (invertidas y presupuestadas) por cliente.
     *
     * @param RegistroHorasPresupuesto $presupuestosIndividuales ArrayCollection
     * @param ProyectoPresupuesto      $proyecto
     *
     * @return Array of ConsultaCliente                        
     */
    private function calcularHorasTotalesCliente($presupuestosIndividuales, $proyecto, $form)
    {
        $returnArray = [];
        $clientesPorProyecto = $this->filtrarClientesPorProyecto($presupuestosIndividuales, $proyecto);

        $registros = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:RegistroHoras')
            ->findByProyecto($proyecto);

        foreach ($clientesPorProyecto as $cliente) {
            $arrayCostos = $this->calcularHorasPorCliente($cliente, $registros, $form);
            $horasPresupuesto = $this->calcularHorasPorClientePresupuesto($cliente, $presupuestosIndividuales);
            $horas = $arrayCostos[0];
            $costo = $arrayCostos[1];
            $costoPresupuesto = $arrayCostos[2];
            $consultaCliente = new ConsultaCliente(
                $cliente,
                $horas,
                $horasPresupuesto,
                $costo
            );
            $costoPresupuesto = $costoPresupuesto * $horasPresupuesto;
            $consultaCliente->setCostoPresupuesto($costoPresupuesto);
            $consultaCliente->setCliente($cliente);
            $consultaCliente->calcularDiferencia();
            $returnArray[] = $consultaCliente;
        }

        return $returnArray;
    }

    /**
     * Método acumular todos los usuarios asignados en un proyecto.
     *
     * @param RegistroHoraPresupuesto $presupuestosIndividuales
     * @param ProyectoPresupuesto     $proyecto
     *
     * @return Array
     */
    private function filtrarUsuariosAsignadosPorProyecto($presupuestosIndividuales, $proyecto)
    {
        //obtener todos los usuarios asignados en un proyecto

        $usuariosAsignadosPorProyecto = new \Doctrine\Common\Collections\ArrayCollection();

        $returnArray = [];
        foreach ($presupuestosIndividuales as $presupuesto) {
            //sin usuarios repetidos
            $usuariosAsignadosPorProyecto = $this->get('consulta.query_controller')->mergeArrayCollectionAction(
                $usuariosAsignadosPorProyecto,
                $presupuesto->getUsuariosAsignados()
            );
        }

        $usuariosAsignadosPorProyecto = $usuariosAsignadosPorProyecto->toArray();

        return $usuariosAsignadosPorProyecto;
    }
    /**
     * Devuelve los clientes asignados a un proyecto de presupuesto.
     *
     * @param ArrayCollection of RegistroHorasPresupuesto $presupuestosIndividuales
     * @param ProyectoPresupuesto                         $proyecto
     *
     * @return ArrayCollection de clientes                           
     */
    private function filtrarClientesPorProyecto($presupuestosIndividuales, $proyecto)
    {
        $clientesPorProyecto = new \Doctrine\Common\Collections\ArrayCollection();

        $returnArray = [];
        foreach ($presupuestosIndividuales as $presupuesto) {
            //sin clientes repetidos
            //
            $clientesPorProyecto = $this
            ->get('consulta.query_controller')
            ->addArrayCollectionAction($clientesPorProyecto, $presupuesto->getCliente());
        }

        $clientesPorProyecto = $clientesPorProyecto->toArray();

        return $clientesPorProyecto;
    }

    /**
     * Calcula las horas por actividad.
     * En este método hay que considerar que el costo por hora es diferente para cada usuario
     * porque las actividades pueden ser ingreadas por diferentes usuarios y
     * por eso hay que multiplicar de un solo el costo por hora por las horas invertidas.
     *
     * @param ProyectoPresupuesto $presupuesto
     * @param RegistroHoras       $registros
     *
     * @return Float
     */
    private function calcularHorasPorActividad($presupuesto, $registros, $form)
    {
        $horasPresupuesto = $presupuesto->getHorasPresupuestadas();
        $actividad = $presupuesto->getActividad();
        $data = $form->getData();
        $returnArray = [];

        $cantidadHorasPorActividad = 0;
        $costoAcumulado = 0;
        $costoAcumuladoPresupuesto = [];

        foreach ($registros as $registro) {
            $registroActividad = $registro->getActividad();

            if ($actividad == $registroActividad) {
                $horasInvertidas = $registro->getHorasInvertidas();

                $cantidadHorasPorActividad += $horasInvertidas;

                $costoPorHora = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('CostoBundle:Costo')
                    ->findByFechaAndUsuario(
                        $data['fechaInicio'],
                        $data['fechaFinal'],
                        $registro->getIngresadoPor()
                    );

                $costoTotal = $this->calcularCostoMonetarioPorUsuario(
                    $horasInvertidas,
                    $costoPorHora['costo']
                    );

                $costoAcumulado += $costoTotal;
                $costoAcumuladoPresupuesto[] = $costoPorHora['costo'];
            }
        }
        if (count($costoAcumuladoPresupuesto) === 0) {
            $costoAcumuladoPresupuesto[] = 0;
        }
        if ($actividad->getActividadNoCargable() === true) {
            $costoAcumuladoPresupuesto = [];
            $costoAcumuladoPresupuesto[] = 0;
            $costoAcumulado = 0;

            return [$cantidadHorasPorActividad, $costoAcumulado, array_sum($costoAcumuladoPresupuesto) / count($costoAcumuladoPresupuesto)];
        }

        return [$cantidadHorasPorActividad, $costoAcumulado, array_sum($costoAcumuladoPresupuesto) / count($costoAcumuladoPresupuesto)];
    }

    /**
     * Calcula las horas por usuario.
     *
     * @param Usuario       $usuario   usuarios asignados al proyecto
     * @param RegistroHoras $registros del proyecto presupuesto
     *
     * @return Float
     */
    private function calcularHorasPorUsuario($usuario, $registros)
    {
        $cantidadHorasPorUsuario = 0;
        foreach ($registros as $registro) {
            $registroUsuario = $registro->getIngresadoPor();
            if ($registro->getActividad()->getActividadNoCargable() === true) {
                continue;
            }
            if ($usuario == $registroUsuario) {
                $cantidadHorasPorUsuario += $registro->getHorasInvertidas();
            }
        }

        return $cantidadHorasPorUsuario;
    }

    /**
     * Calcula el costo monterio por usuario.
     *
     * @param Usuario       $usuario   usuarios asignados al proyecto
     * @param RegistroHoras $registros del proyecto presupuesto
     *
     * @return Float
     */
    private function calcularCostoMonetarioPorUsuario($horas, $costoPorHora)
    {
        return $horas * $costoPorHora;
    }

    /**
     * Calcula las horas invertidas por cliente.
     *
     * @param Cliente       $cliente
     * @param RegistroHoras $registros
     *
     * @return Float
     */
    private function calcularHorasPorCliente($cliente, $registros, $form)
    {
        $data = $form->getData();

        $cantidadHorasCliente = 0;
        $costoAcumulado = 0;
        $costoAcumuladoCliente = [];
        foreach ($registros as $registro) {
            $registroCliente = $registro->getCliente();
            if ($registroCliente == $cliente) {
                $horasInvertidas = $registro->getHorasInvertidas();
                $cantidadHorasCliente += $horasInvertidas;

                $costoPorHora = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('CostoBundle:Costo')
                    ->findByFechaAndUsuario(
                        $data['fechaInicio'],
                        $data['fechaFinal'],
                        $registro->getIngresadoPor()
                    );

                $costoTotal = $this->calcularCostoMonetarioPorUsuario(
                    $horasInvertidas,
                    $costoPorHora['costo']
                    );
                if ($registro->getActividad()->getActividadNoCargable() !== true) {
                    $costoAcumulado += $costoTotal;
                    $costoAcumuladoCliente[] = $costoPorHora['costo'];
                }
            }
        }
        $cantidadArray = count($costoAcumuladoCliente);
        if ($cantidadArray === 0) {
            $costoAcumuladoCliente[] = 0;
        }

        return [$cantidadHorasCliente, $costoAcumulado, array_sum($costoAcumuladoCliente) / count($costoAcumuladoCliente)];
    }

    /**
     * calcula las horas presupuestadas por cliente.
     *
     * @param Cliente       $cliente
     * @param RegistroHoras $registros
     *
     * @return Float
     */
    private function calcularHorasPorClientePresupuesto($cliente, $registros)
    {
        $cantidadHorasCliente = 0;
        foreach ($registros as $registro) {
            $registroCliente = $registro->getCliente();
            if ($registroCliente == $cliente) {
                $cantidadHorasCliente += $registro->getHorasPresupuestadas();
            }
        }

        return $cantidadHorasCliente;
    }

    private function calcularCostoDetalle($registrosFiltrados, $fechaInicio, $fechaFinal)
    {
        $costoReal = [];
        if ($fechaInicio != 'not defined' && $fechaFinal != 'not defined') {
            foreach ($registrosFiltrados as $registro) {
                $costo = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('CostoBundle:Costo')
                    ->findByFechaAndUsuario($fechaInicio, $fechaFinal, $registro->getIngresadoPor());
                $costo = $costo['costo'];
                if ($registro->getActividad()->getActividadNoCargable() === true) {
                    $costo = 0;
                }
                $costoReal[] = $costo * $registro->getHorasInvertidas();
            }
        }

        return $costoReal;
    }

    /**
     * Calcula las horas por actividad.
     *
     * @param Usuario       $usuario   usuarios asignados al proyecto
     * @param RegistroHoras $registros del proyecto presupuesto
     *
     * @return Float
     */
    private function calcularHorasPorUsuarioPresupuesto($usuario, $registros)
    {
        $cantidadHorasPorUsuario = 0;
        foreach ($registros as $registro) {
            $usuariosAsignados = $registro->getUsuariosAsignados();
            $usuariosAsignados = $usuariosAsignados->toArray();
            foreach ($usuariosAsignados as $usuario2) {
                if ($usuario == $usuario2) {
                    $cantidadHorasPorUsuario += $registro->getHorasPresupuestadas();
                }
            }
        }

        return $cantidadHorasPorUsuario;
    }

    private function getQueryUsuariosPorTipoPuesto($arrayTipoPuestos)
    {
        $repositoryUsuarios = $this->getDoctrine()->getRepository('UserBundle:UsuarioTrabajador');
        $qb = $repositoryUsuarios->createQueryBuilder('usuarios');
        $qb
            ->select('usuario')
            ->leftjoin('usuario.puestos', 'puesto')
            ->leftjoin('puesto.tipopuesto', 'tipopuesto')
            ->where($qb->expr()->in(':tipopuesto'))
            ->setParameter('tipopuesto', $arrayTipoPuestos);

        return $qb->getQuery()->getResult();
    }

    /**
     * Obtiene los RegistroHoras por Actividad.
     *
     * @param Array RegistroHras $registros
     * @param Actividad          $actividad
     *
     * @return Array RegistroHoras           
     */
    private function filtarRegistrosPorActividad($registros, $actividad)
    {
        $registrosFiltrados = [];

        foreach ($registros as $registro) {
            $registroActividad = $registro->getActividad();
            if ($actividad == $registroActividad) {
                $registrosFiltrados[] = $registro;
            }
        }

        return $registrosFiltrados;
    }
}
