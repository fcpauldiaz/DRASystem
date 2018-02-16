<?php

namespace CostoBundle\Entity;

use CostoBundle\Entity\Consulta as ConsultaMain;

/**
 * ConsultaPorCliente.
 * Clase para agrupar los cálculos necesarios
 * para realizar el query de horas por cliente.
 */
class ConsultaCliente extends ConsultaMain
{
    /**
     * @var Cliente
     */
    private $cliente;


    /**
     * Agrupar por area
     * @var Entity
     */
    private $costoPorHora;

    public function __construct($cliente, $horas, $costo, $horasPresupuesto, $costoTotal)
    {
        parent::__construct($horas, $horasPresupuesto, $costoTotal);

        $this->cliente = $cliente;
        $this->costoPorHora = $costo;
    }

    public function setCliente($cliente)
    {
        $this->cliente = $cliente;

        return $this;
    }

    public function getCliente()
    {
        return $this->cliente;
    }

    public function setArea($area)
    {
        $this->area = $area;
    }

    public function getCostoPorHora()
    {
        return $this->costoPorHora;
    }

    public function getArea() 
    {
        return $this->area;
    }
}
