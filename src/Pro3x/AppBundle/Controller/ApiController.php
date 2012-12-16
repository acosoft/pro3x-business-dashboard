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
	 * 
	 * @param \Pro3x\SecurityBundle\Entity\User $user
	 * @return string
	 */
	private function getToken($user)
	{
		return $user->getId();
	}
	
	public function decode($data)
	{
		return $data;
	}
	
	public function encode($data)
	{
		return $data;
	}
	
	/**
	 * @Route("/login", name="api_login")
	 */
	public function loginAction()
	{
		$data = $this->decode($this->getRequest()->getContent());
		
		$username = $data->username;
		$password = $data->password;
		
		$user = $this->getUserRepository()->findOneByName($username); /* @var $user \Pro3x\SecurityBundle\Entity\User */
		
		//TODO: exception handlin when user doesn't exist
		
		$response = array('fullName' => $user->getDisplayName(),
			'cashRegister' => $position,
			'token' => $this->getToken($user));
		
		return new \Symfony\Component\HttpFoundation\Response(json_encode($response));
	}
}

?>
