<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creacion", type="date")
     */
    private $date;

    /**
     * @var  
     *
     * @ORM\ManyToOne(targetEntity="UsuarioTrabajador",inversedBy="puestos")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $usuario;

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
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Puesto
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
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
     * Mostrar el noombre del puesto.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->tipoPuesto.': '.$this->departamento;
    }
}
