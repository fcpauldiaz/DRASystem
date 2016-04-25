<?php

namespace CostoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CostoBundle\Form\Type\ConsultaSocioType;
use CostoBundle\Form\Type\ConsultaGerenteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CostoBundle\Entity\ConsultaUsuario;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

/**
 * ConsutlaCosto controller.
 *
 * @Security("is_granted('ROLE_USER')")
 * @Route("/consulta/")
 */
class ConsultaSocioController extends Controller
{
    /**
     * @Route("socio/", name = "consulta_socio")
     */
    public function consultaSocioAction(Request $request)
    {
        $usuario = $this->get('security.token_storage')->getToken()->getUser();
        if (!is_object($usuario) || !$usuario instanceof UserInterface) {
            throw new AccessDeniedException('El usuario no tiene acceso.');
        }

        $form = $this->createForm(
            ConsultaSocioType::class);

        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->render(
                'CostoBundle:ConsultaSocio:consultaPorSocio.html.twig',
                [
                    'verificador' => true, //mandar variable a javascript
                    'nombrePresupuesto' => ' ',
                    'consultaSocio' => [],
                    'form' => $form->createView(),
                ]
            );
        }

        $data = $form->getData();

        $fechaInicio = $data['fechaInicio'];
        $fechaFinal = $data['fechaFinal'];
        $socio = $data['socio'];

        $proyectos = $this->queryProyectosPorSocio($socio);

        $registros = [];
        $registrosPesupuesto = [];
        foreach ($proyectos as $proyecto) {
            $registros = array_merge($registros, $this->queryRegistroHorasPorProyecto($proyecto));
            $registrosPresupuesto = array_merge($registrosPesupuesto, $proyecto->getPresupuestoIndividual()->toArray());
        }
        $returnArray = [];

        foreach ($registros as $registro) {
            $cliente = $registro->getCliente();
            $horas = $registro->getHorasInvertidas($data['horas_extraordinarias']);
            $usuario = $registro->getIngresadoPor();
            $costo = $this->getDoctrine()
                ->getManager()
                ->getRepository('CostoBundle:Costo')
                ->findByFechaAndUsuario($fechaInicio, $fechaFinal, $usuario);
            $costoTotal = $horas * $costo['costo'];
            $actividad = $registro->getActividad();

            $horasPresupuesto = $this
                ->get('consulta.query_controller')
                ->calcularHorasPresupuesto($registrosPresupuesto, $actividad);

            $costoPresupuesto = $horasPresupuesto * $costo['costo'];
            if ($actividad->getActividadNoCargable() === true) {
                $costoTotal = 0;
            }

            $consultaUsuario = new ConsultaUsuario(
                $usuario,
                $horas,
                $horasPresupuesto,
                0,
                $costoTotal
                );
            $consultaUsuario->setCostoPresupuesto($costoPresupuesto);
            $consultaUsuario->setCliente($cliente);
            $consultaUsuario->setActividad($actividad);
            $consultaUsuario->calcularDiferencia();
            $returnArray[] = $consultaUsuario;
        }
        $honorarios = $this
            ->get('consulta.query_controller')
            ->calcularHonorariosTotales($registros);

        return $this->render(
            'CostoBundle:ConsultaSocio:consultaPorSocio.html.twig',
            [
                'honorarios' => $honorarios,
                'verificador' => false,  //mandar variable a javascript
                'consultaSocio' => $returnArray,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("gerente/", name = "consulta_gerente")
     */
    public function consultaGerenteAction(Request $request)
    {
        $usuario = $this->get('security.token_storage')->getToken()->getUser();
        if (!is_object($usuario) || !$usuario instanceof UserInterface) {
            throw new AccessDeniedException('El usuario no tiene acceso.');
        }

        $form = $this->createForm(
            ConsultaGerenteType::class);

        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->render(
                'CostoBundle:ConsultaSocio:consultaPorGerente.html.twig',
                [
                    'verificador' => true, //mandar variable a javascript
                    'nombrePresupuesto' => ' ',
                    'consultaGerente' => [],
                    'form' => $form->createView(),
                ]
            );
        }

        $data = $form->getData();

        $fechaInicio = $data['fechaInicio'];
        $fechaFinal = $data['fechaFinal'];
        $gerente = $data['gerente'];

        $proyectos = $this->queryProyectosPorGerente($gerente);

        $registros = [];
        $registrosPesupuesto = [];
        foreach ($proyectos as $proyecto) {
            $registros = array_merge($registros, $this->queryRegistroHorasPorProyecto($proyecto));
            $registrosPresupuesto = array_merge($registrosPesupuesto, $proyecto->getPresupuestoIndividual()->toArray());
        }
        $returnArray = [];

        foreach ($registros as $registro) {
            $cliente = $registro->getCliente();
            $horas = $registro->getHorasInvertidas($data['horas_extraordinarias']);
            $usuario = $registro->getIngresadoPor();
            $costo = $this->getDoctrine()
                ->getManager()
                ->getRepository('CostoBundle:Costo')
                ->findByFechaAndUsuario($fechaInicio, $fechaFinal, $usuario);
            $costoTotal = $horas * $costo['costo'];
            $actividad = $registro->getActividad();

            $horasPresupuesto = $this
                ->get('consulta.query_controller')
                ->calcularHorasPresupuesto($registrosPresupuesto, $actividad);

            $costoPresupuesto = $horasPresupuesto * $costo['costo'];
            if ($actividad->getActividadNoCargable() === true) {
                $costoTotal = 0;
            }

            $consultaUsuario = new ConsultaUsuario(
                $usuario,
                $horas,
                $horasPresupuesto,
                0,
                $costoTotal
                );
            $consultaUsuario->setCostoPresupuesto($costoPresupuesto);
            $consultaUsuario->setCliente($cliente);
            $consultaUsuario->setActividad($actividad);
            $consultaUsuario->calcularDiferencia();
            $returnArray[] = $consultaUsuario;
        }
        $honorarios = $this
            ->get('consulta.query_controller')
            ->calcularHonorariosTotales($registros);

        return $this->render(
            'CostoBundle:ConsultaSocio:consultaPorGerente.html.twig',
            [
                'honorarios' => $honorarios,
                'verificador' => false,  //mandar variable a javascript
                'consultaGerente' => $returnArray,
                'form' => $form->createView(),
            ]
        );
    }

    private function queryProyectosPorSocio($socio)
    {
        $repositoryProyecto = $this->getDoctrine()->getRepository('AppBundle:ProyectoPresupuesto');
        $qb = $repositoryProyecto->createQueryBuilder('proyecto');
        $qb
            ->select('proyecto')
            ->leftJoin('proyecto.socios', 'socio')
            ->where('socio = :socio')
            ->setParameter('socio', $socio);

        return $qb->getQuery()->getResult();
    }

    private function queryProyectosPorGerente($gerente)
    {
        $repositoryProyecto = $this->getDoctrine()->getRepository('AppBundle:ProyectoPresupuesto');
        $qb = $repositoryProyecto->createQueryBuilder('proyecto');
        $qb
            ->select('proyecto')
            ->leftJoin('proyecto.gerentes', 'gerente')
            ->where('gerente = :gerente')
            ->setParameter('gerente', $gerente);

        return $qb->getQuery()->getResult();
    }

    private function queryRegistroHorasPorProyecto($proyecto)
    {
        $repositoryRegistroHoras = $this->getDoctrine()->getRepository('AppBundle:RegistroHoras');
        $qb = $repositoryRegistroHoras->createQueryBuilder('registro');
        $qb
            ->select('registro')
            ->where('registro.proyectoPresupuesto = :proyecto')
            ->setParameter('proyecto', $proyecto);

        return $qb->getQuery()->getResult();
    }
}
