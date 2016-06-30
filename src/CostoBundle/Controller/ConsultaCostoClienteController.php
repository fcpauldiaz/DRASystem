<?php

namespace CostoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CostoBundle\Form\Type\ConsultaCostoClienteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CostoBundle\Entity\ConsultaCliente;

/**
 * ConsutlaCosto controller.
 *
 * @Security("is_granted('ROLE_VER_CONSULTAS')")
 * @Route("/consulta/cliente/")
 */
class ConsultaCostoClienteController extends Controller
{
    /**
     * @ROUTE("", name="consulta_cliente")
     *
     * @param Request $request [description]
     *
     * @return [type] [description]
     */
    public function consultaClienteAction(Request $request)
    {
        $form = $this->createForm(
            ConsultaCostoClienteType::class);
        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->render(
                'CostoBundle:ConsultaCliente:consultaCliente.html.twig',
                [
                    'verificador' => true,
                    'consultaCliente' => [],
                    'form' => $form->createView(),
                ]
            );
        }
        $data = $form->getData();

        $fechaInicio = $data['fechaInicio'];
        $fechaFinal = $data['fechaFinal'];
        $cliente = $data['cliente'];
        //array
        $registros = $this->queryRegistroHorasPorFecha($fechaInicio, $fechaFinal, $cliente);
        //array
        $registrosPresupuesto = $this->buscarRegistrosPresupuesto($registros, $cliente);

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

            $consultaCliente = new ConsultaCliente(
                $cliente,
                $horas,
                $horasPresupuesto,
                $costoTotal
                );
            $consultaCliente->setCostoPresupuesto($costoPresupuesto);
            $consultaCliente->setActividad($actividad);
            $consultaCliente->calcularDiferencia();
            $consultaCliente->setUsuario($registro->getIngresadoPor());
            $returnArray[] = $consultaCliente;
        }
        $honorarios = $this
            ->get('consulta.query_controller')
            ->calcularHonorariosTotales($registros);

        //buscar presupesto en estas fechas
        //buscar registro horas en estas fechas
        //
       return $this->render(
            'CostoBundle:ConsultaCliente:consultaCliente.html.twig',
            [
                'verificador' => false,
                'honorarios' => $honorarios,
                'consultaCliente' => $returnArray,
                'form' => $form->createView(),
            ]
        );
    }

    private function buscarRegistrosPresupuesto($registros, $cliente)
    {
        $returnArray = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($registros as $registro) {
            $proyecto = $registro->getProyectoPresupuesto();
            $registrosPresupuesto = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('AppBundle:RegistroHorasPresupuesto')
                ->findByCliente($proyecto, $cliente);

            $registrosArrayCollection = new \Doctrine\Common\Collections\ArrayCollection($registrosPresupuesto);
            $returnArray = $this
                ->get('consulta.query_controller')
                ->mergeArrayCollectionAction($returnArray, $registrosArrayCollection);
        }

        return $returnArray->toArray();
    }

    private function queryRegistroHorasPorFecha($fechaInicio, $fechaFinal, $cliente)
    {
        $repositoryRegistroHoras = $this->getDoctrine()->getRepository('AppBundle:RegistroHoras');
        $qb = $repositoryRegistroHoras->createQueryBuilder('registro');
        $qb
            ->select('registro')
            ->where('registro.fechaHoras >= :fechaInicio')
            ->andWhere('registro.fechaHoras <= :fechaFinal')
            ->andWhere('registro.cliente = :cliente')
            ->setParameter('fechaInicio', $fechaInicio)
            ->setParameter('fechaFinal', $fechaFinal)
            ->setParameter('cliente', $cliente)
            ;

        return $qb->getQuery()->getResult();
    }
}
