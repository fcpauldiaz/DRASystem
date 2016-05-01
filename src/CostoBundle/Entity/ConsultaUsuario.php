<?php

namespace CostoBundle\Entity;
use CostoBundle\Entity\Consulta as ConsultaMain;
/**
 * ConsultaPorUsuario.
 * Clase para agrupar los cálculos necesarios
 * para realizar el query de horas por usuario.
 */
class ConsultaUsuario extends ConsultaMain
{
  
    /**
     * Guarda el costo por hora del usuario.
     *
     * @var float
     */
    private $costoPorHora;

    private $cliente;

    public function __construct($usuario, $horas, $horasPresupuesto, $costoPorHora, $costoTotal)
    {
        parent::__construct($horas, $horasPresupuesto, $costoTotal);
      $this->costoPorHora = $costoPorHora;
      $this->usuario = $usuario;
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

  }
