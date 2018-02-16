<?php

namespace CostoBundle\Controller;

use CostoBundle\Entity\ConsultaUsuario;
use CostoBundle\Form\Type\ConsultaCostoUsuarioType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * ConsutlaCosto controller.
 *
 * @Security("is_granted('ROLE_VER_CONSULTAS')")
 * @Route("/consulta/usuario/")
 */
class ConsultaCostoUsuarioController extends Controller
{
    /**
     * @ROUTE("", name="consulta_usuario")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function consultaUsuarioAction(Request $request)
    {
        $form = $this->createForm(
            ConsultaCostoUsuarioType::class
        );
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
        $horas_extra = $data['horas_extraordinarias'];

        $registros = $this->queryRegistroHorasPorUsuario($fechaInicio, $fechaFinal, $usuario, $horas_extra);
        $returnArray = [];
        foreach ($registros as $registro) {
            $cliente = $registro['cliente'];
            $horas = $registro['horasInvertidas'];
            $costoPorHora = $registro['costo'];
            $costoTotal = $registro['costoTotal'];
            dump($costoTotal);

            $consultaUsuario = new ConsultaUsuario(
              '', '',
              $horas,
              0,
              $costoPorHora,
              $costoTotal
            );
            $consultaUsuario->setCliente($cliente);
            $consultaUsuario->setUsuario($usuario);
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

    private function buscarRegistrosPresupuesto($registros, $usuario)
    {
        $returnArray = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($registros as $registro) {
            $proyecto = $registro->getProyectoPresupuesto();
            $registrosPresupuesto = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('AppBundle:RegistroHorasPresupuesto')
                ->findByUsuario($proyecto, $usuario);
            $registrosArrayCollection = new \Doctrine\Common\Collections\ArrayCollection($registrosPresupuesto);
            $returnArray = $this
                 ->get('consulta.query_controller')
                ->mergeArrayCollectionAction($returnArray, $registrosArrayCollection);
        }

        return $returnArray->toArray();
    }

    private function queryRegistroHorasPorUsuario($fechaInicio, $fechaFinal, $usuario, $horas_extra)
    {
        $repositoryRegistroHoras = $this->getDoctrine()->getRepository('AppBundle:RegistroHoras');
        $qb = $repositoryRegistroHoras->createQueryBuilder('registro');
        $qb
            ->select('registro.horasInvertidas')
            ->addSelect('c.costo')
            ->addSelect('(registro.horasInvertidas * c.costo) as costoTotal' )
            ->addSelect('cl.razonSocial as cliente')
            ->innerJoin('UserBundle:Usuario', 'u', 'with', 'u.id = registro.ingresadoPor')
            ->innerJoin('CostoBundle:Costo', 'c', 'with', 'c.usuario = u.id')
            ->innerJoin('AppBundle:Cliente', 'cl', 'with', 'cl.id = registro.cliente')
            ->where('registro.fechaHoras >= :fechaInicio')
            ->andWhere('registro.fechaHoras <= :fechaFinal')
            ->andWhere('c.fechaInicio <= registro.fechaHoras')
            ->andWhere('c.fechaFinal >= registro.fechaHoras')
            ->andWhere('registro.horasExtraordinarias = :horas_extra')
            ->andWhere('u.id = :usuario')
            ->setParameter('fechaInicio', $fechaInicio)
            ->setParameter('fechaFinal', $fechaFinal)
            ->setParameter('usuario', $usuario)
            ->setParameter('horas_extra', $horas_extra)
            ;

        return $qb->getQuery()->getResult();
    }
}
