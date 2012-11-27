<?php

namespace Pro3x\InvoiceBundle\Controller;

use Pro3x\AppBundle\Controller\AdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pro3x\InvoiceBundle\Form\CustomerType;

/**
 * @Route("/admin/customers")
 */
class CustomerController extends AdminController
{
	/**
	 * @Route("/add", name="add_client")
	 * @Template("::edit.html.twig")
	 */
	public function addAction()
	{
		$form = $this->createForm(new CustomerType());
		if($result = $this->saveForm($form, 'Novi kupac je uspješno kreiran')) return $result;

		return array('form' => $form->createView(), 'title' => 'Unos novog klijenta', 'cssClass' => 'pro3x_small_icon_client_add');
	}
	
	/**
	 * @Route("/edit/{id}", name="edit_client")
	 * @Template("::edit.html.twig")
	 */
	public function editAction($id)
	{
		$client = $this->getCustomerRepository()->findOneById($id);
		$this->redirect404($client);
		
		$form = $this->createForm(new CustomerType(), $client);
		if($result = $this->saveForm($form, 'Informacije o kupcu su uspješno izmijenjene')) return $result;
		
		return $this->editParams($form, 'Izmjena klijenta', 'client_edit');
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
     * @Route("/{page}", name="clients", defaults={"page" = 1})
     * @Template()
     */
    public function indexAction($page)
    {
		$clients = $this->getCustomerRepository()->findBy(array(), array('name' => 'ASC'), $this->getPageSize(), $this->getPageOffset($page));
        return array('clients' => $clients, 'count' => $this->getPageCount($this->getCustomerRepository()->getCount()), 'page' => $page);
    }
}
