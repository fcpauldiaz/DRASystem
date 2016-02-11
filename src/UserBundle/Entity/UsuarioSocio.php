<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;
use UserBundle\Entity\Usuario;

/**
 * @ORM\Entity
 * @ORM\Table(name="usuario_socio")
 * @UniqueEntity(fields = "username", targetClass = "UserBundle\Entity\Usuario", message="fos_user.username.already_used")
 * @UniqueEntity(fields = "email", targetClass = "UserBundle\Entity\Usuario", message="fos_user.email.already_used")
 */
class UsuarioSocio extends Usuario
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Set dpi
     *
     * @param string $dpi
     *
     * @return UsuarioSocio
     */
    public function setDpi($dpi)
    {
        $this->dpi = $dpi;

        return $this;
    }

    /**
     * Get dpi
     *
     * @return string
     */
    public function getDpi()
    {
        return $this->dpi;
    }

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return UsuarioSocio
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return string
     */
    public function getNit()
    {
        return $this->nit;
    }
}
