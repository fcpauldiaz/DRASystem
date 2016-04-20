<?php

namespace CostoBundle\Entity;

/**
 * ConsultaPorUsuario.
 * Clase para agrupar los cálculos necesarios
 * para realizar el query de horas por usuario.
 */
class ConsultaUsuario
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
     * Guarda el costo por hora del usuario.
     *
     * @var float
     */
    private $costoPorHora;

    /**
     * Se guarda el cálculo del costoMonetario.
     *
     * @var float
     */
    private $costoTotal;

    /**
     * Guarda el costo del Presupuesto.
     *
     * @var float
     */
    private $costoPresupuesto;

    /**
     * @var Usuario
     */
    private $usuario;

    private $cliente;

    public function __construct($usuario, $horas, $horasPresupuesto, $costoPorHora, $costoTotal)
    {
        $this->horasCalculadas = $horas;
        $this->usuario = $usuario;
        $this->horasPresupuesto = $horasPresupuesto;
        $this->diferencia = 0;
        $this->costoPorHora = $costoPorHora;
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

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getUsuario()
    {
        return $this->usuario;
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

    public function getCostoPorHora()
    {
        return $this->costoPorHora;
    }

    public function setCostoPorHora($costoPorHora)
    {
        $this->costoPorHora = $costoPorHora;

        return $this;
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

    public function setCostoPresupuesto($costo)
    {
        $this->costoPresupuesto = $costo;

        return $this;
    }

    public function getCostoPresupuesto()
    {
        return $this->costoPresupuesto;
    }
}
