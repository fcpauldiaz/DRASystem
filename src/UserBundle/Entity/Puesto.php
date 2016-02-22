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
     * @ORM\Column(name="tipoPuesto",type="string",length=100)
     */
    private $tipoPuesto;

    /**
     * @var string
     *
     * @ORM\Column(name="nombrePuesto", type="string", length=255)
     */
    private $nombrePuesto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var  
     *
     * @ORM\ManyToOne(targetEntity="UsuarioTrabajador",inversedBy="puestos")
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
     * Set nombrePuesto.
     *
     * @param string $nombrePuesto
     *
     * @return Puesto
     */
    public function setNombrePuesto($nombrePuesto)
    {
        $this->nombrePuesto = $nombrePuesto;

        return $this;
    }

    /**
     * Get nombrePuesto.
     *
     * @return string
     */
    public function getNombrePuesto()
    {
        return $this->nombrePuesto;
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
     * Set tipoPuesto.
     *
     * @param string $tipoPuesto
     *
     * @return Puesto
     */
    public function setTipoPuesto($tipoPuesto)
    {
        $this->tipoPuesto = $tipoPuesto;

        return $this;
    }

    /**
     * Get tipoPuesto.
     *
     * @return string
     */
    public function getTipoPuesto()
    {
        return $this->tipoPuesto;
    }

    /**
     * Mostrar el noombre del puesto.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->tipoPuesto.': '.$this->nombrePuesto;
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
}
