<?php

namespace CostoBundle\Controller;

use CostoBundle\Entity\ConsultaUsuario;
use CostoBundle\Form\Type\ConsultaHorasPresupuestoType;
use CostoBundle\Form\Type\ConsultaHorasType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * ConsutlaHoras controller.
 *
 * @Security("is_granted('ROLE_VER_CONSULTAS')")
 * @Route("/consulta/horas")
 */
class ConsultaHorasController extends Controller
{
    /**
     * @ROUTE("", name="consulta_horas_usuario")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function consultaHorasAction(Request $request)
    {
        $form = $this->createForm(
            ConsultaHorasType::class
        );
        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->render(
                'CostoBundle:ConsultaHoras:consultaHoras.html.twig',
                [
                    'verificador' => '', //mandar variable a javascript
                    'registros' => [],
                    'form' => $form->createView(),
                ]
            );
        }
        $data = $form->getData();
        $consulta = $data['consulta_filtro'];
        $registros = [];
        $repository = 'AppBundle:RegistroHoras';
        if ($consulta === 'Usuario') {
            $registros = $this->consultaUsuario($data, $repository);
        }
        if ($consulta === 'Cliente') {
            $registros = $this->consultaCliente($data, $repository);
        }
        if ($consulta === 'Presupuesto') {
            $registros = $this->consultaPresupuesto($data, $repository);
        }
        return $this->render(
            'CostoBundle:ConsultaHoras:consultaHoras.html.twig',
            [
                'verificador' => $consulta, //mandar variable a javascript
                'registros' => $registros,
                'form' => $form->createView(),
            ]
        );
    }

    /**
    * @ROUTE("/presupuesto", name="consulta_horas_presupuesto_usuario")
    *
    * @param Request $request
    *
    * @return Response
    */
    public function consultaHorasPresupuestoAction(Request $request)
    {
        $form = $this->createForm(
            ConsultaHorasPresupuestoType::class
        );
        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->render(
                'CostoBundle:ConsultaHoras:consultaHorasPresupuesto.html.twig',
                [
                    'verificador' => true, //mandar variable a javascript
                    'registros' => [],
                    'form' => $form->createView(),
                ]
            );
        }
        $data = $form->getData();
        $consulta = $data['consulta_filtro'];
        $registros = [];
        $repository = 'AppBundle:RegistroHorasPresupuesto';
        if ($consulta === 'Usuario') {
            $registros = $this->consultaUsuario($data, $repository);
        }
        if ($consulta === 'Cliente') {
            $registros = $this->consultaCliente($data, $repository);
        }
        if ($consulta === 'Presupuesto') {
            $registros = $this->consultaPresupuesto($data, $repository);
        }
        return $this->render(
            'CostoBundle:ConsultaHoras:consultaHorasPresupuesto.html.twig',
            [
                'verificador' => true, //mandar variable a javascript
                'registros' => $registros,
                'form' => $form->createView(),
            ]
        );
    }

    private function consultaUsuario($data, $repository)
    {
        $fechaInicio = $data['fechaInicio'];
        $fechaFinal = $data['fechaFinal'];
        $horas = '';
        if (array_key_exists('horas_extraordinarias', $data)) {
            $horas = $data['horas_extraordinarias'];
        }
        $usuarios = $data['usuario'];
        $registros = [];
        foreach ($usuarios as $usuario) {
            $registro = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository($repository)
            ->findByFechaAndUsuarioExtra($fechaInicio, $fechaFinal, $usuario, $horas, 100);
            $registros = array_merge($registros, $registro);
        }
        return $registros;
    }

    private function consultaCliente($data, $repository)
    {
        $fechaInicio = $data['fechaInicio'];
        $fechaFinal = $data['fechaFinal'];
        $horas = '';
        if (array_key_exists('horas_extraordinarias', $data)) {
            $horas = $data['horas_extraordinarias'];
        }
        $clientes = $data['cliente'];
        $registros = [];
        foreach ($clientes as $cliente) {
            $registro = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository($repository)
          ->findByFechaAndClienteExtra($fechaInicio, $fechaFinal, $cliente, $horas, 100);
            $registros = array_merge($registros, $registro);
        }
        return $registros;
    }

    private function consultaPresupuesto($data, $repository)
    {
        $fechaInicio = $data['fechaInicio'];
        $fechaFinal = $data['fechaFinal'];
        $horas = '';
        if (array_key_exists('horas_extraordinarias', $data)) {
            $horas = $data['horas_extraordinarias'];
        }
        $presupuestos = $data['proyectoPresupuesto'];
        $registros = [];
        foreach ($presupuestos as $presupuesto) {
            $registro = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository($repository)
          ->findByFechaAndPresupuestoExtra($fechaInicio, $fechaFinal, $presupuesto, $horas, 100);
            $registros = array_merge($registros, $registro);
        }
        return $registros;
    }
}
