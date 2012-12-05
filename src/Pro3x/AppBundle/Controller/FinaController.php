<?php

namespace Pro3x\AppBundle\Controller;

use Pro3x\AppBundle\Controller\AdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api")
 */
class FinaController extends AdminController
{
	public function getSoapClient()
	{
		return new \Pro3x\Online\FinaClient('-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEArGyl0YiemQZ7PVmTtuhX3KHjShCTB+YjN58dx0/dTaOREVZb
dTZJanSnxQGfbYLwWazsHZMy5TIJ6TA4XR8yabl34yxAyMeE6YY01ezBRoEwnfei
GSy3VadE69V4vGYEbQ6rp+whaHub0p6Ru1h0wmSmMlxfujOZRBKi1DWw7mBu+3zo
IJJi7DIgIkAP7z8UX/VGWSPF3PNNt8DUuna8+551jcSRYJdcGmAMYD/owiCKbP9B
qDLWrd1t7IUCcyjDkiIlUvMLEr3FZVkrCcUQPM79p5Cei5U1aB5enuUlx4z7u5FJ
/StNp7JiOooqTJu/atB+QF5aWhmMx9IDCH6m7QIDAQABAoIBAG6kUUtBhbPgSti1
UGpAcZDsePMf65lX/iVE/1DUWMfnO8GKTqnKKhYbwURTpEAbk6uXoPPhbvlPwLhX
7vMif7EECIBH91JfZVSMf/7+K4/YeqADNYs9/G8EJN/IXe9W72Qj09avWUi4eOcQ
RaeYPWQ4PcV3LR5I2gKqir3trhlnzfuKcygzDDDodp8Xar4O6IUeDWAUp27KSjG9
k+kny8/xqVNKOIuF94BaoAC3sxixrUmGa2ln5owpykriYP0pt6DRCUYWL4rvUSH4
NBKCtpCGd09dsmmGOm+YfPXrx1QKQ5fr6OjApUBIAWXap8Fm5mfa3DMdWhNjH7cj
mrlTnYECgYEA3n6Aye07qifk+w5brm50eNv3PQRe5Ni1DG5VrjFuMsjqRwgrXJPR
pih/RrTedVCRKJKUH2loeKLMP3gz/M+n1UYx/KqAx34RCBluJ1mqXjMmTQV2pMgn
Xk9t9YJD5qRvldJvNCSnshLS+NXQqFF3Q6FxIDRqPHKybMPJ/CDldj0CgYEAxmPg
si560VX7Ng1Xgcs+G0QPEOcu7LUxmXHKqgTXRkz313dR9c8VGLR95fLr9w0Qe/Le
CLc6YEI3jcE4g88lZU1iBGgeLLvllVGbksxxRrinULJWKDotqr6gViD4xqf+Syxz
ZVsBrZH95j9DK7v2n0WJLOWngW24aN5IB+W4rnECgYEAzPdRAbSQYIJ8CWQCxcBv
f99z9jwOh1e0Ag2q1NwonpREbsdx2sk6O43XInYA8aYU84GE/yMF4QxoiJfdnKpX
/Lu71P3lwOG8Iu/6cDnEHx+TyySbVdYlnlYiACfPEayuNyEy3KBf9EYvIJkorcbO
zfjt1DM1IxaulFARxTkw6ikCgYEAmIjwmW89RTi7pqqJbqUrAmHIx7FIlf0WvqkM
Lr78GfRR1tvKK5nl2ZHk/ulQ3imwU+y/JKpkeuBOwHIgls+tK/9cbpDzKmfptG4D
lNIWAk81bclAjzt0I0TgGHjPbkZ6MahirTpUxvRp6B8Z8UVa8MjRGLEWomAPGqpV
baUF4zECgYAOv7d1by+G/bXXMEQLZUpJHY7kTar9nS4iVnHJpMllgQ5qErZx7pGX
nViSBDlNnLZ6KtLALek49CO4OinvO34O2Q8HpextouHwY/3eXhL9rXu+7w/FS1Lk
ZcYZXqgKCWrzF7sYI1XQzK6WgNqBEJm/Nm3FQs5c8/DrcsKmB+d+lg==
-----END RSA PRIVATE KEY-----', 'Bag Attributes
    localKeyID: 01 00 00 00 
subject=/C=HR/O=MALI ZELENI D.O.O. HR61543907467/L=RIJEKA/CN=FISKAL 1
issuer=/C=HR/O=FINA/OU=DEMO
-----BEGIN CERTIFICATE-----
MIIE4TCCA8mgAwIBAgIEPssjAjANBgkqhkiG9w0BAQUFADArMQswCQYDVQQGEwJI
UjENMAsGA1UEChMERklOQTENMAsGA1UECxMEREVNTzAeFw0xMjEyMDExMjEzMjFa
Fw0xNDEyMDExMjQzMjFaMFwxCzAJBgNVBAYTAkhSMSkwJwYDVQQKEyBNQUxJIFpF
TEVOSSBELk8uTy4gSFI2MTU0MzkwNzQ2NzEPMA0GA1UEBxMGUklKRUtBMREwDwYD
VQQDEwhGSVNLQUwgMTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAKxs
pdGInpkGez1Zk7boV9yh40oQkwfmIzefHcdP3U2jkRFWW3U2SWp0p8UBn22C8Fms
7B2TMuUyCekwOF0fMmm5d+MsQMjHhOmGNNXswUaBMJ33ohkst1WnROvVeLxmBG0O
q6fsIWh7m9KekbtYdMJkpjJcX7ozmUQSotQ1sO5gbvt86CCSYuwyICJAD+8/FF/1
RlkjxdzzTbfA1Lp2vPuedY3EkWCXXBpgDGA/6MIgimz/Qagy1q3dbeyFAnMow5Ii
JVLzCxK9xWVZKwnFEDzO/aeQnouVNWgeXp7lJceM+7uRSf0rTaeyYjqKKkybv2rQ
fkBeWloZjMfSAwh+pu0CAwEAAaOCAdowggHWMAsGA1UdDwQEAwIFoDBCBgNVHSAE
OzA5MDcGCSt8iFAFHwUDATAqMCgGCCsGAQUFBwIBFhxodHRwOi8vZGVtby1wa2ku
ZmluYS5oci9jcHMvMB8GA1UdEQQYMBaBFElpbmZvQG1hbGktemVsZW5pLmhyMIHO
BgNVHR8EgcYwgcMwQqBAoD6kPDA6MQswCQYDVQQGEwJIUjENMAsGA1UEChMERklO
QTENMAsGA1UECxMEREVNTzENMAsGA1UEAxMEQ1JMOTB9oHugeYZPbGRhcDovL2Rl
bW8tbGRhcC5maW5hLmhyL291PURFTU8sbz1GSU5BLGM9SFI/Y2VydGlmaWNhdGVS
ZXZvY2F0aW9uTGlzdCUzQmJpbmFyeYYmaHR0cDovL2RlbW8tcGtpLmZpbmEuaHIv
Y3JsL2RlbW9jYS5jcmwwKwYDVR0QBCQwIoAPMjAxMjEyMDExMjEzMjFagQ8yMDE0
MTIwMTEyNDMyMVowHwYDVR0jBBgwFoAUemAjjkidMmuk5S3duFm0lPxCYp4wHQYD
VR0OBBYEFDzAWRqLeFcd1Dk7Ut5+1j31nDQ3MAkGA1UdEwQCMAAwGQYJKoZIhvZ9
B0EABAwwChsEVjguMQMCA6gwDQYJKoZIhvcNAQEFBQADggEBAHJjD4nFnBgFWczr
qW4jzh84s/Z0q5n/fVTKgN8MQsAnmDB8urFUjjtTFY2FU6hzlMa7LSKuXExfLcm9
MZo2xPiddoFBqnFFqrGOrfS9pqo28t1GlSK1ffDv2XqUdTHONXMA+Uk7kY/hGI3w
5rqHnZf2KA+TYzxe5kYrwZkRzv+31gkMLA5N4UfDDSiiuhtzCyAcCQ3wPC9sv7eH
ksxUDoWCnMsOVM7ujljdN9iOV/XqMwF6AMzubk1s1HSBRFMyIkepw+glncvJJZM3
oN7gRSd3bu6t4J57X12aFJyYTbkCJN+3ocdAKhdaabQZrljHcAs5w0iqW6zUJxqC
tHsYoXY=
-----END CERTIFICATE-----', 'http://localhost/pro3x/fina/wsdl/FiskalizacijaService.wsdl', array('trace' => true));
	}
	/**
	 * @Route("/echo", name="fina_echo")
	 */
	public function echoAction()
	{	
		$result = array();
		
		try
		{
			$result[] = "radi";
			$soap = $this->getSoapClient();
			
			//$result[] = $soap->echo('Hello Fina!');
			$result[] = $soap->test('Hello Fina!');


			$result[] = $soap->__getLastRequestHeaders();
			$result[] = $soap->__getLastRequest();
			$result[] = $soap->__getLastResponse();
			$result[] = "client pozvan";
		}
		catch (\Exception $exc)
		{
			$result[] = $exc->getTraceAsString();
		}



		return new \Symfony\Component\HttpFoundation\Response(implode("\n\n", $result));
	}
	
	/**
	 * @Route("/location", name="fina_location")
	 */
	public function locationAction()
	{
		$result = array();
		$soap = $this->getSoapClient();
		
		$zahtjev = new \Pro3x\Online\Fina\PoslovniProstorZahtjev($soap->randomGuid());
		
		$prostor = $zahtjev->getPoslovniProstor();
		$prostor->setOib('61543907467');
		$prostor->setOznPoslProstora('Pehlin');
		$prostor->setRadnoVrijeme('od 10 do 12h');
		
		$adresa = new \Pro3x\Online\Fina\Adresa();
		$adresa->setBrojPoste('51000');
		$adresa->setKucniBroj('7');
		$adresa->setKucniBrojDodatak('c');
		$adresa->setNaselje('Pehlin');
		$adresa->setOpcina('Rijeka');
		$adresa->setUlica('Baretićevo');
		
		$prostor->setAdresniPodatak($adresa);
		
		$pocetak = new \DateTime();
		$pocetak->setDate(2012, 12, 15);
		
		$zahtjev->getPoslovniProstor()->setDatumPocetkaPrimjene($pocetak->format('d.m.Y'));
		
		
		$data = $soap->poslovniProstor($zahtjev);
		//$result[] = $data->Zaglavlje->IdPoruke;
		//$result[] = $data->Zaglavlje->DatumVrijeme;
		$result[] = $data->getIdPoruke();
		$result[] = $data->getDatumVrijeme()->format('d.m.Y');
		$result[] = $data->getDatumVrijeme()->format('H:i:s');
		
		$result[] = $soap->__getLastRequest();
		$result[] = $soap->__getLastResponse();

		return new \Symfony\Component\HttpFoundation\Response(implode("\n\n", $result));
	}
	
	/**
	 * @Route("/invoice/{id}", name="fina_invoice")
	 * @Template()
	 */
	public function invoiceAction($id)
	{
		$soap = $this->getSoapClient();
		$invoice = $this->getInvoiceRepository()->find($id); /* @var $invoice \Pro3x\InvoiceBundle\Entity\Invoice */
		
		if(!$invoice->getUuid())
		{
			$invoice->setUuid($soap->randomGuid());
			
			$manager = $this->getDoctrine()->getEntityManager();
			$manager->persist($invoice);
			$manager->flush();
		}
		
		$zahtjev = new \Pro3x\Online\Fina\RacunZahtjev($invoice->getUuid());
		
		$racun = $zahtjev->getRacun();
		$racun->setOib('61543907467');
		$racun->setUSustPdv(true);
		$racun->setDatVrijeme($invoice->getCreated()->format('d.m.Y\TH:i:s'));
		$racun->setOznSlijed('N');
		
		$oznaka = $racun->getBrRac();
		$oznaka->setBrOznRac($invoice->getSequence());
		$oznaka->setOznPosPr($invoice->getPosition()->getLocationName());
		$oznaka->setOznNapUr($invoice->getPosition()->getName());

		$map = array('pdv' => array(), 'pot' => array());
		
		$invoice->setNumeric($this->getNumeric());
		foreach($invoice->getTaxItems() as $item) /* @var $item \Pro3x\InvoiceBundle\Entity\InvoiceItemTax */
		{
			$porez = new \Pro3x\Online\Fina\Porez();
			
			$porez->setOsnovica(number_format(round($item['baseNumeric'], 2, PHP_ROUND_HALF_DOWN), 2));
			$porez->setStopa(number_format($item['rateNumeric'], 2));
			$porez->setIznos(number_format(round($item['amountNumeric'], 2, PHP_ROUND_HALF_DOWN), 2));
			
			$map[$item['group']][] = $porez;
		}
		
		if(count($map['Pdv']) > 0)
			$racun->setPdv($map['Pdv']);
		
		if(count($map['Pnp']) > 0)
			$racun->setPnp ($map['Pnp']);
		
		$racun->setIznosUkupno($invoice->getTotal());
		$racun->setNacinPlac('G');
		$racun->setOibOper($invoice->getUser()->getOib());
		$racun->setNakDost(false);
		
		
		$data = $soap->racuni($zahtjev); /* @var $data \Pro3x\Online\Fina\RacunOdgovor */
		
		if($data instanceof \Pro3x\Online\Fina\RacunOdgovor && !$invoice->getUniqueInvoiceNumber())
		{
			$invoice->setUniqueInvoiceNumber($data->getJir());
			
			$manager = $this->getDoctrine()->getEntityManager();
			$manager->persist($invoice);
			$manager->flush();
		}
		
		$result = array();
		
		$result[] = $soap->__getLastRequest();
		$result[] = $soap->__getLastResponse();
		
		return new \Symfony\Component\HttpFoundation\Response(implode("\n\n", $result));
		
//		return new \Symfony\Component\HttpFoundation\JsonResponse(array(
//			'total' => $invoice->getTotal(),
//			'jir' => $data->getJir(),
//			'oznaka' => $data->getIdPoruke(),
//			'datum' => $data->getDatumVrijeme()->format('d.m.Y H:i'),
//		));
	}
}

?>
