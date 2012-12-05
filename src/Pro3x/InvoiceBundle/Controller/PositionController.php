<?php

namespace Pro3x\InvoiceBundle\Controller;

use Pro3x\AppBundle\Controller\AdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Pro3x\Online\TableParams;
use Pro3x\InvoiceBundle\Form\PositionType;
use Pro3x\Online\EditHandler;


/**
 * @Route("/admin/pos")
 */
class PositionController extends AdminController
{
	/**
	 * @Route("/add", name="add_position")
	 * @Template("::edit.html.twig")
	 */
	public function addAction()
	{
		$handler = new \Pro3x\Online\AddHandler($this);
		
		$handler->setTitle('Izmjena blagajne')
				->setIcon('position_add')
				->setSuccessMessage('Blagajna je uspješno spremljena')
				->setFormType(new PositionType());
		
		return $handler->execute();
	}
	
	/**
	 * @Route("/edit/{id}", name="edit_position")
	 * @Template("::edit.html.twig")
	 */
	public function editAction($id)
	{
		$handler = new EditHandler($this, $id);
		
		$handler->setIcon('position_edit')
				->setTitle('Izmjena blagajne')
				->setSuccessMessage('Blagajna je uspješno izmjenjena')
				->setRepository($this->getPositionRepository())
				->setFormType(new PositionType());
		
		return $handler->execute();
	}
	
	/**
	 * @Route("/delete/{id}", name="delete_position")
	 * @Template()
	 */
	public function deleteAction()
	{
		return $this->deleteEntity($this->getPositionRepository(), 'Blagajna je uspješno izbrisana');
	}
	
	
	/**
	 * @Route("/", name="positions")
	 * @Template("::table.html.twig")
	 */
	public function positionsAction()
	{
		$items = $this->getPositionRepository()->createQueryBuilder('c')->select()->orderBy('c.location')->addOrderBy('c.name')->getQuery()->getResult();
		$params = new TableParams();

		$params->setTitle('Pregled blagajni')
				->setIcon('position')
				->addColumn('locationName', 'Naziv lokacije')
				->addColumn('name', 'Naziv blagajne')
				->addColumn('sequence', 'Sljedeći račun')
				->setDeleteType('poziciju')
				->setDeleteColumn('name')
				->setRoutes('position')
				->setItems($items)
				->setPagerVisible(false);
				
		
		return $params->getParams();
	}
}

?>
