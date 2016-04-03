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
            new ConsultaPresupuestoType());

        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->render(
                'CostoBundle:Consulta:consultaPorActividad.html.twig',
                [
                    'nombrePresupuesto' => ' ',
                    'proyecto' => [],
                    'form' => $form->createView(),
                ]
            );
        }
        $data = $form->getData();
        $proyecto = $data['proyecto'];

        $consultaFiltro = $data['consulta_filtro'];

        if ($consultaFiltro == 0) {
            return $this->consultaPorActividadAction($proyecto, $form);
        }
        //TODO: filtar por usuarios
        //Solo puede ver los que tienen jerarquía.
        if ($consultaFiltro == 1) {
            //ahora filtrar los usuarios que están involucrados en el proyecto

            return $this->consultaPorUsuariosAction($proyecto, $form);
        }
        //TODO: filtrar por clientes
        if ($consultaFiltro == 2) {
            return $this->consultaPorClientesAction($proyecto, $form);
        }

        //nunca debería llegar aquí
        throw $this->createNotFoundException('Ha seleccionado un parámetro inexistente de búsqueda');
    }

    /**
     * @Route("presupuesto/individual/{id}/", name="presupuesto_individual")
     */
    public function consultaPresupuestoInidivual($id)
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
            $consultaUsuario = $this->calcularHorasTotalesUsuarios($presupuestosIndividuales, $proyecto);

        return $this->render(
                'CostoBundle:Consulta:consultaPorUsuarios.html.twig',
                ['consultaUsuario' => $consultaUsuario,
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
                $horasSubTotal = $this->calcularHorasTotales($presupuestosIndividuales, $proyecto);

            $diferencia = [];
            $totalHoras = [];
            $contador = 0;
                //realizar los calculos finales
                while ($contador != count($presupuestosIndividuales)) {
                    $horasPresupuestadas = $presupuestosIndividuales[$contador]->getHorasPresupuestadas();
                    $diferencia[] = $horasPresupuestadas - $horasSubTotal[$contador];
                    $totalHoras[] = $horasPresupuestadas;
                    $contador += 1;
                }
        }

        return $this->render(
                'CostoBundle:Consulta:consultaPorActividad.html.twig',
                [
                    'nombrePresupuesto' => $proyecto->getNombrePresupuesto(),
                    'diferenciaSubTotal' => $diferencia,
                    'horasTotal' => array_sum($horasSubTotal),
                    'diferenciaTotal' => array_sum($diferencia),
                    'horasPresupuestadasTotal' => array_sum($totalHoras),
                    'horasSubTotal' => $horasSubTotal,
                    'proyecto' => $presupuestosIndividuales,
                    'form' => $form->createView(),
                ]
            );
    }

    /**
     * Método que calcula las horas totales de un proyecto de todas las actividades.
     *
     * @param RegistroHorasPresupuesto $presupuestosIndividuales
     * @param ProyectoPresupuesto      $proyecto
     *
     * @return Array con las horas invertidas de todas las actividades de un proyecto 
     */
    private function calcularHorasTotales($presupuestosIndividuales, $proyecto)
    {
        //registro horas por proyecto
        $registros = $this->getQueryRegistroHorasPorProyecto($proyecto);
        $returnArray = [];

        foreach ($presupuestosIndividuales as $presupuesto) {
            $returnArray[] = $this->calcularHorasPorActividad($presupuesto, $registros);
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
    private function calcularHorasTotalesUsuarios($presupuestosIndividuales, $proyecto)
    {
        $usuariosAsignadosPorProyecto = $this->filtrarUsuariosAsignadosPorProyecto($presupuestosIndividuales, $proyecto);

        //este ciclo coloca en un array instancias de ConsultaUsuario
        //que guarda el costo por usuario.
        foreach ($usuariosAsignadosPorProyecto as $usuario) {
            $horas = $this->calcularHorasPorUsuario($usuario, $registros);
            //horas presupuestadas de un usuarios asignadas
            $horasPresupuesto = $this->calcularHorasPorUsuarioPresupuesto($usuario, $presupuestosIndividuales);

            $consultaUsuario = new ConsultaUsuario($usuario, $horas, $horasPresupuesto);
            $consultaUsuario->calcularDiferencia();

            $returnArray[] = $consultaUsuario;
        }
        //ahora que ya tengo los usuarios del proyecto asignado
        //tengo los registros del proyecto
        //acumulo las horas por el usuario que ha ingresado horas

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

        $registros = $this->getQueryRegistroHorasPorProyecto($proyecto);
        $returnArray = [];
        foreach ($presupuestosIndividuales as $presupuesto) {
            //sin usuarios repetidos
            $usuariosAsignadosPorProyecto = $this->mergeArrayCollection($usuariosAsignadosPorProyecto, $presupuesto->getUsuariosAsignados());
        }
        $usuariosAsignadosPorProyecto = $usuariosAsignadosPorProyecto->toArray();

        return $usuariosAsignadosPorProyecto;
    }

    /**
     * Calcula las horas por actividad.
     *
     * @param ProyectoPresupuesto $presupuesto
     * @param RegistroHoras       $registros
     *
     * @return Float
     */
    private function calcularHorasPorActividad($presupuesto, $registros)
    {
        $actividad = $presupuesto->getActividad();

        $cantidadHorasPorActividad = 0;
        foreach ($registros as $registro) {
            $registroActividad = $registro->getActividad();
            if ($actividad == $registroActividad) {
                $cantidadHorasPorActividad += $registro->getHorasInvertidas();
            }
        }

        return $cantidadHorasPorActividad;
    }

    /**
     * Calcula las horas por actividad.
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

    private function getQueryRegistroHorasPorUsuario($proyecto)
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

    private function addArrayCollection($array1, $item)
    {
        if (!$array1->contains($item)) {
            $array1->add($item);
        }

        return $array1;
    }
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
