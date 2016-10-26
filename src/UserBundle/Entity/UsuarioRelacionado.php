<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UsuarioRelacionado.
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class UsuarioRelacionado
{
    /**
     * [$usuario description].
     *
     * @var int relationship
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Usuario", inversedBy="misUsuariosRelacionados")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    private $usr;
    /**
     * [$usuarioPertenece description].
     *
     * @var int relationship
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Usuario", inversedBy="usuarioRelacionado")
     * @ORM\JoinColumn(name="usuario_pertenece_id", referencedColumnName="id")
     */
    private $usuarioPertenece;

    public function __construct($usuario, $usuarioPertenece)
    {
        $this->usr = $usuario;
        $this->usuarioPertenece = $usuarioPertenece;
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
     * Set usr.
     *
     * @param \UserBundle\Entity\Usuario $usr
     *
     * @return UsuarioRelacionado
     */
    public function setUsr(\UserBundle\Entity\Usuario $usr)
    {
        $this->usr = $usr;

        return $this;
    }

    /**
     * Get usr.
     *
     * @return \UserBundle\Entity\Usuario
     */
    public function getUsr()
    {
        return $this->usr;
    }

    /**
     * Set usuarioPertenece.
     *
     * @param \UserBundle\Entity\Usuario $usuarioPertenece
     *
     * @return UsuarioRelacionado
     */
    public function setUsuarioPertenece(\UserBundle\Entity\Usuario $usuarioPertenece)
    {
        $this->usuarioPertenece = $usuarioPertenece;

        return $this;
    }

    /**
     * Get usuarioPertenece.
     *
     * @return \UserBundle\Entity\Usuario
     */
    public function getUsuarioPertenece()
    {
        return $this->usuarioPertenece;
    }
}
