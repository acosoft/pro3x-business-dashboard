<?php

namespace Pro3x\AppBundle\Controller;

use Pro3x\AppBundle\Controller\AdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api")
 */
class ApiController extends AdminController
{
	/**
	 * @Route("/invoice/{id}", name="api_get_invoice")
	 * @Template()
	 */
	public function nameAction($id)
	{
		
		$data = $this->getRequest()->getContent();
		return new \Symfony\Component\HttpFoundation\Response(json_encode(array('hello' => $id, 'data' => $data), JSON_FORCE_OBJECT));
	}
}

?>
