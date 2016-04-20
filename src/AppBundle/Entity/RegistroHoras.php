<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RegistroHoras.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="RegistroHorasRepository")
 */
class RegistroHoras
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaHoras", type="date")
     */
    private $fechaHoras;

    /**
     * @var int
     *
     * @ORM\Column(name="horasInvertidas", type="integer")
     */
    private $horasInvertidas;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Actividad")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $actividad;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Cliente")
     * @ORM\JoinColumn(onDelete="SET NULL")
     *
     * @var [type]
     */
    private $cliente;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Usuario")
     *
     * @var [type]
     */
    private $ingresadoPor;

    /**
     * @var date
     *
     * @ORM\Column(name="fechaCreacion", type="datetime")
     */
    private $fechaCreacion;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProyectoPresupuesto")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $proyectoPresupuesto;

    public function __construct()
    {
        $this->fechaCreacion = new \DateTime();
    }
    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fecha.lll.
     *
     * @param \DateTime $fecha
     *
     * @return RegistroHoras
     */
    public function setFechaHoras($fecha)
    {
        $this->fechaHoras = $fecha;

        return $this;
    }

    /**
     * Get fecha.
     *
     * @return \DateTime
     */
    public function getFechaHoras()
    {
        return $this->fechaHoras;
    }

    /**
     * Set horasInvertidas.
     *
     * @param int $horasInvertidas
     *
     * @return RegistroHoras
     */
    public function setHorasInvertidas($horasInvertidas)
    {
        $this->horasInvertidas = $horasInvertidas;

        return $this;
    }

    /**
     * Get horasInvertidas.
     *
     * @return int
     */
    public function getHorasInvertidas()
    {
        return $this->horasInvertidas;
    }

    /**
     * Set actividad.
     *
     * @param \AppBundle\Entity\Actividad $actividad
     *
     * @return RegistroHoras
     */
    public function setActividad(\AppBundle\Entity\Actividad $actividad = null)
    {
        $this->actividad = $actividad;

        return $this;
    }

    /**
     * Get actividad.
     *
     * @return \AppBundle\Entity\Actividad
     */
    public function getActividad()
    {
        return $this->actividad;
    }

    /**
     * Set fechaCreacion.
     *
     * @param \DateTime $fechaCreacion
     *
     * @return RegistroHoras
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion.
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set cliente.
     *
     * @param \AppBundle\Entity\Cliente $cliente
     *
     * @return RegistroHoras
     */
    public function setCliente(\AppBundle\Entity\Cliente $cliente = null)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente.
     *
     * @return \AppBundle\Entity\Cliente
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set ingresadoPor.
     *
     * @param \UserBundle\Entity\Usuario $ingresadoPor
     *
     * @return RegistroHoras
     */
    public function setIngresadoPor(\UserBundle\Entity\Usuario $ingresadoPor = null)
    {
        $this->ingresadoPor = $ingresadoPor;

        return $this;
    }

    /**
     * Get ingresadoPor.
     *
     * @return \UserBundle\Entity\Usuario
     */
    public function getIngresadoPor()
    {
        return $this->ingresadoPor;
    }

    /**
     * Set proyectoPresupuesto.
     *
     * @param \AppBundle\Entity\ProyectoPresupuesto $proyectoPresupuesto
     *
     * @return RegistroHoras
     */
    public function setProyectoPresupuesto(\AppBundle\Entity\ProyectoPresupuesto $proyectoPresupuesto = null)
    {
        $this->proyectoPresupuesto = $proyectoPresupuesto;

        return $this;
    }

    /**
     * Get proyectoPresupuesto.
     *
     * @return \AppBundle\Entity\ProyectoPresupuesto
     */
    public function getProyectoPresupuesto()
    {
        return $this->proyectoPresupuesto;
    }

    public function __toString()
    {
        return $this->getActividad()->__toString();
    }
}
