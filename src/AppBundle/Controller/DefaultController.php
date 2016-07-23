<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
       
       
        $cantidadUsuarios = $this->queryUsuarios();
        $cantidadHoras = $this->queryHorasIngresadas();
        $cantidadHorasPresupuestadas = $this->queryHorasPresupuesto();
        $cantidadCostos = $this->queryCosto();

        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'cantidadUsuarios' => $cantidadUsuarios,
            'cantidadHoras' => $cantidadHoras,
            'cantidadHorasPresupuestadas' => $cantidadHorasPresupuestadas,
            'cantidadCostos' => $cantidadCostos,
        ));
    }
    /**
     * Query que devuelve la cantidad de costos guardados del usuairo actual
     * @return 
     */
    private function queryCosto()
    {
        $em = $this->getDoctrine()->getManager();
        $usuarioActual = $this->getUser();
        $repositoryCosto = $em->getRepository('CostoBundle:Costo');
        $queryCosto = $repositoryCosto->createQueryBuilder('costo')
            ->select('COUNT(costo.id)')
            ->where('costo.usuario = :usuarioActual')
            ->setParameter('usuarioActual', $usuarioActual);
        return $queryCosto->getQuery()->getSingleScalarResult();
    }

    /**
     * Query que devuelve las horas del presupuesto.
     * @return float or null.
     */
    private function queryHorasPresupuesto() 
    {
        $em = $this->getDoctrine()->getManager();
        $usuarioActual = $this->getUser();
         $repositoryPresupuesto = $em->getRepository('AppBundle:RegistroHorasPresupuesto');
        $queryPresupuestos = $repositoryPresupuesto->createQueryBuilder('presupuesto')
            ->select('COUNT(presupuesto.id)')
            ->innerJoin('presupuesto.usuario', 'usuario')
            ->where('usuario = :user')
            ->setParameter('user', $usuarioActual);
        return $queryPresupuestos->getQuery()->getSingleScalarResult();
    }

    /**
     * Query que devuelve la cantidad de hoas ingresadas por el usuario logueado.
     * @return float or null
     */
    private function queryHorasIngresadas()
    {
        $em = $this->getDoctrine()->getManager();
        $usuarioActual = $this->getUser();
        $repositoryHoras = $em->getRepository('AppBundle:RegistroHoras');
        $queryHoras = $repositoryHoras->createQueryBuilder('horas')
            ->select('COUNT(horas.id)')
            ->innerJoin('horas.ingresadoPor', 'autor')
            ->where('autor = :usuario')
            ->setParameter('usuario', $usuarioActual);

        return $queryHoras->getQuery()->getSingleScalarResult();
    }

    /**
     * Query que devuelve la cantidad de usuarios actuales en el sistema
     * @return Integer or NULL.
     */
    private function queryUsuarios()
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryUsuarios = $em->getRepository('UserBundle:Usuario');
        $queryUsuarios = $repositoryUsuarios->createQueryBuilder('usuario')
            ->select('COUNT(usuario.id)');

        return $queryUsuarios->getQuery()->getSingleScalarResult();
    }

}
