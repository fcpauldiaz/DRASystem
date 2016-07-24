<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Form\Type\PlanillaType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use UserBundle\Entity\UsuarioPlanilla;
use UserBundle\Entity\UsuarioTrabajador;
use UserBundle\Entity\Codigo;
use UserBundle\Entity\DatosPrestaciones;

/**
 * Codigo controller.
 *
 * @Security("is_granted('ROLE_USER')")
 * @Route("/planilla")
 */
class PlanillaController extends Controller
{
    /**
     * @Route("/excel", name ="planilla_excel")
     * Método que valida planilla en formato excel
     *
     * @param Request $request
     *
     * @return Response
     */
    public function recibirExcel(Request $request)
    {
        $usuario = $this->get('security.token_storage')->getToken()->getUser();
        if (!is_object($usuario) || !$usuario instanceof UserInterface) {
            throw new AccessDeniedException('El usuario no tiene acceso.');
        }

        $form = $this->createForm(
            PlanillaType::class);

        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->render(
                'AppBundle:Planilla:newPlanilla.html.twig',
                [
                    'form' => $form->createView(),
                ]
            );
        }
        $data = $form->getData();
        $planilla = $data['planilla'];
        $hoja = $data['hoja'];
        $hoja = $hoja - 1;//arreglar index.
        $archivo = $planilla;
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($archivo);
        $worksheet = $phpExcelObject->getSheet($hoja);
        $etiquetas = false;
        $usuarios = [];
        foreach ($worksheet->getRowIterator() as $row => $columns) {
            $cellIterator = $columns->getCellIterator();
            $columna = 0;
            $usuario = new UsuarioPlanilla();
            foreach ($cellIterator as $cell) {
                // $worksheet->setCellValueByColumnAndRow($column, $row + 1, $data);
                $data = $cell->getValue();
                $returnArray = $this->validarExcel($data, $usuario, $columna, $etiquetas);
                $usuario = $returnArray[0];
                $columna = $returnArray[1];
            }
            if ($usuario->getCodigo() !== null) {
                $usuarios[] = $usuario;
            }
        }
        //Crear usuarios que no existen
        $this->crearUsuarios($usuarios);
    }
    /**
     * Método para crear los usuarios que no existen 
     * o ingresar nuevos datos de prestaciones.
     *
     * @param  $usuarios ya pre-validados
     *
     * @return $usuarios con los valores
     */
    private function crearUsuarios($usuarios)
    {
        foreach ($usuarios as $usuario) {
            $em = $this->getDoctrine()->getManager();
            //buscar usuario por código.
            $dbUsuario = $this->queryCodigoUsuario($usuario->getCodigo());

            //Si no existe el usuario
            if (is_null($dbUsuario)) {

                //hay que crear el usuario;
                $usuarioTrabajador = new UsuarioTrabajador();
                $usuarioTrabajador->setNombre($usuario->getNombre());
                $usuarioTrabajador->setApellidos($usuario->getApellido());
                $codigo = $em->getRepository('UserBundle:Codigo')->findBy([
                    'codigo' => $usuario->getCodigo(),
                ]);
                $codigo = $codigo[0];
                if (is_null($codigo)) {
                    $codigo = new Codigo();
                    $codigo->setCodigo($usuario->getCodigo());
                    $codigo->setNombres($usuario->getNombre());
                    $codigo->setApellido($usuario->getApellido());
                    $em->persist($codigo);
                    $em->flush();
                }

                $usuarioTrabajador->setCodigo($codigo);
                $encoder = $this->container->get('security.password_encoder');
                //encriptar contraseña.
                $encoded = $encoder->encodePassword($usuarioTrabajador, 'smart-time');
                $usuarioTrabajador->setPassword($encoded);
                $username = str_replace(' ', '', strtolower($usuario->getApellido()));
                $usuarioTrabajador->setUsername($username);
                //puede generar probelmas de email unique
                $usuarioTrabajador->setEmail($username.'@diazreyes.com');
                $usuarioTrabajador->setUserImage('578ae8d025164_default-user-icon-profile.png');
                $em->persist($usuarioTrabajador);
                $em->flush();
            }
            //ahora los datos de las prestaciones
            $prestaciones = new DatosPrestaciones();
            $prestaciones->setSueldo(
                $usuario->getBase()
            )
            ->setBonificacionIncentivo(
                $usuario->getBonificacion()
            )
            ->setOtraBonificacion(
                $usuario->getOtraBonificacion()
            )
            ->setGasolina(
                $usuario->getGasolina()
            )
            ->setOtrasPrestaciones(
                $usuario->getCorporacion()
            )
            //falta ver que se hace con 
            //corporacion y comcel
            ;
        }
    }
    /**
     * Método para validar las etiquetas de las columnas.
     *
     * @param string $data valor de la fila
     *
     * @return bool
     */
    private function validarEtiquetas($data)
    {
        $primeraFila = [
            'codigo',
            'departamento',
            'primer apellido',
            'segundo apellido',
            'primer nombre',
            'segundo nombre',
            'base',
            'dias',
            'bonificacion',
            'otras bonif',
            'deprec',
            'gas',
            'ingresos',
            'igss',
            'aguinaldo',
            'corpoin',
            'comcel',
            'prest',
            'isr',
            'otros des',
            'valens',
            'liquido',
            '',
        ];

        return $this->revisar_substring($primeraFila, strtolower($data));
    }

    /**
     * Método para validar etiquetas del excel
     * Y guardar los usuarios no existentes.
     *
     * @param   $data    valor de la celda.
     * @param   $usuario
     * @param   $columna
     *
     * @return $usuario pre-creado     
     */
    private function validarExcel($data, $usuario, $columna, $etiquetas)
    {    
        //condición para la primera columna
        if (strtolower($data) == 'codigo') {
            $etiquetas = true;
        }
        //condición para la última columna
        if (strtolower($data) == 'liquido') {
            $etiquetas = false;
        }
          //validar etiquetas
        if ($etiquetas === true && $data !== null) {
            if ($this->validarEtiquetas($data) === false) {
                $this->addFlash('error', 'Excel subido con formato inválido');
                $this->addFlash('error', 'La siguiente etiqueta no es válida');
                $this->addFlash('error', $data);

                return $this->render(
                        'AppBundle:Planilla:newPlanilla.html.twig',
                        [
                            'form' => $form->createView(),
                        ]
                    );
            }
        }
        //lógica
        if ($etiquetas === false && $data !== null) {
            //codigo

            switch ($columna) {
                case 0:
                    $usuario->setCodigo($data);
                    break;
                case 1:
                    $usuario->setDepartamento($data);
                    break;
                case 2:
                    $usuario->setApellido($data);
                    break;
                case 3:
                    $usuario->setApellido($usuario->getApellido().' '.$data);
                    break;
                case 4:
                    $usuario->setNombre($data);
                    break;
                case 5:
                    $usuario->setNombre($usuario->getNombre().' '.$data);
                    break;
                case 6:
                    $usuario->setBase($data);
                    break;
                case 7:
                    $usuario->setDias($data);
                    break;
                case 8:
                    $usuario->setBonificacion($data);
                    break;
                case 9:
                    $usuario->setOtraBonificacion($data);
                    break;
                case 10:
                    $usuario->setDepreciacion($data);
                    break;
                case 11:
                    $usuario->setGasolina($data);
                    break;
                case 12:
                    $usuario->setIngresos($data);
                    break;
                case 13:
                    $usuario->setIgss($data);
                    break;
                case 14:
                    $usuario->setAguinaldo($data);
                    break;
                case 15:
                    $usuario->setCorporacion($data);
                    break;
                case 16:
                    $usuario->setComcel($data);
                    break;
                case 17:
                    $usuario->setIsr($data);
                    break;
                case 18:
                    $usuario->setOtrosDescuentos($data);
                    break;
                case 19:
                    $usuario->setPrestaciones($data);
                    break;
                case 20:
                    $usuario->setValens1($data);
                    break;
                case 21:
                    $usuario->setValens2($data);
                    break;
                case 22:
                    $usuario->setValens3($data);
                    break;
                case 23:
                    $usuario->setLiquido($data);
                    break;
            }
        }
        ++$columna;

        return [$usuario, $columna];
    }

    /**
     * Query para retornar el usuario relacionado a un código.
     *
     * @param  $codigo 
     *
     * @return $usuario
     */
    private function queryCodigoUsuario($codigo)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryUsuario = $em->getRepository('UserBundle:UsuarioTrabajador');
        $qb = $repositoryUsuario->createQueryBuilder('usuario')
            ->leftJoin('usuario.codigo', 'code')
            ->where('code.codigo = :codigoUsuario')
            ->setParameter('codigoUsuario', $codigo);

        return $qb->getQuery()->getOneOrNullResult();
    }

    private function revisar_substring($needle, $haystack)
    {
        foreach ($needle as $name) {
            if (stripos($haystack, $name) !== false) {
                return true;
            }
        }

        return false;
    }
}
