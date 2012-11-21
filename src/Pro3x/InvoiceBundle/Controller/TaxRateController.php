<?php

namespace Pro3x\InvoiceBundle\Controller;

use Pro3x\AppBundle\Controller\AdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pro3x\InvoiceBundle\Form\TaxRateType;
use Pro3x\Online\TableParams;

/**
 * @Route("/admin/tax-rates")
 */
class TaxRateController extends AdminController
{
	/**
	 * @Route("/add", name="add_tax_rate")
	 * @Template("::edit.html.twig")
	 */
	public function addAction()
	{
		$form = $this->createForm(new TaxRateType);
		if($result = $this->saveForm($form, 'Nova porezna stopa je uspješno spremljena')) return $result;

		return $this->editParams($form, 'Nova porezna stopa', 'tax');
	}
	
	/**
	 * @Route("/edit/{id}", name="edit_tax_rate")
	 * @Template("::edit.html.twig")
	 */
	public function editAction($id)
	{
		$tax = $this->getTaxRateRepository()->findOneById($id);
		$this->redirect404($tax);
		
		$form = $this->createForm(new TaxRateType(), $tax);
		if($result = $this->saveForm($form, 'Izmjenjena porezna stopa je uspješno spremljena')) return $result;
		
		return $this->editParams($form, 'Izmjena porezne stope', 'tax');
	}
	
	/**
	 * @Route("/delete/{id}", name="delete_tax_rate")
	 * @Template()
	 */
	public function deleteAction()
	{
		return $this->deleteEntity($this->getTaxRateRepository(), 'Porezna stopa je uspješno izbrisana');
	}
	
	/**
     * @Route("/{page}", name="tax_rates", defaults={"page" = 1})
     * @Template("::table.html.twig")
     */
    public function indexAction($page)
    {
		$params = new TableParams();
		
		return $params->setTitle('Popis poreznih stopa')
				->setIcon('tax')
				
				->addColumn('name')
				->addColumn('rate')
				
				->setAddRoute('add_tax_rate')
				->setEditRoute('edit_tax_rate')
				->setDeleteRoute('delete_tax_rate')
				->setDeleteColumn('name')
				->setDeleteType('poreznu stopu')
				
				->setPager(false)
				->setItems($this->getTaxRateRepository()->findAll())->getParams();
    }
}
