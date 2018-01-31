<?php

namespace UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UserBundle\Entity\Permiso;

class InsertPermissionsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('user:permissions')//sintaxis del comando
            ->setDescription('Insert base permissions to database.')//descripcion

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

        $cantidadPermisos = count(Permiso::PERMISOS_ACTUALES); //contador todos los permisos a insertar

        //Mostrar Progreso de todos los usuarios evaluados
        $progress = new ProgressBar($output, $cantidadPermisos);
        $progress->start();

        foreach (Permiso::PERMISOS_ACTUALES as $etiqueta => $permiso) {
            $permiso = new Permiso($etiqueta, $permiso);
            $em->persist($permiso);
            //aumentar la cantidad de permisos creados
            ++$cantidadPermisos;

            $progress->advance();
        }
        $em->flush();
        //terminar barra de progreso
        $progress->finish();

        $output->writeln('Cantidad de permisos creados'.$cantidadPermisos);
    }
}
