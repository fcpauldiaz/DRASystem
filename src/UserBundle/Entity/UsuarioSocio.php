<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="usuario_socio")
 * @UniqueEntity(fields = "username", targetClass = "UserBundle\Entity\Usuario", message="fos_user.username.already_used")
 * @UniqueEntity(fields = "email", targetClass = "UserBundle\Entity\Usuario", message="fos_user.email.already_used")
 *
 *  El usuario Socio no necesita guardar todas los campos de un usuario trabajador
 * Solo necesita los básicos de FOSUserBundle
 *
 * @author  Pablo Díaz support@chapilabs.com
 */
class UsuarioSocio extends Usuario
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        $this->presupuestos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->nombre.' '.$this->apellidos;
    }

    public function getCodigoString()
    {
        return $this->getCodigo().' : '.$this->__toString();
    }
}
