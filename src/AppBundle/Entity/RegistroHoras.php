<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RegistroHoras.
 *
 * @ORM\Table()
 * @ORM\Entity
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
     * @var [type]
     *
     * @ORM\ManyToMany(targetEntity="UserBundle\Entity\Usuario", inversedBy="registroHoras")
     * @ORM\JoinTable(name="horas_usuario")
     */
    private $usuarios;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Actividad")
     *
     * @var [type]
     */
    private $actividad;

    /**
     * 
     * @var date
     *
     * @ORM\Column(name="fechaCreacion", type="datetime")
     */
    private $fechaCreacion;

    public function __construct()
    {
        $this->usuarios = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set fecha.
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
     * Add usuario.
     *
     * @param \UserBundle\Entity\Usuario $usuario
     *
     * @return RegistroHoras
     */
    public function addUsuario(\UserBundle\Entity\Usuario $usuario)
    {
        $this->usuarios[] = $usuario;

        return $this;
    }

    /**
     * Add usuario.
     *
     * @param \UserBundle\Entity\Usuario $usuario
     *
     * @return RegistroHoras
     */
    public function getUsuariosSupervisor()
    {
        return $this->usuarios;
    }

    /**
     * Remove usuario.
     *
     * @param \UserBundle\Entity\Usuario $usuario
     */
    public function removeUsuario(\UserBundle\Entity\Usuario $usuario)
    {
        $this->usuarios->removeElement($usuario);
    }

    /**
     * Get usuarios.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuarios()
    {
        return $this->usuarios;
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

    public function __toString()
    {
        return $this->getActividad();
    }
}
