<?php

namespace Pro3x\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @Route("/admin/users")
 */
class SecurityController extends Controller
{
	private function getPageSize()
	{
		return 3;
	}
	
	private function getPageOffset($page)
	{
		return ($page - 1) * $this->getPageSize();
	}
	
	private function getPageCount($itemCount)
	{
		return ceil($itemCount / $this->getPageSize());
	}
	
	/**
	 * @Route("/list/{page}", name="users_list", defaults={"page" = 1})
	 * @Secure(roles="ROLE_ADMIN")
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
}

?>
