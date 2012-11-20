<?php

namespace Pro3x\SecurityBundle\Controller;

use Pro3x\AppBundle\Controller\AdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Pro3x\SecurityBundle\Form\UserType;
use Pro3x\SecurityBundle\Entity\User;

/**
 * @Route("/admin/users")
 */
class UserController extends AdminController
{
	/**
	 * @Route("/delete/{id}", name="delete_user")
	 */
	public function deleteAction()
	{
		$id = $this->getRequest()->get('id', null);
		
		$manager = $this->getDoctrine()->getEntityManager();
		$user = $manager->getRepository('Pro3xSecurityBundle:User')->findOneById($id);
		
		if(!$user) 
			$this->createNotFoundException ('Korisnik ne postoji');
		
		/* @var $user \Pro3x\SecurityBundle\Entity\User */
		$manager->remove($user);
		$manager->flush();
		
		$this->get('session')->setFlash('message', 'Korisnik je uspješno izbrisan');
		return $this->redirect($this->getRequest()->get('back'));
	}

	/**
	 * @Route("/edit/{id}", name="edit_user")
	 * @Template()
	 */
	public function editAction()
	{
		$id = $this->getRequest()->get('id');
		$manager = $this->getDoctrine()->getEntityManager();
		$user = $manager->getRepository('Pro3xSecurityBundle:User')->findOneById($id); /* @var $user User */
		
		if(!$user) $this->createNotFoundException ('Korisnik ne postoji');
		
		if($this->getRequest()->isMethod('get'))
		{
			$form = $this->createForm(new UserType(), $user);
		}
		else if($this->getRequest()->isMethod('post'))
		{
			$form = $this->createForm(new UserType());
			$form->bind($this->getRequest());

			$data = $form->getData(); /* @var $data User */
			
			if($form->isValid())
			{
				if($data->getPassword())
				{
					$encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
					$encodedPassword = $encoder->encodePassword($data->getPassword(), $user->getSalt());
					$user->setPassword($encodedPassword);
				}
				
				$user->setActive($data->isActive());
				$user->setEmail($data->getEmail());
				$user->setOib($data->getOib());
				$user->setUsername($data->getUsername());
				
				$manager->persist($user);
				$manager->flush();
				
				$this->get('session')->setFlash('message', 'Informacije o korisniku su uspješno izmjenjene');
				
				return $this->redirect($this->getRequest()->get('back'));
			}
		}
		
		return array('form' => $form->createView(), 'mode' => 'edit');
	}
	
	/**
	 * @Route("/add", name="add_user")
	 * @Template("Pro3xSecurityBundle:User:edit.html.twig")
	 */
	public function addAction()
	{
		$form = $this->createForm(new UserType(), $user = new User());
		
		if($this->getRequest()->isMethod('post'))
		{
			$form->bind($this->getRequest());

			if($form->isValid())
			{
				$manager = $this->getDoctrine()->getEntityManager();
				
				$encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
				$encodedPassword = $encoder->encodePassword($user->getPassword(), $user->getSalt());
				$user->setPassword($encodedPassword);
				
				$manager->persist($user);
				$manager->flush();
				
				$this->get('session')->setFlash('message', 'Podaci o novom korisniku su uspješno spremljeni u bazu');
				
				return $this->redirect($this->getRequest()->get('back'));
			}
		}
		
		return array('form' => $form->createView(), 'mode' => 'add');
	}
	
	/**
	 * @Route("/{page}", name="users_list", defaults={"page" = 1})
	 * @Template()
	 */
	public function listAction()
	{
		$page = $this->getRequest()->get('page', 1);
		
		$query = $this->getDoctrine()->getEntityManager()->createQueryBuilder()->select('u')
				->from('Pro3xSecurityBundle:User', 'u')
				->orderBy('u.username')
				->setMaxResults($this->getPageSize())
				->setFirstResult($this->getPageOffset($page))
				->getQuery();
		
		$pager = new Paginator($query);
		
		$count = $this->getPageCount($pager->count());
		
		return array('users' => $pager, 'count' => $count, 'page' => $page);
	}
}

?>
