<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RegistroHorasPresupuesto.
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class RegistroHorasPresupuesto
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
     * @var int
     *
     * @ORM\Column(name="horasPresupuestadas", type="integer")
     */
    private $horasPresupuestadas;

 
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
     * [$proyecto description]
     * @var [type]
     *  @ORM\ManyToOne(targetEntity="ProyectoPresupuesto", inversedBy="presupuestoIndividual", cascade={"persist"})
     */
    private $proyecto;

    /**
     * @var date
     *
     * @ORM\Column(name="fechaCreacion", type="datetime")
     */
    private $fechaCreacion;

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
     * Set horasPresupuestadas.
     *
     * @param int $horasPresupuestadas
     *
     * @return RegistroHoras
     */
    public function setHorasPresupuestadas($horasPresupuestadas)
    {
        $this->horasPresupuestadas = $horasPresupuestadas;

        return $this;
    }

    /**
     * Get horasPresupuestadas.
     *
     * @return int
     */
    public function getHorasPresupuestadas()
    {
        return $this->horasPresupuestadas;
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
     * Set proyecto
     *
     * @param \AppBundle\Entity\ProyectoPresupuesto $proyecto
     *
     * @return RegistroHorasPresupuesto
     */
    public function setProyecto(\AppBundle\Entity\ProyectoPresupuesto $proyecto = null)
    {
        $this->proyecto = $proyecto;


        return $this;
    }

    /**
     * Get proyecto
     *
     * @return \AppBundle\Entity\ProyectoPresupuesto
     */
    public function getProyecto()
    {
        return $this->proyecto;
    }

      public function __toString()
    {
        return 'P'.$this->getId();
    }

}
