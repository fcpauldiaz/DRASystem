<?php

namespace CostoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CostoBundle\Form\Type\ConsultaCostoUsuarioType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CostoBundle\Entity\ConsultaUsuario;

/**
 * ConsutlaCosto controller.
 *
 * @Security("is_granted('ROLE_USER')")
 * @Route("/consulta/usuario/")
 */
class ConsultaCostoUsuarioController extends Controller
{
	 /**
     * @ROUTE("", name="consulta_usuario")
     *
     * @param Request $request [description]
     *
     * @return Response
     */
    public function consultaUsuarioAction(Request $request)
    {
        $form = $this->createForm(
            ConsultaCostoUsuarioType::class);
        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->render(
                'CostoBundle:ConsultaUsuario:consultaUsuario.html.twig',
                [
                     'verificador' => true, //mandar variable a javascript
                    'consultaUsuario' => [],
                    'form' => $form->createView(),
                ]
            );
        }
        $data = $form->getData();

        $fechaInicio = $data['fechaInicio'];
        $fechaFinal = $data['fechaFinal'];
        $usuario = $data['usuario'];

        $registros = $this->queryRegistroHorasPorUsuario($fechaInicio, $fechaFinal, $usuario);
        $registrosPresupuesto = $this->buscarRegistrosPresupuesto($registros, $usuario);
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
            $consultaUsuario = new ConsultaUsuario(
                $usuario,
                $horas,
                $horasPresupuesto,
                0,
                $costoTotal
                );
            $consultaUsuario->setCliente($cliente);
            $consultaUsuario->setActividad($actividad);
            $consultaUsuario->calcularDiferencia();
            $returnArray[] = $consultaUsuario;
        }

        return $this->render(
            'CostoBundle:ConsultaUsuario:consultaUsuario.html.twig',
            [
                 'verificador' => false,  //mandar variable a javascript
                'consultaUsuario' => $returnArray,
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

    private function buscarRegistrosPresupuesto($registros, $usuario)
    {
        $returnArray = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($registros as $registro) {
            $proyecto = $registro->getProyectoPresupuesto();
            $registrosPresupuesto = $this->queryRegistroPresupuestos($proyecto, $usuario);
            $registrosArrayCollection = new \Doctrine\Common\Collections\ArrayCollection($registrosPresupuesto);
            $returnArray = $this->mergeArrayCollection($returnArray, $registrosArrayCollection);
        }

        return $returnArray->toArray();
    }

       private function queryRegistroPresupuestos($proyecto, $usuario)
    {
        $repositoryRegistroHorasPresupuesto = $this->getDoctrine()->getRepository('AppBundle:RegistroHorasPresupuesto');
        $qb = $repositoryRegistroHorasPresupuesto->createQueryBuilder('registro');
        $qb
            ->select('registro')
            ->where('registro.proyecto = :proyecto')
            ->leftJoin('registro.usuariosAsignados', 'usuario')
            ->andWhere('usuario = :usuario')
            ->setParameter('proyecto', $proyecto)
            ->setParameter('usuario', $usuario)
            ;

        return $qb->getQuery()->getResult();
    }

    /** Query para buscar solo un costo por usuario.
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


    private function queryRegistroHorasPorUsuario($fechaInicio, $fechaFinal, $usuario)
    {
        $repositoryRegistroHoras = $this->getDoctrine()->getRepository('AppBundle:RegistroHoras');
        $qb = $repositoryRegistroHoras->createQueryBuilder('registro');
        $qb
            ->select('registro')
            ->where('registro.fechaHoras >= :fechaInicio')
            ->andWhere('registro.fechaHoras <= :fechaFinal')
            ->andWhere('registro.ingresadoPor = :usuario')
            ->setParameter('fechaInicio', $fechaInicio)
            ->setParameter('fechaFinal', $fechaFinal)
            ->setParameter('usuario', $usuario)
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