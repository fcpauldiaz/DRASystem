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
 * @Security("is_granted('ROLE_USER')")
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
            $horas = $registro->getHorasInvertidas();
            $usuario = $registro->getIngresadoPor();
            $costo = $this->getQueryCostoPorFechaYUsuario($fechaInicio, $fechaFinal, $usuario);
            $costoTotal = $horas * $costo;
            $actividad = $registro->getActividad();
            $horasPresupuesto = $this->calcularHorasPresupuesto($registrosPresupuesto, $actividad);
            if ($actividad->getHoraNoCargable() === true) {
                $costoTotal = 0;
            }
            $consultaCliente = new ConsultaCliente(
                $cliente,
                $horas,
                $horasPresupuesto,
                $costoTotal
                );

            $consultaCliente->setActividad($actividad);
            $consultaCliente->calcularDiferencia();
            $consultaCliente->setUsuario($registro->getIngresadoPor());
            $returnArray[] = $consultaCliente;
        }
        $honorarios = $this->calcularHonorariosTotales($registros);

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

    private function calcularHorasPresupuesto($registrosPresupuesto, $actividad)
    {
        $horasPresupuesto = 0;
        foreach ($registrosPresupuesto as $presupuesto) {
            if ($presupuesto->getActividad() == $actividad) {
                $horasPresupuesto += $presupuesto->getHorasPresupuestadas();
            }
        }

        return $horasPresupuesto;
    }

    private function calcularHonorariosTotales($registros)
    {
        $honorarios = 0;
        //se utilizará este array collection para no usar los honorarios
        //de un proyecto
        $proyectosAcum = new \Doctrine\Common\Collections\ArrayCollection();

        foreach ($registros as $registro) {
            $proyecto = $registro->getProyectoPresupuesto();
            //condición para no usar los honorarios de un mismo proyecto.
            if (!$proyectosAcum->contains($proyecto)) {
                $honorarios += $proyecto->getHonorarios();
            }
            //se agrega el proyecto ya analizado
            $proyectosAcum = $this->addArrayCollection($proyectosAcum, $proyecto);
        }

        return $honorarios;
    }

    private function buscarRegistrosPresupuesto($registros, $cliente)
    {
        $returnArray = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($registros as $registro) {
            $proyecto = $registro->getProyectoPresupuesto();
            $registrosPresupuesto = $this->queryRegistroPresupuestos($proyecto, $cliente);
            $registrosArrayCollection = new \Doctrine\Common\Collections\ArrayCollection($registrosPresupuesto);
            $returnArray = $this->mergeArrayCollection($returnArray, $registrosArrayCollection);
        }

        return $returnArray->toArray();
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

        return $qb->getQuery()->getOneOrNullResult()['costo'];
    }

    private function queryRegistroPresupuestos($proyecto, $cliente)
    {
        $repositoryRegistroHorasPresupuesto = $this->getDoctrine()->getRepository('AppBundle:RegistroHorasPresupuesto');
        $qb = $repositoryRegistroHorasPresupuesto->createQueryBuilder('registro');
        $qb
            ->select('registro')
            ->where('registro.proyecto = :proyecto')
            ->andWhere('registro.cliente = :cliente')
            ->setParameter('proyecto', $proyecto)
            ->setParameter('cliente', $cliente)
            ;

        return $qb->getQuery()->getResult();
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
