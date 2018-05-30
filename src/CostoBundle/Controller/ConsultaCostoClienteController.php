<?php

namespace CostoBundle\Controller;

use CostoBundle\Entity\ConsultaCliente;
use CostoBundle\Form\Type\ConsultaCostoClienteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
            ConsultaCostoClienteType::class
        );
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
        $horas_extra = $data['horas_extraordinarias'];

        //obtain all areas that have been submit in daily registry.
        $preQueryArea = $this->queryRegistroHorasPorFechaArea($fechaInicio, $fechaFinal, $cliente, $horas_extra);


        $returnArray = [];
        foreach ($preQueryArea as $registroArray) {
            $horasId = $registroArray['registroId'];
            $horas = $registroArray['horas'];
            $area = $registroArray['nombre'];
            $area_id = $registroArray['id'];

            $first_date = date('m-01-Y', $fechaInicio->getTimestamp());

            $last_date = date('m-t-Y', $fechaFinal->getTimestamp());

            $costo = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('CostoBundle:Costo')
                ->findCostoPorArea($first_date, $last_date, $area_id, $cliente);


            // obtain budget to compare
            $horasPresupuesto =  $this->queryRegistrosPresupuestoPorArea($fechaInicio, $fechaFinal, $area_id, $cliente);

            $costoAcum = 0;
            $horasAcum = 0;
            $horas = explode(',', $horas);
            $horasId = explode(',', $horasId);

            foreach ($horasId as $id) {
                foreach ($costo as $costoQuery) {
                    if ($costoQuery['id'] == $id) {
                        $index = array_search($id, $horasId);
                        $costoAcum += $horas[$index] * $costoQuery['costo'];

                        $horasAcum += $horas[$index];
                    }
                }
            }

            $costo = $costoAcum/$horasAcum;
            $horas = $horasAcum;
            $costoTotal = $costoAcum;
            $costoPresupuesto = $horasPresupuesto === null ? 0: $horasPresupuesto[1]*$costo;

            $consultaCliente = new ConsultaCliente(
                $cliente,
                $horas,
                $costo,
                $horasPresupuesto === null ? 0: $horasPresupuesto[1],
                $costoTotal
                );
            $consultaCliente->setArea($area);
            $consultaCliente->setCostoPresupuesto($costoPresupuesto);
            $consultaCliente->calcularDiferencia();

            $returnArray[] = $consultaCliente;
        }
        $honorarios = $this
            ->get('consulta.query_controller')
            ->calcularHonrariosPorCliente($cliente);


        return $this->render(
            'CostoBundle:ConsultaCliente:consultaCliente.html.twig',
            [
                'verificador' => false,
                'honorarios' => $honorarios[1],
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

    private function queryRegistroHorasPorFechaArea($fechaInicio, $fechaFinal, $cliente, $horas)
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
            ->innerJoin('AppBundle:Cliente', 'cliente', 'with', 'cliente.id = registro.cliente')
            ->andWhere('cliente.id = :cliente')
            ->andWhere('registro.horasExtraordinarias = :extra_horas')
            ->setParameter('cliente', $cliente)
            ->setParameter('extra_horas', $horas)
            ->groupBy('area.id');
        return $qb->getQuery()->getResult();
    }
    private function queryRegistrosPresupuestoPorArea($fechaInicio, $fechaFinal, $area, $cliente)
    {
        $repositoryRegistroHoras = $this->getDoctrine()->getRepository('AppBundle:RegistroHorasPresupuesto');
        $qb = $repositoryRegistroHoras->createQueryBuilder('registro');
        $qb
            ->select('SUM(registro.horasPresupuestadas)')
            ->andWhere('registro.cliente = :cliente')
            ->andWhere('registro.area = :area_id')
            ->andWhere('registro.cliente = :cliente')
            ->setParameter('area_id', $area)
            ->setParameter('cliente', $cliente)
            ->groupBy('registro.area');
        return $qb->getQuery()->getOneOrNullResult();
    }
}
