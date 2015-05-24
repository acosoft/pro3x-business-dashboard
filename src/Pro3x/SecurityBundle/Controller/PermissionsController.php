<?php

namespace Pro3x\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pro3x\SecurityBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class PermissionsController extends Controller
{
	/**
	 * @Route("/admin/user/permisions/{id}", name="edit_permissions")
	 * @Security("has_role('ROLE_PERMISSION_ADMIN')")
	 * @Template()
	 */
	public function editAction()
	{
		$id = $this->getRequest()->get('id');
		$manager = $this->getDoctrine()->getEntityManager();
		$user = $manager->getRepository('Pro3xSecurityBundle:User')->findOneById($id); /* @var $user User */
		
		if(!$user) return $this->createNotFoundException();
		$groups = $manager->getRepository('Pro3xSecurityBundle:Group')->findAll();
		
		if($this->getRequest()->isMethod('post'))
		{
			$user->getGroups()->clear();
			
			$newGroups = $this->getRequest()->get('groups', array());
			foreach($groups as $group)
			{
				if(in_array($group->getId(), $newGroups))
				{
					$user->getGroups()->add($group);
				}
			}

			$manager->persist($user);
			$manager->flush();
			
			$this->addFlash('message', 'Dozvole korisnika su uspješno izmijenjene');
			return $this->redirect($this->getRequest()->get('back'));
		}
		
		return array('user' => $user, 'groups' => $groups, 'icon' => 'user');
	}
}

?>
