<?php

namespace Pro3x\InvoiceBundle\Controller;

use Pro3x\AppBundle\Controller\AdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pro3x\InvoiceBundle\Form\CustomerType;
use Pro3x\InvoiceBundle\Entity\Customer;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * @Route("/admin/customers")
 */
class CustomerController extends AdminController
{
	/**
	 * @Route("/add", name="add_client")
	 * @Template("Pro3xInvoiceBundle:Customer:edit.html.twig")
	 */
	public function addAction()
	{
		$form = $this->createForm(new CustomerType());
		if($result = $this->saveForm($form, 'Novi kupac je uspješno kreiran')) return $result;

		return array('form' => $form->createView(), 'title' => 'Unos novog kupca', 'cssClass' => 'pro3x_small_icon_client_add');
	}
	
	/**
	 * @Route("/edit/{id}", name="edit_client")
	 * @Template("Pro3xInvoiceBundle:Customer:edit.html.twig")
	 */
	public function editAction($id)
	{
		$client = $this->getCustomerRepository()->findOneById($id);
		$this->redirect404($client);
		
		$form = $this->createForm(new CustomerType(), $client);
		if($result = $this->saveForm($form, 'Informacije o kupcu su uspješno izmijenjene')) return $result;
		
		return $this->editParams($form, 'Izmjena kupca', 'client_edit');
	}
	
	/**
	 * @Route("/delete/{id}", name="delete_client")
	 * @Template()
	 */
	public function deleteAction()
	{
		return $this->deleteEntity($this->getCustomerRepository(), 'Kupac je uspješno izbrisan');
	}
	
	/**
	 * @Route("/relation/add/{id}", name="customer_relations")
	 * @Secure(roles="ROLE_RELATIONS")
	 * @Template()
	 */
	public function relationsAction($id)
	{
		$customer = $this->getCustomerRepository()->find($id);
		$relations = $this->getRelationTypeRepository()->findAll();
		
		return array('customer' => $customer, 'relations' => $relations);
	}
	
	/**
	 * @Route("/select/{owner}/{page}", name="select_customer", defaults={"page"=1})
	 * @Template("Pro3xInvoiceBundle:Customer:select.html.twig")
	 * @Secure(roles="ROLE_RELATIONS")
	 */
	public function selectAction($owner, $page)
	{
		$owner = $this->getCustomerRepository()->find($owner);
		$clients = $this->getCustomerRepository()->findBy(array(), array('name' => 'ASC'), $this->getPageSize());
		$relation = $this->getRelationTypeRepository()->find($this->getParam('relation'));
		
		return array('owner' => $owner, 'relation' => $relation,'clients' => $clients, 'pagerParams' => array('owner' => $owner->getId(), 'relation' => $relation->getId()), 'search' => '', 'count' => $this->getCustomerRepository()->getCount(), 'page' => $page);
	}
	
	/**
	 * @Route("/selectBy/{owner}/{relation}/{page}", name="select_by_customer", defaults={"page"=1})
	 * @Template("Pro3xInvoiceBundle:Customer:select.html.twig")
	 * @Secure(roles="ROLE_RELATIONS")
	 */
	public function selectByAction($owner, $relation, $page)
	{
		$search = $this->getParam('search', '');
		$offset = $this->getPageOffset($page);
		$pageSize = $this->getPageSize();
		
		$owner = $this->getCustomerRepository()->find($owner);
		$relation = $this->getRelationTypeRepository()->find($relation);
		$result = $this->getCustomerRepository()->findBySearchQuery($search, $offset, $pageSize);
		
		$pageCount = $this->getPageCount($result['count']);
		
		return array('owner' => $owner, 'relation' => $relation, 'clients' => $result['items'], 'search' => $search, 'pagerParams' => array('owner' => $owner->getId(), 'relation' => $relation->getId(), 'search' => $search), 'count' => $pageCount, 'page' => $page);
	}
	
	/**
	 * @Secure(roles="ROLE_RELATIONS")
	 * @Route("/relations/add/{owner}/{related}/{relation}", name="add_customer_relation")
	 * @Template()
	 */
	public function addRelationAction($owner, $related, $relation)
	{
		$ownerCustomer = $this->getCustomerRepository()->find($owner);
		$relatedCustomer = $this->getCustomerRepository()->find($related);
		$relationType = $this->getRelationTypeRepository()->find($relation);
		
		$relation = new \Pro3x\InvoiceBundle\Entity\CustomerRelation();
		
		$relation->setOwner($ownerCustomer);
		$relation->setRelated($relatedCustomer);
		$relation->setRelationType($relationType);
		
		$manager = $this->getDoctrine()->getEntityManager();
		$manager->persist($relation);
		$manager->flush();

		$this->setMessage("Relacija je uspješno spremljena");
		return $this->goBack();
	}
	
	/**
	 * @Route("/relation/delete/{id}", name="delete_relation")
	 * @Template()
	 * @Secure(roles="ROLE_RELATIONS")
	 */
	public function deleteRelationAction($id)
	{
		$relation = $this->getRelationsRepository()->find($id);
		
		$manager = $this->getDoctrine()->getEntityManager();
		$manager->remove($relation);
		$manager->flush();

		$this->setMessage("Relacija je uspješno izbrisana");
		
		return $this->goBack();
	}
	
	/**
	 * @Route("/notes/{id}", name="customer_notes")
	 * @Secure(roles="ROLE_NOTES")
	 * @Template()
	 */
	public function notesAction($id)
	{
		$customer = $this->getCustomerRepository()->find($id);
		return array('customer' => $customer);
	}
	
	/**
	 * @Route("/notes/add/{id}", name="add_customer_note")
	 * @Secure(roles="ROLE_NOTES")
	 * @Template("::edit.html.twig")
	 */
	public function addCustomerNoteAction($id)
	{
		$note = new \Pro3x\InvoiceBundle\Entity\Note();
		$note->setCustomer($this->getCustomerRepository()->find($id));
		
		$handler = new \Pro3x\Online\AddHandler($this, $note);
		
		$handler->setTitle("Dodaj Bilješku")
				->setIcon("add_note")
				->setSuccessMessage("Bilješka je uspješno spremljena")
				->setFormType(new \Pro3x\InvoiceBundle\Form\NoteType());
		
		return $handler->execute();
	}
	
	/**
	 * @Route("/notes/delete/{id}", name="delete_customer_note")
	 * @Secure(roles="ROLE_NOTES")
	 * @Template()
	 */
	public function deleteCustomerNoteAction()
	{
		return $this->deleteEntity($this->getNoteRepository(), 'Bilješka je uspješno izbrisana');
	}
	
	/**
	 * @Route("/notes/edit/{id}", name="edit_customer_note")
	 * @Template("::edit.html.twig")
	 * @Secure(roles="ROLE_NOTES")
	 */
	public function editCustomerNoteAction($id)
	{
		$handler = new \Pro3x\Online\EditHandler($this, $id);

		$handler->setTitle("Izmjena Bilješke")
				->setIcon("edit_note")
				->setSuccessMessage("Bilješka je uspješno izmjenjena")
				->setRepository($this->getNoteRepository())
				->setFormType(new \Pro3x\InvoiceBundle\Form\NoteType());
		
		return $handler->execute();
	}
	
	/**
     * @Route("/{page}", name="clients", defaults={"page" = 1})
     * @Template()
     */
    public function indexAction($page)
    {
		if($this->isPost())
		{
			return $this->redirect($this->generateUrl('clients', array('qsearch' => $this->getParam('search'))));
		}
		else if($this->getParam('qsearch') != '')
		{
			$search = $this->getParam('qsearch', '');
			$offset = $this->getPageOffset($page);
			$pageSize = $this->getPageSize();
			
			$result = $this->getCustomerRepository()->findBySearchQuery($search, $offset, $pageSize);
			
			$pageCount = $this->getPageCount($result['count']);
			
			return array('clients' => $result['items'], 'search' => $search, 'pagerParams' => array('qsearch' => $search), 'count' => $pageCount, 'page' => $page);
		}
		else
		{
			$search = "";
			$clients = $this->getCustomerRepository()->findBy(array(), array('name' => 'ASC'), $this->getPageSize(), $this->getPageOffset($page));
			$count = $this->getPageCount($this->getCustomerRepository()->getCount());
			
			return array('clients' => $clients, 'search' => $search, 'count' => $count, 'page' => $page);
		}        
    }
}
