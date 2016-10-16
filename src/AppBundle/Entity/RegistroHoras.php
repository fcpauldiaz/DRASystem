<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

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
     * @ORM\Column(name="fecha_horas", type="datetime")
     */
    private $fechaHoras;

    /**
     * @var int
     *
     * @ORM\Column(name="horas_invertidas", type="float")
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
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\UsuarioTrabajador")
     *
     * @var [type]
     */
    private $ingresadoPor;

    /**
     * Fecha de creacion.
     *
     * @ORM\Column(name="fecha_creacion", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $fechaCreacion;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="fecha_actualizacion", type="datetime")
     */
    private $fechaActualizacion;

    /**
     * @var string
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Codigo")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $creadoPor;

    /**
     * @var string
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Codigo")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $actualizadoPor;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProyectoPresupuesto")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $proyectoPresupuesto;

    /**
     * @ORM\Column(name="aprobado", type="boolean")
     *
     * @var bool
     */
    private $aprobado;

    /**
     * @ORM\Column(name="horas_extraordinarias", type="boolean")
     *
     * @var bool
     */
    private $horasExtraordinarias;

    private $horasActividad;

    public function __construct()
    {
        $this->fechaCreacion = new \DateTime();
        $this->aprobado = false;
        $this->horasExtraordinarias = false;
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

    public function getHorasAprobadas($extraordinario = 0)
    {

        //condición para no tomar en cuenta las horas extraordinarias
        if ($extraordinario === 1 && $this->horasExtraordinarias === true) {
            return 0;
        }
        if ($this->aprobado === true) {
            return $this->horasInvertidas;
        }

        return 0;
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

    /**
     * Set aprobado.
     *
     * @param bool $aprobado
     *
     * @return RegistroHoras
     */
    public function setAprobado($aprobado)
    {
        $this->aprobado = $aprobado;

        return $this;
    }

    /**
     * Get aprobado.
     *
     * @return bool
     */
    public function getAprobado()
    {
        return $this->aprobado;
    }

    /**
     * Set horasExtraordinarias.
     *
     * @param bool $horasExtraordinarias
     *
     * @return RegistroHoras
     */
    public function setHorasExtraordinarias($horasExtraordinarias)
    {
        $this->horasExtraordinarias = $horasExtraordinarias;

        return $this;
    }

    /**
     * Método para devolver el conjunto de horas-actividad
     * Este método no es utilizado pero es necesario.
     *
     * @return [type] [description]
     */
    public function getHorasActividad()
    {
        return $this->horasActividad;
    }

    /**
     * Método para devolver el conjunto de horas-actividad
     * Este método no es utilizado pero es necesario.
     */
    public function setHorasActividad($horasActividad)
    {
        $this->horasActividad = $horasActividad;
    }
    /**
     * Get horasExtraordinarias.
     *
     * @return bool
     */
    public function getHorasExtraordinarias()
    {
        return $this->horasExtraordinarias;
    }

    /**
     * Set fechaActualizacion.
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return RegistroHoras
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Get fechaActualizacion.
     *
     * @return \DateTime
     */
    public function getFechaActualizacion()
    {
        return $this->fechaActualizacion;
    }

    /**
     * Set creadoPor.
     *
     * @param string $creadoPor
     *
     * @return RegistroHoras
     */
    public function setCreadoPor($creadoPor)
    {
        $this->creadoPor = $creadoPor;

        return $this;
    }

    /**
     * Get creadoPor.
     *
     * @return string
     */
    public function getCreadoPor()
    {
        return $this->creadoPor;
    }

    /**
     * Set actualizadoPor.
     *
     * @param string $actualizadoPor
     *
     * @return RegistroHoras
     */
    public function setActualizadoPor($actualizadoPor)
    {
        $this->actualizadoPor = $actualizadoPor;

        return $this;
    }

    /**
     * Get actualizadoPor.
     *
     * @return string
     */
    public function getActualizadoPor()
    {
        return $this->actualizadoPor;
    }

    public function __toString()
    {
        return $this->getActividad()->__toString();
    }
}
