<?php

namespace AppBundle\Extensions;

class Notification 
{
	private $cantidadUsuarios;
	/**
	 * Array
	 * @var array
	 */
	private $usuariosAndHoras;

	public function __construct($cantidadUsuarios, $usuariosAndHoras)
	{
		$this->cantidadUsuarios = $cantidadUsuarios;
		$this->usuariosAndHoras = $usuariosAndHoras;
	}

	public function setCantidadUsuarios($cantidad)
	{
		$this->cantidadUsuarios = $cantidad;
	}

	public function getCantidadUsuarios()
	{
		return $this->cantidadUsuarios;
	}

	public function setUsuariosAndHoras($arrayUsuarios) 
	{
		$this->usuariosAndHoras = $arrayUsuarios;
	}

	public function getUsuariosAndHoras()
	{
		return $this->usuariosAndHoras;
	}
}