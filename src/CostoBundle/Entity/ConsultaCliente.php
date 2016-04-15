<?php

namespace CostoBundle\Entity;

/**
 * ConsultaPorCliente.
 * Clase para agrupar los cálculos necesarios
 * para realizar el query de horas por cliente.
 */
class ConsultaCliente
{
    /**
     * @var float
     *
     * total horas calculadas invertidaas
     */
    private $horasCalculadas;

    /**
     * @var array
     *
     * actividades
     */
    private $actividad;

    /**
     * @var float
     *
     * actividades
     *
     * Horas presupuestadas que tenía en usuario
     */
    private $horasPresupuesto;

    /**
     * @var float
     *
     * diferencie entre costo y presupuesto
     */
    private $diferencia;

    /**
     * @var Cliente
     */
    private $cliente;

    /**
     * Guarda el costo total.
     *
     * @var float
     */
    private $costoTotal;

    /**
     * Guarda el costo del presupuesto
     * @var float
     */
    private $costoPresupuesto;

    /**
     * Guardar el usuario que ingresó las horas.
     *
     * @var Usuario
     */
    private $usuario;

    public function __construct($cliente, $horas, $horasPresupuesto, $costoTotal)
    {
        $this->horasCalculadas = $horas;
        $this->cliente = $cliente;
        $this->horasPresupuesto = $horasPresupuesto;
        $this->diferencia = 0;
        $this->costoTotal = $costoTotal;
    }

    public function setHorasCalculadas($horas)
    {
        $this->horasCalculadas = $horas;

        return $this;
    }

    public function getHorasCalculadas()
    {
        return $this->horasCalculadas;
    }

    public function setActividad($actividad)
    {
        $this->actividad = $actividad;

        return $this;
    }

    public function getActividad()
    {
        return $this->actividad;
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

    public function setHorasPresupuesto($horasPresupuesto)
    {
        $this->horasPresupuesto = $horasPresupuesto;

        return $this;
    }

    public function getHorasPresupuesto()
    {
        return $this->horasPresupuesto;
    }

    public function getDiferencia()
    {
        return $this->diferencia;
    }

    public function calcularDiferencia()
    {
        $this->diferencia = $this->horasPresupuesto -
        $this->horasCalculadas;

        return $this;
    }

    public function getCostoTotal()
    {
        return $this->costoTotal;
    }

    public function setCostoTotal($costo)
    {
        $this->costoTotal = $costo;

        return $this;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function getCostoPresupuesto()
    {
        return $this->costoPresupuesto;
    }

    public function setCostoPresupuesto($costo)
    {
        $this->costoPresupuesto = $costo;
        return $this;
    }
}
