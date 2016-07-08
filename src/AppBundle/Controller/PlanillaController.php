<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
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
	 * @param  Request $request [description]
	 * @return [type]           [description]
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
        $hoja = $hoja - 1 ;//arreglar index.
        $archivo = $planilla;
		$phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($archivo);
		$worksheet = $phpExcelObject->getSheet($hoja);
		$etiquetas = false;
		$usuarios = [];
		foreach($worksheet->getRowIterator() as $row => $columns) {
			$cellIterator = $columns->getCellIterator();
			$columna = 0;
			$usuario = new UsuarioPlanilla();
		    foreach($cellIterator as $cell) {
		       // $worksheet->setCellValueByColumnAndRow($column, $row + 1, $data);
		    	$data = $cell->getValue();
	      	
		      	if (strtolower($data) == 'codigo') {
		      		$etiquetas = true;
		      	}
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
		        if ($etiquetas === false && $data !== null ) {
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
		        			$usuario->setNombre();
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
		        $columna++;
	        }
	        $usuarios[] = $usuario;
		}
		$this->crearUsuarios($usuarios);
	}


	private function crearUsuarios($usuarios)
	{
		foreach($usuarios as $usuario) {
			$em = $this->getDoctrine()->getManager();
    		//buscar usuario por código.
    		$usuarioTrabajador = $em->getRepository('UserBundle:UsuarioTrabajador')->findBy([
    			'codigo' => $usuario->getCodigo()
    		]);
    		if (is_null($dbUsuario)) {
    			//hay que crear el usuario;
    			$usuarioTrabajador = new UsuarioTrabajador();
    			$usuarioTrabajador->setNombre($usuario->getNombre());
    			$usuarioTrabajador->setApellido($usuario->getApellido());
    			$codigo = $em->getRepository('UserBundle:Codigo')->findBy([
    				'codigo'=> $usuario->getCodigo()
    			]);
    			if (is_null($codigo)) {
    				$codigo = new Codigo();
    				$codigo->setCodigo($usuario->getCodigo());
    				$codigo->setNombres($usuario->getNombre());
    				$codigo->setApellidos($usuario->getApellido());
    				$em->persist($codigo);
    				$em->flush();

    			}
    			$usuarioTrabajador->setCodigo($codigo);
    			$em->persist($usuarioTrabajador);
    			$em->flush();
    		}
    		//ahora los datos de las prestaciones
    		$datosPrestaciones = new DatosPrestaciones();
    		
		}
	}
	/**
	 * Método para validar las etiquetas de las columnas
	 * @param  string $data valor de la fila
	 * @return  boolean 
	 */
	private function validarEtiquetas($data)
	{
		$primeraFila = [
			"codigo",
			"departamento",
			"primer apellido",
			"segundo apellido",
			"primer nombre",
			"segundo nombre",
			"base",
			"dias",
			"bonificacion",
			"otras bonif",
			"deprec",
			"gas",
			"ingresos",
			"igss",
			"aguinaldo",
			"corpoin",
			"comcel",
			"prest",
			"isr",
			"otros des",
			"valens",
			"liquido",
			""
		];
		return $this->revisar_substring($primeraFila, strtolower($data));
	}

	private function revisar_substring($needle, $haystack) 
	{
	    foreach ($needle as $name) {
	        if (stripos($haystack, $name) !== FALSE) {
	            return true;
	        }
	    }
	    return false;
	}
}