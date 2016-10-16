<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class RegistroHorasDatatable
 *
 * @package AppBundle\Datatables
 */
class RegistroHorasDatatable extends AbstractDatatableView
{

    public function updateAjax(array $customOptions = array())
    {
        if (empty($customOptions) !== true) {
            $user_id = $customOptions[0];     
        }
        if (array_key_exists(1, $customOptions) && array_key_exists(2, $customOptions)) {
            $fechaInicio = $customOptions[1];
            $fechaFinal = $customOptions[2];
        } else {
            $fechaInicio = '';
            $fechaFinal = '';
        }
       
       /* if ($this->ajax->options['']])) {
            $fechaInicio = $this->ajax['url']['fechaInicio'];
        }
        if (array_key_exists('fechaFinal', $this->ajax['url'])){
            $fechaInicio = $this->ajax['url']['fechaFinal'];
        }*/
        $this->ajax->set(array(
            'url' => $this->router->generate('registrohoras_results', [
                'user_id' => $user_id,
                'fechaInicio' => $fechaInicio, 
                'fechaFinal' => $fechaFinal
            ]),
            'type' => 'GET',
            'pipeline' => 0,
        ));
        
    }

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $customOptions = array())
    {
        if (empty($customOptions) !== true) {
            $user_id = $customOptions[0];     
        }
        if (array_key_exists(1, $customOptions) && array_key_exists(2, $customOptions)) {
            $fechaInicio = $customOptions[1];
            $fechaFinal = $customOptions[2];
        } else {
            $fechaInicio = '';
            $fechaFinal = '';
        }
       
        $this->features->set(array(
            'auto_width' => true,
            'defer_render' => false,
            'info' => true,
            'jquery_ui' => false,
            'length_change' => true,
            'ordering' => true,
            'paging' => true,
            'processing' => true,
            'scroll_x' => false,
            'scroll_y' => '',
            'searching' => true,
            'state_save' => false,
            'delay' => 0,
            'extensions' => array(
                'responsive' => true,
                'fixedHeader' => true,
            ),
            'highlight' => true,
            'highlight_color' => 'yellow'
        ));


        $this->options->set(array(
            'display_start' => 0,
            'defer_loading' => -1,
            'dom' => 'lfrtip',
            'length_menu' => array(10, 25, 50, 100),
            'order_classes' => true,
            'order' => array(array(0, 'asc')),
            'order_multi' => true,
            'page_length' => 10,
            'paging_type' => Style::FULL_NUMBERS_PAGINATION,
            'renderer' => '',
            'scroll_collapse' => false,
            'search_delay' => 0,
            'state_duration' => 7200,
            'stripe_classes' => array(),
            'class' => Style::BOOTSTRAP_3_STYLE,
            'individual_filtering' => false,
            'individual_filtering_position' => 'head',
            'use_integration_options' => true,
            'force_dom' => false,
            'row_id' => 'id'
        ));

        $this->columnBuilder
            ->add('fechaHoras', 'datetime', array(
                'title' => 'Fecha',
                'editable' => true,
                'date_format' => 'll'
            ))            
            ->add('actividad.nombre', 'column', array(
                'title' => 'Actividad Nombre',
            ))
            ->add('cliente.razonSocial', 'column', array(
                'title' => 'Cliente',
            ))
            ->add('horasInvertidas', 'column', array(
                'title' => 'Horas Invertidas',
                'editable' => true
            ))
            ->add('horasExtraordinarias', 'boolean', array(
                'title' => 'Horas Extraordinarias',
                'editable' => true,
                'true_label' => 'Sí',
                'false_label' => 'No'
            ))
            ->add('aprobado', 'boolean', array(
                'title' => 'Aprobado',
                'editable' => true,
                'true_label' => 'Sí',
                'false_label' => 'No'
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AppBundle\Entity\RegistroHoras';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'registrohoras_datatable';
    }
}
