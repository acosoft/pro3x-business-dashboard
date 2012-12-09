<?php

namespace Pro3x\InvoiceBundle\Controller;

use Pro3x\AppBundle\Controller\AdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Pro3x\Online\TableParams;
use Pro3x\InvoiceBundle\Form\LocationType;
use Pro3x\Online\EditHandler;


/**
 * @Route("/admin/locations")
 */
class LocationController extends AdminController
{
	/**
	 * @Route("/add", name="add_location")
	 * @Template("::edit.html.twig")
	 */
	public function addAction()
	{
		$handler = new \Pro3x\Online\AddHandler($this);
		
		$handler->setTitle('Izmjena lokacije')
				->setIcon('location_add')
				->setSuccessMessage('Lokacija je uspješno spremljena')
				->setFormType(new LocationType());
		
		return $handler->execute();
	}
	
	/**
	 * @Route("/edit/{id}", name="edit_location")
	 * @Template("::edit.html.twig")
	 */
	public function editAction($id)
	{
		$handler = new EditHandler($this, $id);
		
		$handler->setIcon('location_edit')
				->setTitle('Izmjena lokacije')
				->setSuccessMessage('Lokacija je uspješno izmjenjena')
				->setRepository($this->getLocationRepository())
				->setFormType(new LocationType());
		
		return $handler->execute();
	}
	
	/**
	 * @Route("/delete/{id}", name="delete_location")
	 * @Template()
	 */
	public function deleteAction()
	{
		return $this->deleteEntity($this->getLocationRepository(), 'Lokacija je uspješno izbrisana');
	}
	
	
	/**
	 * @Route("/", name="locations")
	 * @Template("::table.html.twig")
	 */
	public function locationsAction()
	{
		$items = $this->getLocationRepository()->findAll();
		$params = new TableParams();

		$params->setTitle('Pregled lokacija')
				->setIcon('location')
				->addColumn('name', 'Naziv Lokacije')
				->addColumn('description', 'Adresa')
				->addColumn('workingHours', 'Radno vrijeme')
				->setDeleteType('lokaciju')
				->setDeleteColumn('name')
				->setRoutes('location')
				->setItems($items)
				->setPagerVisible(false);
				
		
		return $params->getParams();
	}
}

?>
