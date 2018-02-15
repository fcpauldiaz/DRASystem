<?php

namespace CostoBundle\Controller;

use CostoBundle\Entity\ConsultaActividad;
use CostoBundle\Entity\ConsultaCliente;
use CostoBundle\Entity\ConsultaUsuario;
use CostoBundle\Entity\ConsultaClienteProyecto;
use CostoBundle\Form\Type\ConsultaPresupuestoType;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * ConsutlaCosto controller.
 *
 * @Security("is_granted('ROLE_VER_CONSULTAS')")
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
            ConsultaPresupuestoType::class
        );

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
        // filtar por Area
        if ($consultaFiltro == 'Área') {
            return $this->consultaPorAreaAction($proyecto, $form);
        }

        //nunca debería llegar aquí
        throw $this->createNotFoundException('Ha seleccionado un parámetro inexistente de búsqueda');
    }

    /**
     * @Route("presupuesto/actividad/detalle/", name="presupuesto_individual")
     * @Method({"PUT", "GET"})
     * Se mandan los párametro a través del request.
     */
    public function consultaActividadDetalleAction(Request $request)
    {
        $actividad_id = $request->get('actividad_id');
        $proyecto_id = $request->get('proyecto_id');
        $fechaInicio = $request->get('fechaInicio');
        $fechaFinal = $request->get('fechaFinal');
        $horasExtraordinarias = $request->get('horasExtraordinarias');
        $horasPresupuestadas = $request->get('presupuesto');

        $em = $this->getDoctrine()->getManager();

        //obtener el presupuesto Proyecto presupuesto
        $actividad = $em->getRepository('AppBundle:Actividad')->findOneById($actividad_id);
        $proyecto = $em->getRepository('AppBundle:ProyectoPresupuesto')->findById($proyecto_id);
        //obtener los registros que pertencen al proyecto
        $registros = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:RegistroHoras')
            ->findByProyecto($proyecto);
        //juntar los registros por actividad
        $registrosFiltrados = $this->filtarRegistrosPorActividad($registros, $actividad);

        $costoReal = $this->calcularCostoDetalle($registrosFiltrados, $fechaInicio, $fechaFinal, $horasExtraordinarias);

        return $this->render(
            'CostoBundle:Consulta:consultaDetallePorActividad.html.twig',
            [
                'presupuesto' => $horasPresupuestadas,
                'registros' => $registrosFiltrados,
                'costoReal' => $costoReal,
            ]
        );
    }

     /**
     * @Route("presupuesto/area/detalle/", name="presupuesto_area_detalle")
     * @Method({"PUT", "GET"})
     * Se mandan los párametro a través del request.
     */
    public function consultaAreaDetalleAction(Request $request)
    {
        $area_id = $request->get('area_id');
        $area_nombre = $request->get('area_nombre');
        $proyecto_id = $request->get('proyecto_id');
        $horasExtraordinarias = $request->get('horasExtraordinarias');
        $registros = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:RegistroHoras')
            ->findByAreaAndProyectoCosto($area_id, $proyecto_id);
        $arrayRegistros = [];
        foreach($registros as $registro) {
            $actividad = $registro['actividad'];
            $usuario_nombre = $registro['nombre'];
            $usuario_apellido = $registro['apellidos'];
            $cliente = $registro['cliente'];
            $fecha = $registro['fechaHoras'];
            $horas = $registro['horasInvertidas'];
            $costo = $registro['costo'];
            $costoHoras = $horas * $costo;
            $registro['costoReal'] = $costoHoras;
            $arrayRegistros[] = $registro;

        }
        return $this->render(
            'CostoBundle:Consulta:consultaDetalleArea.html.twig',
            [
                'area_nombre' => $area_nombre,
                'registros' => $arrayRegistros,
                'costoReal' => 0,
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

        //Array de entidad Consulta Usuario
        $consultaUsuario = $this->calcularHorasTotalesUsuarios($proyecto, $form);

        $honorarios = $proyecto->getHonorarios();

        $fechaInicio = 'not defined';
        $fechaFinal = 'not defined';
        $data = $form->getData();
        if (isset($form->getData()['fechaInicio']) && isset($form->getData()['fechaFinal'])) {
            $fechaInicio = $data['fechaInicio']->format('Y-m-d');
            $fechaFinal = $data['fechaFinal']->format('Y-m-d');
        }

        return $this->render(
                'CostoBundle:Consulta:consultaPorUsuarios.html.twig',
                [
                    'horasExtraordinarias' => $data['horas_extraordinarias'],
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
        $data = $form->getData();
        if (isset($form->getData()['fechaInicio']) && isset($form->getData()['fechaFinal'])) {
            $fechaInicio = $data['fechaInicio']->format('Y-m-d');
            $fechaFinal = $data['fechaFinal']->format('Y-m-d');
        }

        $honorarios = $proyecto->getHonorarios();

        return $this->render(
            'CostoBundle:Consulta:consultaPorCliente.html.twig',
            [
                'honorarios' => $honorarios,
                'horasExtraordinarias' => $data['horas_extraordinarias'],
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
    public function consultaPorAreaAction($proyecto, $form)
    {
        if (isset($proyecto)) {
            //obtener los registros de presupuesto de un proyecto presupuesto

            //calculo de todas las horas por actividad
            $consultasPorArea = $this->calcularHorasTotales($proyecto, $form);
        }
        $honorarios = $proyecto->getHonorarios();
        $data = $form->getData();


        return $this->render(
                'CostoBundle:Consulta:consultaPorArea.html.twig',
                [
                    'horasExtraordinarias' => $data['horas_extraordinarias'],
                    'verificador' => true,
                    'honorarios' => $honorarios,
                    'proyecto' => $proyecto,
                    'consultasPorArea' => $consultasPorArea,
                    'form' => $form->createView(),

                ]
            );
    }



    /**
     * Muestra el detalle de una consulta por usuario.
     *
     * @Route("presupuesto/usuario/individual/{nombrePresupuesto}/{usuario_id}/{fechaInicio}/{fechaFinal}/{horasExtraordinarias}", name="presupuesto_individual_usuario")
     */
    public function consultaUsuarioIndividualAction($nombrePresupuesto, $usuario_id, $fechaInicio, $fechaFinal, $horasExtraordinarias)
    {
        $em = $this->getDoctrine()->getManager();
        //obtener el presupuesto Proyecto presupuesto
        $proyecto = $em->getRepository('AppBundle:ProyectoPresupuesto')->findOneBy(['nombrePresupuesto' => $nombrePresupuesto]);
        $usuario = $em->getRepository('UserBundle:UsuarioTrabajador')->findOneById($usuario_id);
        //obtener los registros que pertencen al proyecto
        $registros = $em->getRepository('AppBundle:RegistroHoras')->findBy(['proyectoPresupuesto' => $proyecto]);
        //juntar los registros por actividad
        $registrosFiltrados = $this->filtarRegistrosPorUsuario($registros, $usuario);

        $costoReal = $this->calcularCostoDetalle($registrosFiltrados, $fechaInicio, $fechaFinal, $horasExtraordinarias);

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
     * @Route("presupuesto/cliente/individual/{nombrePresupuesto}/{cliente_id}/{fechaInicio}/{fechaFinal}/{horasExtraordinarias}", name="presupuesto_individual_cliente")
     */
    public function consultaClienteIndividualAction($nombrePresupuesto, $cliente_id, $fechaInicio, $fechaFinal, $horasExtraordinarias)
    {
        $em = $this->getDoctrine()->getManager();

        $proyecto = $em->getRepository('AppBundle:ProyectoPresupuesto')->findOneBy(['nombrePresupuesto' => $nombrePresupuesto]);
        $cliente = $em->getRepository('AppBundle:Cliente')->findOneById($cliente_id);
        //obtener solo los registros asociados al proyecto de presupuesto.
        $registros = $em->getRepository('AppBundle:RegistroHoras')->findBy(['proyectoPresupuesto' => $proyecto]);
        $registrosFiltrados = $this->filtarRegistrosPorCliente($registros, $cliente);

        $costoReal = $this->calcularCostoDetalle($registrosFiltrados, $fechaInicio, $fechaFinal, $horasExtraordinarias);

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
     * @return array con las horas invertidas de todas las actividades de un proyecto
     */
    private function calcularHorasTotales($proyecto, $form)
    {
        $returnArray = [];
        //registro horas por proyecto
        $data = $form->getData();
        //1 == no (false),
        $horasExtraordinarias = $data['horas_extraordinarias'] === 1 ? false: true;


        $arrayRegistros = $this->queryRegistroHorasPorFechaArea($proyecto, $horasExtraordinarias);
        foreach ($arrayRegistros as $registroArray) {
            dump($registroArray);

            $horasId = $registroArray['registroId'];
            $horas = $registroArray['horas'];
            $area_nombre = $registroArray['nombre'];
            $area_id = $registroArray['id'];

           $horasPresupuestadas = $this
            ->getDoctrine()
            ->getRepository('AppBundle:RegistroHorasPresupuesto')
            ->findHorasPresupuestoByArea($area_id, $proyecto);

            $horasPresupuestadas = $horasPresupuestadas[1] === null ? 0: $horasPresupuestadas[1];

             $costo = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('CostoBundle:Costo')
                ->findCostoPorAreaProyecto($area_id, $proyecto);

            $costoAcum = 0;
            $horasAcum = 0;
            $horas = explode(',', $horas);
            $horasId = explode(',', $horasId);

            foreach($horasId as $id) {
                foreach($costo as $costoQuery) {
                if ($costoQuery['id'] === $id) {
                    $index = array_search($id, $horasId);
                    $costoAcum += $horas[$index] * $costoQuery['costo'];

                    $horasAcum += $horas[$index];
                }
                }
            }
            $costo = $costoAcum/$horasAcum;
            $horas = $horasAcum;
            $costoReal = $costoAcum;
            $costoPresupuesto = $costo * $horasPresupuestadas;
            $consultaActividad = new ConsultaClienteProyecto(
                $area_nombre,
                $area_id,
                $horas,
                $horasPresupuestadas,
                $costoReal,
                $costoPresupuesto,
                $costo
            );

            $consultaActividad->setPresupuestoId($proyecto->getId());
            $consultaActividad->calcularDiferencia();
            $returnArray[] = $consultaActividad;
        }
        return $returnArray;
    }

    private function queryRegistroHorasPorFechaArea($proyecto, $horasExtraordinarias)
    {
         $repositoryRegistroHoras = $this->getDoctrine()->getRepository('AppBundle:RegistroHoras');
        $qb = $repositoryRegistroHoras->createQueryBuilder('registro');
        $qb
            ->select('GROUP_CONCAT(registro.horasInvertidas) as horas')
            ->addSelect('GROUP_CONCAT(registro.id) as registroId')
            ->addSelect('area.nombre')
            ->addSelect('area.id')
            ->innerJoin('AppBundle:Actividad', 'act', 'with', 'act.id = registro.actividad ')
            ->innerJoin('AppBundle:Area', 'area', 'with', 'act.area = area.id')
            ->innerJoin('AppBundle:ProyectoPresupuesto', 'proy', 'with', 'proy.id = registro.proyectoPresupuesto')
            ->andWhere('proy.id = :proyecto')
            ->andWhere('registro.horasExtraordinarias = :extra_horas')
            ->setParameter('proyecto', $proyecto)
            ->setParameter('extra_horas', $horasExtraordinarias)
            ->groupBy('area.id');
        return $qb->getQuery()->getResult();
    }

    private function searchArray($queryArray, $search)
    {
        foreach($queryArray as $key => $value)
        {
            if (in_array($search, $value)) {
                return $value['horasP'];
            }
        }
        return 0;
    }

    /**
     * Método costo de horas por usuario.
     *
     * @param RegistroHoraPresupuesto $presupuestosIndividuales
     * @param ProyectoPresupuesto     $proyecto
     *
     * @return Symfony Response
     */
    private function calcularHorasTotalesUsuarios($proyecto, $form)
    {
        $data = $form->getData();
        $horasInvertidas =  $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:RegistroHoras')
            ->findByProyectoGroupUsuario($proyecto);

        $queryPresupuesto = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:RegistroHorasPresupuesto')
            ->findByProyectoGroupUsuario($proyecto);
        $returnArray = [];
        foreach($horasInvertidas as $resultHoras) {
            $usuarioId = $resultHoras['id'];
            $nombreUsuario = $resultHoras['nombre'];
            $apellidosUsuario = $resultHoras['apellidos'];
            $horas = $resultHoras['horas'];
            $horasPresupuestadas = $this->searchArray($queryPresupuesto, $usuarioId);
            //ahora calcular costo promedio por usuario y fecha
            $costo = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('CostoBundle:Costo')
                ->findByFechaAndUsuario($data['fechaInicio'], $data['fechaFinal'], $usuarioId);
            $costoPorHora = $costo === null ? 0: $costo;
            $costoTotal = $costoPorHora * $horas;
             $consultaUsuario = new ConsultaUsuario(
                $usuarioId,
                $nombreUsuario .' '. $apellidosUsuario,
                $horas,
                $horasPresupuestadas,
                $costoPorHora,
                $costoTotal
            );
            $costoPresupuesto = $costoPorHora * $horasPresupuestadas;
            $consultaUsuario->setCostoPresupuesto($costoPresupuesto);
            $consultaUsuario->calcularDiferencia();

            $returnArray[] = $consultaUsuario;
        }


        return $returnArray;
    }

    /**
     * Calcula las horas totales (invertidas y presupuestadas) por cliente.
     *
     * @param RegistroHorasPresupuesto $presupuestosIndividuales ArrayCollection
     * @param ProyectoPresupuesto      $proyecto
     *
     * @return array of ConsultaCliente
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
     * @return array
     */
    private function filtrarUsuariosAsignadosPorProyecto($presupuestosIndividuales, $proyecto)
    {
        //obtener todos los usuarios asignados en un proyecto

        $usuariosAsignadosPorProyecto = new \Doctrine\Common\Collections\ArrayCollection();

        foreach ($presupuestosIndividuales as $presupuesto) {
            //sin usuarios repetidos
            $usuariosAsignadosPorProyecto->add($presupuesto->getUsuario());
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

        foreach ($presupuestosIndividuales as $presupuesto) {
            //sin clientes repetidos

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
     * @return float
     */
    private function calcularHorasPorActividad($registros, $actividad, $form)
    {
        $data = $form->getData();
        $cantidadHorasPorActividad = 0;
        $costoAcumulado = 0;
        $costoAcumuladoPresupuesto = [];

        foreach ($registros as $registro) {
            $registroActividad = $registro->getActividad();

            if ($actividad == $registroActividad) {
                $horasInvertidas = $registro->getHorasAprobadas($data['horas_extraordinarias']);

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
     * @return float
     */
    private function calcularHorasPorUsuario($usuario, $registros, $horasExtraordinarias)
    {
        $cantidadHorasPorUsuario = 0;
        foreach ($registros as $registro) {
            $registroUsuario = $registro->getIngresadoPor();
            if ($registro->getActividad()->getActividadNoCargable() === true) {
                continue;
            }
            if ($usuario == $registroUsuario) {
                $cantidadHorasPorUsuario += $registro->getHorasAprobadas($horasExtraordinarias);
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
     * @return float
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
     * @return float
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
                $horasInvertidas = $registro->getHorasAprobadas($data['horas_extraordinarias']);
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
                if ($registro->getActividad()->getActividadNoCargable() === false) {
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
     * @return float
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

    private function calcularCostoDetalle($registrosFiltrados, $fechaInicio, $fechaFinal, $horasExtraordinarias)
    {
        $costoReal = [];
        if ($fechaInicio != 'not defined' && $fechaFinal != 'not defined') {
            foreach ($registrosFiltrados as $registro) {
                $costo = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('CostoBundle:Costo')
                    ->findByFechaAndUsuario($fechaInicio, $fechaFinal, $registro->getIngresadoPor());
                $costo = $costo === null ? 0: $costo;
                if ($registro->getActividad()->getActividadNoCargable() === true) {
                    $costo = 0;
                }
                $calculoCosto = $costo * $registro->getHorasAprobadas($horasExtraordinarias);
                $costoReal[] = ['costoReal' => $calculoCosto, 'costo' => $costo];
            }
        }

        return $costoReal;
    }

    /**
     * Método para agregar las actividades que fueron ingresadas
     * como presupuesto pero no hay ingreso de horas todavía.
     *
     * @param array of Actividadees $verificadorActividades
     * @param array of actividades  $verificador
     *
     * @return array de actividades cmpleto
     */
    private function completarActividades($verificadorActividades, $actividades)
    {
        foreach ($verificadorActividades as $verificador) {
            if (!in_array($verificador, $actividades)) {
                $actividades[] = $verificador;
            }
        }

        return $actividades;
    }

    /**
     * Calcula las horas por actividad.
     *
     * @param Usuario       $usuario   usuarios asignados al proyecto
     * @param RegistroHoras $registros del proyecto presupuesto
     *
     * @return float
     */
    private function calcularHorasPorUsuarioPresupuesto($usuario, $registros)
    {
        $cantidadHorasPorUsuario = 0;
        foreach ($registros as $registro) {
            $cantidadHorasPorUsuario += $registro->getHorasPresupuestadas();
        }

        return $cantidadHorasPorUsuario;
    }

    /**
     * Obtiene los RegistroHoras por Actividad.
     *
     * @param array RegistroHras $registros
     * @param Actividad          $actividad
     *
     * @return array RegistroHoras
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
