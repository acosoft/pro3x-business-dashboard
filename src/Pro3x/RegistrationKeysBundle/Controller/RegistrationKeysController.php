<?php

namespace Pro3x\RegistrationKeysBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pro3x\RegistrationKeysBundle\Entity\RegistrationKey;
use Pro3x\RegistrationKeysBundle\Form\RegistrationKeyType;
use Pro3x\AppBundle\Controller\AdminController;
use Pro3x\Online\TableParams;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/registration-keys")
 */
class RegistrationKeysController extends AdminController
{
    /**
     * @Route("/add", name="add_registration_key")
     * @Template("::edit.html.twig")
     */
    public function addAction()
    {
		$handler = new \Pro3x\Online\AddHandler($this, new RegistrationKey());
		
		$handler->setTitle("Izmjena registracijskog ključa")
				->setIcon("position_add")
				->setSuccessMessage("Registracijski ključe je uspješno spremljen.")
				->setFormType(new RegistrationKeyType());
		
        return $handler->execute();
    }
	
	/**
	 * @Route("/edit/{id}", name="edit_registration_key")
	 * @Template("::edit.html.twig")
	 */
	public function editAction($id)
	{
		$handler = new \Pro3x\Online\EditHandler($this, $id);
		
		$handler->setIcon('position_edit')
				->setTitle('Izmjena registracijskog ključa')
				->setSuccessMessage('Registracijski ključe je uspješno izmjenjen')
				->setRepository($this->getRegistrationKeysRepository())
				->setFormType(new RegistrationKeyType());
		
		return $handler->execute();
	}
	
	/**
	 * @Route("/download/{id}", name="download_registration_key")
	 */
	public function downloadAction($id)
	{
		$reg = $this->getRegistrationKeysRepository()->find($id); /* @var $reg RegistrationKey */
		
		$filename = 'registracijski-kljuc-' . $reg->getId() . '-' . str_replace(' ', '-', strtolower($reg->getProduct()->getName())) . ".xml";
		
		$document = new \DOMDocument();
		$document->appendChild($licence = $document->createElement("licence"));
		
		$signatureParts = array();
		$signatureParts['pid'] = $reg->getProduct()->getBarcode();
		$signatureParts['pcode'] = $reg->getProduct()->getName();
		$signatureParts['refno'] = $reg->getId();
		$signatureParts['firstname'] = '';
		$signatureParts['lastname'] = '';
		$signatureParts['company'] = $reg->getCustomer()->getName();
		$signatureParts['email'] = $reg->getCustomer()->getEmail();
		$signatureParts['country'] = '';
		$signatureParts['city'] = '';
		$signatureParts['zipcode'] = '';
		$signatureParts['validfrom'] = $reg->getValidFromFormated();
		$signatureParts['validto'] = $reg->getValidToFormated();
		
		foreach ($signatureParts as $key => $value)
		{
			$licence->appendChild($document->createElement(strtolower($key), $value));
		}

		$source = "// " . implode(" // ", $signatureParts) . " //";
		$sha1signature = sha1($source, true);
		$signature = base64_encode($sha1signature);
		
		$password = md5($reg->getProduct()->getName(), true);
		
		$blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
		$padding = $blockSize - (strlen($signature) % $blockSize);
		$signature .= str_repeat(chr($padding), $padding);
		
		$encryptedSignature = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $password, $signature, MCRYPT_MODE_ECB);
		$base64encryptedSignature = base64_encode($encryptedSignature);
		
		$licence->appendChild($document->createElement("signature", $base64encryptedSignature));
		
		//default options
		$licence->appendChild($options = $document->createElement("options"));
		$options->appendChild($document->createElement('maloprodaja', 'true'));
		$options->appendChild($document->createElement('userMode', 'false'));
		$options->appendChild($document->createElement('ispisRacuna', 'true'));
		$options->appendChild($document->createElement('skraceniKodovi', 'false'));
		$options->appendChild($document->createElement('izmjenaSekvenci', 'true'));
		$options->appendChild($document->createElement('stanjeSkladista', 'true'));
		$options->appendChild($document->createElement('tempTasks', 'false'));
		$options->appendChild($document->createElement('brisanjeKartice', 'false'));
		$options->appendChild($document->createElement('skriveniNormativi', 'true'));
		$options->appendChild($document->createElement('koristiNormativeArtikala', 'true'));
		$options->appendChild($document->createElement('koristiMigracijuArtikala', 'false'));
		$options->appendChild($document->createElement('koristiZapisnikePromjeneCijena', 'false'));
		$options->appendChild($document->createElement('koristiTehnickuPodrsku', 'false'));
		$options->appendChild($document->createElement('koristiGotovinskeTransakcije', 'false'));
		$options->appendChild($document->createElement('koristiUsporedivanjeSkladista', 'false'));
		$options->appendChild($document->createElement('koristiDeklaracije', 'false'));
		$options->appendChild($document->createElement('koristiPopupEditorCijena', 'true'));
		$options->appendChild($document->createElement('koristiIzracunOstatka', 'true'));
		$options->appendChild($document->createElement('koristiDisplayZaKupca', 'false'));
		$options->appendChild($document->createElement('poslinkDisplayZaKupca'));			
		
		return new Response($document->saveXML(), 200, array('Content-type' => "text/xml",
					'Content-Disposition' => 'attachment; filename=' . $filename));
//		return new Response($document->saveXML(), 200, array('Content-type' => "text/xml"));
	}
	
	/**
	 * @Route("/delete/{id}", name="delete_registration_key")
	 * @Template()
	 */
	public function deleteAction()
	{
		return $this->deleteEntity($this->getRegistrationKeysRepository(), 'Registracijski ključ je uspješno izbrisan');
	}
	
	/**
	 * @Route("/", name="registation_keys")
	 * @Template("::table.html.twig")
	 */
	public function indexAction()
	{
		$items = $this->getRegistrationKeysRepository()->createQueryBuilder('c')->select()->orderBy('c.validTo', 'DESC')->getQuery()->getResult();
		$params = new TableParams();

		$params->setTitle('Pregled registracijskih ključeva')
				->setIcon('position')
				->addColumn('id', 'Kljuc')
				->addColumn('customerDescription', 'Kupac')
				->addColumn('productDescription', 'Proizvod')
				->addColumn('validToFormated', 'Vrijedi do')
				->setDeleteType('registracijski ključ')
				->setDeleteColumn('id')
				->setRoutes('registration_key')
				->setItems($items)
				->setPagerVisible(false)
				->setToolsTemplate('Pro3xRegistrationKeysBundle:RegistrationKeys:tools.html.twig');
				
		return $params->getParams();
	}
}
