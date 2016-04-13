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
     * @Route("presupuesto/actividad/individual/{id}/", name="presupuesto_individual")
     */
    public function consultaPresupuestoInidividual($id)
    {
        $em = $this->getDoctrine()->getManager();
        //obtener el presupuesto Proyecto presupuesto
        $presupuesto = $em->getRepository('AppBundle:RegistroHorasPresupuesto')->findOneById($id);
        //obtener los registros que pertencen al proyecto
        $registros = $this->getQueryRegistroHorasPorProyecto($presupuesto->getProyecto());
        //juntar los registros por actividad
        $registrosFiltrados = $this->filtarRegistrosPorActividad($registros, $presupuesto->getActividad());

        return $this->render(
            'CostoBundle:Consulta:consultaDetallePorActividad.html.twig',
            [
                'presupuesto' => $presupuesto->getHorasPresupuestadas(),
                'registros' => $registrosFiltrados,
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
    {       //Obtener todos los registros de presupuesto de un proyecto
            $presupuestosIndividuales = $proyecto->getPresupuestoIndividual();
            //Array de entidad Consulta Usuario
            $consultaUsuario = $this->calcularHorasTotalesUsuarios($presupuestosIndividuales, $proyecto, $form);

        return $this->render(
                'CostoBundle:Consulta:consultaPorUsuarios.html.twig',
                [
                    'consultaUsuario' => $consultaUsuario,
                    'nombrePresupuesto' => $proyecto->getNombrePresupuesto(),
                    'form' => $this->createForm(
                    ConsultaPresupuestoType::class)
                    ->createView(),
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

        return $this->render(
            'CostoBundle:Consulta:consultaPorCliente.html.twig',
            [
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

        return $this->render(
                'CostoBundle:Consulta:consultaPorActividad.html.twig',
                [
                    'nombrePresupuesto' => $proyecto->getNombrePresupuesto(),
                    'consultasPorActividades' => $consultasPorActividades,
                    'proyecto' => $presupuestosIndividuales,
                    'form' => $this->createForm(
                     ConsultaPresupuestoType::class)
                    ->createView(),

                ]
            );
    }

    /**
     * Muestra el detalle de una consulta por usuario.
     * 
     * @Route("presupuesto/usuario/individual/{nombrePresupuesto}/{usuario_id}", name="presupuesto_individual_usuario")
     */
    public function consultaUsuarioIndividualAction($nombrePresupuesto, $usuario_id)
    {
        $em = $this->getDoctrine()->getManager();
        //obtener el presupuesto Proyecto presupuesto
        $proyecto = $em->getRepository('AppBundle:ProyectoPresupuesto')->findOneBy(['nombrePresupuesto' => $nombrePresupuesto]);
        $usuario = $em->getRepository('UserBundle:UsuarioTrabajador')->findOneById($usuario_id);
        //obtener los registros que pertencen al proyecto
        $registros = $em->getRepository('AppBundle:RegistroHoras')->findBy(['proyectoPresupuesto' => $proyecto]);
        //juntar los registros por actividad
        $registrosFiltrados = $this->filtarRegistrosPorUsuario($registros, $usuario);

        return $this->render(
            'CostoBundle:Consulta:consultaDetallePorUsuario.html.twig',
            [
               'presupuesto' => $this->calcularHorasPorUsuarioPresupuesto($usuario, $proyecto->getPresupuestoIndividual()),
               'registros' => $registrosFiltrados,
            ]
        );
    }

    /**
     * Muestra el detalle de una consulta por cliente.
     * 
     * @Route("presupuesto/cliente/individual/{nombrePresupuesto}/{cliente_id}", name="presupuesto_individual_cliente")
     */
    public function consultaClienteIndividualAction($nombrePresupuesto, $cliente_id, $form = null)
    {
        $em = $this->getDoctrine()->getManager();

        $proyecto = $em->getRepository('AppBundle:ProyectoPresupuesto')->findOneBy(['nombrePresupuesto' => $nombrePresupuesto]);
        $cliente = $em->getRepository('AppBundle:Cliente')->findOneById($cliente_id);
        //obtener solo los registros asociados al proyecto de presupuesto.
        $registros = $em->getRepository('AppBundle:RegistroHoras')->findBy(['proyectoPresupuesto' => $proyecto]);
        $registrosFiltrados = $this->filtarRegistrosPorCliente($registros, $cliente);

        return $this->render(
            'CostoBundle:Consulta:consultaDetallePorCliente.html.twig',
            [
                'presupuesto' => $this->calcularHorasPorClientePresupuesto($cliente, $proyecto->getPresupuestoIndividual(), $form),
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
        $registros = $this->getQueryRegistroHorasPorProyecto($proyecto);
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

            $consultaActividad = new ConsultaActividad(
                $actividad,
                $horas,
                $horasPresupuesto,
                $costo
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
        $registros = $this->getQueryRegistroHorasPorProyecto($proyecto);
        //este ciclo coloca en un array instancias de ConsultaUsuario
        //que guarda el costo por usuario.
        foreach ($usuariosAsignadosPorProyecto as $usuario) {
            $horas = $this->calcularHorasPorUsuario($usuario, $registros);
            //horas presupuestadas de un usuarios asignadas
            $horasPresupuesto = $this->calcularHorasPorUsuarioPresupuesto($usuario, $presupuestosIndividuales);
            $costoPorHora = $this->getQueryCostoPorFechaYUsuario($data['fechaInicio'], $data['fechaFinal'], $usuario);
            $costoPorHora = $costoPorHora['costo'];
            $costoTotal = $this->calcularCostoMonetarioPorUsuario($horas, $costoPorHora);

            $consultaUsuario = new ConsultaUsuario(
                $usuario,
                $horas,
                $horasPresupuesto,
                $costoPorHora,
                $costoTotal
            );

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
        $registros = $this->getQueryRegistroHorasPorProyecto($proyecto);

        foreach ($clientesPorProyecto as $cliente) {
            $arrayCostos = $this->calcularHorasPorCliente($cliente, $registros, $form);
            $horasPresupuesto = $this->calcularHorasPorClientePresupuesto($cliente, $presupuestosIndividuales);
            $horas = $arrayCostos[0];
            $costo = $arrayCostos[1];

            $consultaCliente = new ConsultaCliente(
                $cliente,
                $horas,
                $horasPresupuesto,
                $costo
            );
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
            $usuariosAsignadosPorProyecto = $this->mergeArrayCollection($usuariosAsignadosPorProyecto, $presupuesto->getUsuariosAsignados());
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
            $clientesPorProyecto = $this->addArrayCollection($clientesPorProyecto, $presupuesto->getCliente());
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
        foreach ($registros as $registro) {
            $registroActividad = $registro->getActividad();

            if ($actividad == $registroActividad) {
                $horasInvertidas = $registro->getHorasInvertidas();

                $cantidadHorasPorActividad += $horasInvertidas;

                $costoPorHora = $this->getQueryCostoPorFechaYUsuario(
                    $data['fechaInicio'],
                    $data['fechaFinal'],
                    $registro->getIngresadoPor()
                );

                $costoTotal = $this->calcularCostoMonetarioPorUsuario(
                    $horasInvertidas,
                    $costoPorHora['costo']
                    );
                $costoAcumulado += $costoTotal;
            }
        }

        return [$cantidadHorasPorActividad, $costoAcumulado];
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
        foreach ($registros as $registro) {
            $registroCliente = $registro->getCliente();
            if ($registroCliente == $cliente) {
                $horasInvertidas = $registro->getHorasInvertidas();
                $cantidadHorasCliente += $horasInvertidas;

                $costoPorHora = $this->getQueryCostoPorFechaYUsuario(
                    $data['fechaInicio'],
                    $data['fechaFinal'],
                    $registro->getIngresadoPor()
                );

                $costoTotal = $this->calcularCostoMonetarioPorUsuario(
                    $horasInvertidas,
                    $costoPorHora['costo']
                    );
                $costoAcumulado += $costoTotal;
            }
        }

        return [$cantidadHorasCliente, $costoAcumulado];
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

    /**
     * Método que devuleve los registros de un Proyecto.
     *
     * @param ProyectoPresupuesto $proyecto
     *
     * @return RegistroHoras
     */
    private function getQueryRegistroHorasPorProyecto($proyecto)
    {
        $repositoryRegistro = $this->getDoctrine()->getRepository('AppBundle:RegistroHoras');
        $qb = $repositoryRegistro->createQueryBuilder('registro');
        $qb
            ->select('registro')
            ->Where('registro.proyectoPresupuesto = :proyecto')
            ->setParameter('proyecto', $proyecto);

        return $qb->getQuery()->getResult();
    }

    /**
     * Query para buscar solo un costo por usuario.
     * Devuelve un costo o null.
     *
     * @param DATE    $fechaInicio
     * @param DATE    $fechaFinal
     * @param Usuario $usuario
     *
     * @return Array Costo de un elemento.
     */
    private function getQueryCostoPorFechaYUsuario($fechaInicio, $fechaFinal, $usuario)
    {
        $repositoryCosto = $this->getDoctrine()->getRepository('CostoBundle:Costo');
        $qb = $repositoryCosto->createQueryBuilder('costo');
        $qb
            ->select('costo.costo')
            ->where('costo.fechaInicio = :fechaInicio')
            ->andWhere('costo.fechaFinal = :fechaFinal')
            ->andWhere('costo.usuario = :usuario')
            ->setParameter('fechaInicio', $fechaInicio)
            ->setParameter('fechaFinal', $fechaFinal)
            ->setParameter('usuario', $usuario);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * query para buscar todos las entidades costo que coincidan con la fecha
     * ingresada.
     * Puede retornar más de una entdidad.
     *
     * @param DATE $fechaInicio
     * @param DaTe $fechaFinal
     *
     * @return Array Costo              [
     */
    private function getQueryCostoPorFecha($fechaInicio, $fechaFinal)
    {
        $repositoryCosto = $this->getDoctrine()->getRepository('CostoBundle:Costo');
        $qb = $repositoryCosto->createQueryBuilder('costo');
        $qb
            ->select('costo.costo')
            ->where('costo.fechaInicio = :fechaInicio')
            ->andWhere('costo.fechaFinal = :fechaFinal')
            ->setParameter('fechaInicio', $fechaInicio)
            ->setParameter('fechaFinal', $fechaFinal);

        return $qb->getQuery()->getResult();
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

    /**
     * Agregar elemento a un array collection.
     *
     * @param ArrayCollection $array1
     * @param T               $item
     */
    private function addArrayCollection($array1, $item)
    {
        if (!$array1->contains($item)) {
            $array1->add($item);
        }

        return $array1;
    }

    /**
     * Unir dos ArrayCollection.
     *
     * @param ArrayCollection $array1
     * @param ArrayCollection $array2
     *
     * @return ArrayCollection
     */
    private function mergeArrayCollection($array1, $array2)
    {
        foreach ($array2 as $item) {
            if (!$array1->contains($item)) {
                $array1->add($item);
            }
        }

        return $array1;
    }
}
