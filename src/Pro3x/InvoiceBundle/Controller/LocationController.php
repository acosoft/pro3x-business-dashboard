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
	 * @Template("Pro3xInvoiceBundle:Location:edit.html.twig")
	 */
	public function addAction()
	{
		$handler = new \Pro3x\Online\AddHandler($this);
		
		$handler->setTitle('Izmjena lokacije')
				->setIcon('location_add')
				->setSuccessMessage('Lokacija je uspješno spremljena')
				->setFormType(new LocationType());
		
		$result = $handler->execute();
		
		if($this->getRequest()->isMethod('POST'))
		{
			$id = $handler->getForm()->getData()->getId();
			$this->finaLocation($id);
		}
		
		return $result;
	}
	
	private function finaLocation($id)
	{
		if($this->getRequest()->isMethod('POST'))
		{
			try
			{
				$location = $this->getLocationRepository()->find($id); /* @var $location \Pro3x\InvoiceBundle\Entity\Location */
				
				$soap = $this->getFinaClientFactory()->createInstance($location->getSecurityKey(), $location->getSecurityCertificate(), array('trace' => true));
				$zahtjev = new \Pro3x\Online\Fina\PoslovniProstorZahtjev($soap->randomGuid());

				$prostor = $zahtjev->getPoslovniProstor();
				$prostor->setOib($location->getCompanyTaxNumber());
				$prostor->setOznPoslProstora($location->getName());
				$prostor->setRadnoVrijeme($location->getWorkingHours());


				$adresa = new \Pro3x\Online\Fina\Adresa();
				$adresa->setBrojPoste($location->getPostalCode());
				$adresa->setKucniBroj($location->getHouseNumber());
				$adresa->setKucniBrojDodatak($location->getHouseNumberExtension());
				$adresa->setNaselje($location->getSettlement());
				$adresa->setOpcina($location->getCity());
				$adresa->setUlica($location->getStreet());

				$prostor->setAdresniPodatak($adresa);

				$pocetak = new \DateTime('now');
				$zahtjev->getPoslovniProstor()->setDatumPocetkaPrimjene($pocetak->format('d.m.Y'));

				$result = $soap->poslovniProstor($zahtjev);
				
				$this->setMessage('Podaci o poslovnom prostoru su uspješno spremljeni i prijavljeni na Finu');
				$location->setSubmited(true);
			}
			catch (\Exception $exc)
			{
				$this->setWarning('Servisi porezne uprave nisu dostupni, lokacija nije prijavljena');
				$location->setSubmited(false);
			}
			
			$manager = $this->getDoctrine()->getEntityManager();
			$manager->persist($location);
			$manager->flush();
		}
	}
	
	/**
	 * @Route("/edit/{id}", name="edit_location")
	 * @Template()
	 */
	public function editAction($id)
	{
		$handler = new EditHandler($this, $id);
		
		$handler->setIcon('location_edit')
				->setTitle('Izmjena lokacije')
				->setSuccessMessage('Lokacija je uspješno izmjenjena')
				->setRepository($this->getLocationRepository())
				->setFormType($form = new LocationType());
		
		$result = $handler->execute();
		
		$this->finaLocation($id);
		
		return $result;
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
