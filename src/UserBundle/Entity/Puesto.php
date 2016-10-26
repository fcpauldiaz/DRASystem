<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Puesto.
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Puesto
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
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="TipoPuesto")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $tipoPuesto;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Departamento")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $departamento;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="UsuarioTrabajador",inversedBy="puestos")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $usuario;

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
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set usuario.
     *
     * @param \UserBundle\Entity\UsuarioTrabajador $usuario
     *
     * @return Puesto
     */
    public function setUsuario(\UserBundle\Entity\UsuarioTrabajador $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario.
     *
     * @return \UserBundle\Entity\UsuarioTrabajador
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set tipoPuesto.
     *
     * @param \UserBundle\Entity\TipoPuesto $tipoPuesto
     *
     * @return Puesto
     */
    public function setTipoPuesto(\UserBundle\Entity\TipoPuesto $tipoPuesto = null)
    {
        $this->tipoPuesto = $tipoPuesto;

        return $this;
    }

    /**
     * Get tipoPuesto.
     *
     * @return \UserBundle\Entity\TipoPuesto
     */
    public function getTipoPuesto()
    {
        return $this->tipoPuesto;
    }

    /**
     * Set departamento.
     *
     * @param \UserBundle\Entity\Departamento $departamento
     *
     * @return Puesto
     */
    public function setDepartamento(\UserBundle\Entity\Departamento $departamento = null)
    {
        $this->departamento = $departamento;

        return $this;
    }

    /**
     * Get departamento.
     *
     * @return \UserBundle\Entity\Departamento
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * Set fechaCreacion.
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Puesto
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
     * Set fechaActualizacion.
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return Puesto
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
     * @return Puesto
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
     * @return Puesto
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

    /**
     * Mostrar el noombre del puesto.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->tipoPuesto.': '.$this->departamento;
    }
}
