<?php

namespace CostoBundle\Entity;


/**
 * ConsultaPorUsuario.
 * Clase para agrupar los cálculos necesarios
 * para realizar el query de horas por usuario
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
    private $actividades = [];

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
     * 
     * @var Usuario
     */
    private $usuario;

    public function __construct($usuario, $horas, $horasPresupuesto)
    {
        $this->horasCalculadas = $horas;
        $this->usuario = $usuario;
        $this->horasPresupuesto = $horasPresupuesto;
        $this->diferencia = 0;
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

    public function addActividad($actividad)
    {
        $this->actividades[] = $actividad;
        return $this;
    }

    public function getActividades()
    {
        return $this->actividades;
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
}
