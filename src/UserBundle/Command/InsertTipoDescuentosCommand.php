<?php

namespace UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use UserBundle\Entity\TipoDescuento;

class InsertTipoDescuentosCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('user:tipo:descuento')//sintaxis del comando
            ->setDescription('Insert base tipos descuentos to database.')//descripcion

        ;
    }
    /**
     * Método que ejecuta el comando.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return string con la cantidad de permisos creados
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //se utiliza el manager de Symfony
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $cantidadDescuentos = count(TipoDescuento::tipos); //contador todos los permisos a insertar

        //Mostrar Progreso de todos los usuarios evaluados
        $progress = new ProgressBar($output, $cantidadDescuentos);
        $progress->start();
        //initialize count
        $cantidadDescuentos = 0;
        foreach (TipoDescuento::tipos as $nombre) {
            $tipoDescuento = new TipoDescuento($nombre);
            $em->persist($tipoDescuento);
            //aumentar la cantidad de permisos creados
            ++$cantidadDescuentos;

            $progress->advance();
        }
        $em->flush();
        //terminar barra de progreso
        $progress->finish();

        $output->writeln('Cantidad de descuentos creados'.$cantidadDescuentos);
    }
}
