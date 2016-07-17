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
        $em = $this->getDoctrine()->getManager();
        $usuarioActual = $this->getUser();
        $repositoryUsuarios = $em->getRepository('UserBundle:Usuario');
        $queryUsuarios = $repositoryUsuarios->createQueryBuilder('usuario')
            ->select('COUNT(usuario.id)');
        $repositoryHoras = $em->getRepository('AppBundle:RegistroHoras');
        $queryHoras = $repositoryHoras->createQueryBuilder('horas')
            ->select('COUNT(horas.id)')
            ->innerJoin('horas.ingresadoPor', 'autor')
            ->where('autor = :usuario')
            ->setParameter('usuario', $usuarioActual);
        $repositoryPresupuesto = $em->getRepository('AppBundle:RegistroHorasPresupuesto');
        $queryPresupuestos = $repositoryPresupuesto->createQueryBuilder('presupuesto')
            ->select('COUNT(presupuesto.id)')
            ->innerJoin('presupuesto.usuario', 'usuario')
            ->where('usuario = :user')
            ->setParameter('user',$usuarioActual);

        $repositoryCosto = $em->getRepository('CostoBundle:Costo');
        $queryCosto = $repositoryCosto->createQueryBuilder('costo')
            ->select('COUNT(costo.id)');


        $cantidadUsuarios = $queryUsuarios->getQuery()->getSingleScalarResult();
        $cantidadHoras = $queryHoras->getQuery()->getSingleScalarResult();
        $cantidadHorasPresupuestadas = $queryPresupuestos->getQuery()->getSingleScalarResult();
        $cantidadCostos = $queryCosto->getQuery()->getSingleScalarResult();
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'cantidadUsuarios' => $cantidadUsuarios,
            'cantidadHoras' => $cantidadHoras,
            'cantidadHorasPresupuestadas' => $cantidadHorasPresupuestadas,
            'cantidadCostos' => $cantidadCostos,
        ));
    }
}
