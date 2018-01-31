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
        
        //obtain all areas that have been submit in daily registry.
        $preQueryArea = $this->queryRegistroHorasPorFechaArea($fechaInicio, $fechaFinal, $cliente);
       

        $returnArray = [];
        foreach ($preQueryArea as $area_array) {
            
            $horas = $area_array[1];
            
            $first_date = date('m-01-Y', $fechaInicio->getTimestamp());

            $last_date = date('m-t-Y', $fechaFinal->getTimestamp());
            $area = $area_array['nombre'];

            $costo = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('CostoBundle:Costo')
                ->findCostoPorArea($first_date, $last_date, $area_array['id'], $cliente);
            $costo = $costo[1];
            $costoTotal = $horas * ($costo === null ? 0: $costo);

            // obtain budget to compare
            $horasPresupuesto =  $this->queryRegistrosPresupuestoPorArea($fechaInicio, $fechaFinal, $area_array['id'], $cliente);            
            
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

    private function queryRegistroHorasPorFechaArea($fechaInicio, $fechaFinal, $cliente) 
    {
         $repositoryRegistroHoras = $this->getDoctrine()->getRepository('AppBundle:RegistroHoras');
        $qb = $repositoryRegistroHoras->createQueryBuilder('registro');
        $qb
            ->select('SUM(registro.horasInvertidas)')
            ->addSelect('area.nombre')
            ->addSelect('area.id')
            ->innerJoin('AppBundle:Actividad', 'act', 'with', 'act.id = registro.actividad ')
            ->innerJoin('AppBundle:Area', 'area', 'with', 'act.area = area.id')
            ->where('registro.fechaHoras >= :fechaInicio')
            ->andWhere('registro.fechaHoras <= :fechaFinal')
            ->andWhere('registro.cliente = :cliente')
            ->setParameter('fechaInicio', $fechaInicio)
            ->setParameter('fechaFinal', $fechaFinal)
            ->setParameter('cliente', $cliente)
            ->groupBy('area.id')
           
            ;
        return $qb->getQuery()->getResult();
    }
    private function queryRegistrosPresupuestoPorArea($fechaInicio, $fechaFinal, $area, $cliente) {
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
