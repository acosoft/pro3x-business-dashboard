<?php

namespace Pro3x\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pro3x\SecurityBundle\Form\GroupType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/groups")
 */
class GroupController extends Controller
{
	/**
	 * @Route("/add", name="add_group")
	 * @Security("has_role('ROLE_GROUP_ADMIN')")
	 * @Template("::edit.html.twig")
	 */
	public function addAction()
	{
		$form = $this->createForm(new GroupType());
		if($result = $this->persist($form, 'Grupa je uspješno kreirana')) return $result;
		
		return array('form' => $form->createView(), 'title' => 'Unos nove grupe', 'icon' => 'group_add', 'cssClass' => 'pro3x_small_icon_group_add');
	}
	
	private function persist($form, $message)
	{
		if($this->getRequest()->isMethod('post'))
		{
			$form->bind($this->getRequest());
			
			if($form->isValid())
			{
				$manager = $this->getDoctrine()->getEntityManager();
				$manager->persist($form->getData());
				$manager->flush();
				
				$this->addFlash('message', $message);
				return $this->redirect($this->getRequest()->get('back'));
			}
		}
		
		return false;
	}
	
	/**
	 * @Route("/edit/{id}", name="edit_group")
	 * @Security("has_role('ROLE_GROUP_ADMIN')")
	 * @Template("::edit.html.twig")
	 */
	public function editAction()
	{
		$id = $this->getRequest()->get('id');
		$group = $this->getDoctrine()->getRepository('Pro3xSecurityBundle:Group')->findOneById($id);
		
		if(!$group) $this->createNotFoundException ('Tražena grupa ne postoji');

		$form = $this->createForm(new GroupType(), $group);
		if($result = $this->persist($form, 'Grupa je uspješno izmjenjena')) return $result;
		
		return array('form' => $form->createView(), 'title' => 'Izmjena grupe', 'icon' => 'group_add', 'cssClass' => 'pro3x_small_icon_group_edit');
	}
	
	/**
	 * @Route("/delete/{id}", name="delete_group")
	 * @Security("has_role('ROLE_GROUP_ADMIN')")
	 * @Template()
	 */
	public function deleteAction()
	{
		$id = $this->getRequest()->get('id');
		$group = $this->getDoctrine()->getRepository('Pro3xSecurityBundle:Group')->findOneById($id);
		if(!$group) $this->createNotFoundException ('Tražena grupa ne postoji');
		
		$manager = $this->getDoctrine()->getEntityManager();
		$manager->remove($group);
		$manager->flush();
		
		$this->addFlash('message', 'Grupa je uspješno izbrisana');
		return $this->redirect($this->getRequest()->get('back'));
	}
	
	/**
	 * @Route("/", name="list_groups")
	 * @Security("has_role('ROLE_GROUP_ADMIN')")
	 * @Template()
	 */
	public function listAction()
	{
		$groups = $this->getDoctrine()->getRepository('Pro3xSecurityBundle:Group')->findAll();
		return array('groups' => $groups, 'icon' => 'group');
	}
}

?>
