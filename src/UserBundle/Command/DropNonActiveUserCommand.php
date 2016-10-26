<?php

namespace UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

class DropNonActiveUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fos:user:drop:nonactivated')//sintaxis del comando
            ->setDescription('Drop all non activated users.')//descripcion

        ;
    }
    /**
     * Método que ejecuta el comando.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return string con los nombres de los usuarios que han sido eliminados
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //se utiliza el manager de Symfony
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        //se utiliza el manager de FOS
        $um = $this->getContainer()->get('fos_user.user_manager');

        //buscar todos los usuarios
        $usuarios = $em->getRepository('UserBundle:Usuario')->findAll();

        $UsuariosEliminados = ' ';
        $cantidadUsuarios = count($usuarios); //contador todos los usuarios a evaluar

        //Mostrar Progreso de todos los usuarios evaluados
        $progress = new ProgressBar($output, $cantidadUsuarios);
        $progress->start();

        foreach ($usuarios as $usuario) {
            //si no ha sido confirmado esta variable es NULL
            if (is_null($usuario->getLastLogin())) {
                $um->deleteUser($usuario); //eliminar Usuario

                $UsuariosEliminados = $UsuariosEliminados.' '.$usuario->getUsername().'\n';
            }

            $progress->advance();
        }
        //terminar barra de progreso
        $progress->finish();

        $output->writeln($UsuariosEliminados);
    }
}
