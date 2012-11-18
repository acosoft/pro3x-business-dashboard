<?php

namespace Pro3x\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * @Route("/admin/users")
 */
class SecurityController extends Controller
{
	/**
	 * @Route("/list", name="users_list")
	 * @Secure(roles="ROLE_ADMIN")
	 * @Template()
	 */
	public function listAction()
	{
		return array('title' => 'Radi ko urica : )');
	}
}

?>
