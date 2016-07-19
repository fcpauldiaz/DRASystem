<?php
namespace AppBundle\Extensions;

use AppBundle\Extensions\HorasPendientes;

class HorasPendientesExtension extends \Twig_Extension
{
    protected $horasPendientes;

    function __construct(HorasPendientes $horas) {
        $this->horasPendientes = $horas;
    }

    public function getGlobals() {
        return array(
            'appstate' => $this->horasPendientes
        );
    }

    public function getName()
    {
        return 'appstate';
    }

}