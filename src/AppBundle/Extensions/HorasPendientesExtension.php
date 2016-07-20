<?php

namespace AppBundle\Extensions;

class HorasPendientesExtension extends \Twig_Extension
{
    protected $horasPendientes;

    public function __construct(HorasPendientes $horas)
    {
        $this->horasPendientes = $horas;
    }

    public function getGlobals()
    {
        return array(
            'appstate' => $this->horasPendientes,
        );
    }

    public function getName()
    {
        return 'appstate';
    }
}
