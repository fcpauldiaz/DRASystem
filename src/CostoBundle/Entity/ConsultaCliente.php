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

    public function __construct($cliente, $horas, $horasPresupuesto, $costoTotal)
    {
        parent::__construct($horas, $horasPresupuesto, $costoTotal);

        $this->cliente = $cliente;
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
}
