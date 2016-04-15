<?php

namespace CostoBundle\Twig;

class ArrayExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
        new \Twig_SimpleFunction('sum_horas', array($this, 'sumArrayHorasInvertidas')),
        new \Twig_SimpleFunction('sum_costo', array($this, 'sumArrayCostoTotal')),
        new \Twig_SimpleFunction('sum_presupuesto', array($this, 'sumArrayHorasPresupuesto')),
        new \Twig_SimpleFunction('sum_diferencia', array($this, 'sumArrayDiferencia')),
        new \Twig_SimpleFunction('sum_costo_hora', array($this, 'sumArrayCostoPorHora')),
        new \Twig_SimpleFunction('sum_costo_presupuesto', array($this, 'sumArrayCostoPresupuesto')),
    );
    }

    public function sumArrayCostoPresupuesto($consultas = [])
    {
         $array = [];
        foreach ($consultas as $consulta) {
            $array[] = $consulta->getCostoPresupuesto();
        }

        return array_sum($array);

    }

    public function sumArrayHorasInvertidas($consultas = [])
    {
        $array = [];
        foreach ($consultas as $consulta) {
            $array[] = $consulta->getHorasCalculadas();
        }

        return array_sum($array);
    }

    public function sumArrayHorasPresupuesto($consultas = [])
    {
        $array = [];
        foreach ($consultas as $consulta) {
            $array[] = $consulta->getHorasPresupuesto();
        }

        return array_sum($array);
    }

    public function sumArrayDiferencia($consultas = [])
    {
        $array = [];
        foreach ($consultas as $consulta) {
            $array[] = $consulta->getDiferencia();
        }

        return array_sum($array);
    }

    public function sumArrayCostoTotal($consultas = [])
    {
        $array = [];
        foreach ($consultas as $consulta) {
            $array[] = $consulta->getCostoTotal();
        }

        return array_sum($array);
    }

    public function sumArrayCostoPorHora($consultas = [])
    {
        $array = [];
        foreach ($consultas as $consulta) {
            $array[] = $consulta->getCostoPorHora();
        }

        return array_sum($array);
    }

    public function getName()
    {
        return 'array_extension';
    }
}
