<?php

namespace Pro3x\InvoiceBundle\Controller;

use Pro3x\AppBundle\Controller\AdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Pro3x\Online\TableParams;
use Pro3x\InvoiceBundle\Form\LocationType;
use Pro3x\Online\EditHandler;
use Pro3x\InvoiceBundle\Form\TemplateType;


/**
 * @Route("/admin/templates")
 */
class TemplateController extends AdminController
{
	/**
	 * @Route("/add", name="add_template")
	 * @Template("::edit.html.twig")
	 */
	public function addAction()
	{
		$handler = new \Pro3x\Online\AddHandler($this);
		
		$handler->setTitle('Izmjena predloška')
				->setIcon('template_add')
				->setSuccessMessage('Predložak je uspješno spremljen')
				->setFormType(new TemplateType());
		
		return $handler->execute();
	}
	
	/**
	 * @Route("/edit/{id}", name="edit_template")
	 * @Template("::edit.html.twig")
	 */
	public function editAction($id)
	{
		$handler = new EditHandler($this, $id);
		
		$handler->setIcon('template_edit')
				->setTitle('Izmjena predloška')
				->setSuccessMessage('Predložak je uspješno izmjenjen')
				->setRepository($this->getTemplateRepository())
				->setFormType(new TemplateType());
		
		return $handler->execute();
	}
	
	/**
	 * @Route("/delete/{id}", name="delete_template")
	 * @Template()
	 */
	public function deleteAction()
	{
		return $this->deleteEntity($this->getTemplateRepository(), 'Predložak je uspješno izbrisan');
	}
	
	
	/**
	 * @Route("/", name="templates")
	 * @Template("::table.html.twig")
	 */
	public function locationsAction()
	{
		$items = $this->getTemplateRepository()->createQueryBuilder('c')
				->join('c.location', 'l')
				->orderBy('l.id', 'ASC')
				->addOrderBy('c.priority', 'DESC')->getQuery()->getResult();
				
				//findBy(array(), array('location' => 'ASC', 'priority' => 'DESC'));
		$params = new TableParams();

		$params->setTitle('Pregled predložaka')
				->setIcon('template')
				->addColumn('locationName', 'Naziv lokacije')
				->addColumn('name', 'Naziv predloška')
				->addColumn('priority', 'Prioritet')
				->addColumn('useGoogleCloudFormated', "Način ispisa")
				->setDeleteType('predložak')
				->setDeleteColumn('name')
				->setRoutes('template')
				->setItems($items)
				->setPagerVisible(false);
				
		
		return $params->getParams();
	}
}

?>
