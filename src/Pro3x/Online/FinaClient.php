<?php

namespace Pro3x\Online;

include_once 'xmlseclibs.php';

class FinaClient extends \SoapClient
{
	private $key;
	private $certificate;
	private $node;
	
	function __construct($key, $certificate, $wsdl, $options)
	{
		$options['exceptions'] = true;
		$options['cache_wsdl'] = WSDL_CACHE_BOTH;
		
		$options['classmap'] = array(
			'PoslovniProstorOdgovor'	=> '\Pro3x\Online\Fina\PoslovniProstorOdgovor',
			'ZaglavljeOdgovorType'		=> '\Pro3x\Online\Fina\Zaglavlje',
			'RacunZahtjev'				=> '\Pro3x\Online\Fina\RacunZahtjev',
			'RacunOdgovor'				=> '\Pro3x\Online\Fina\RacunOdgovor',
			'GreskaType'				=> '\Pro3x\Online\Fina\Greska',
		);
		
		//TODO: this is a workaround for SOAP-ERROR and XDEBUG problems
		if(function_exists('xdebug_disable')) xdebug_disable();
		
		parent::__construct($wsdl, $options);
		
		$this->key = new \XMLSecurityKey(\XMLSecurityKey::RSA_SHA1, array('type' => 'private'));
		$this->key->loadKey($key);
		
		$this->certificate = $certificate;
	}
	
	public function randomGuid()
	{
		return \XMLSecurityDSig::generate_GUID('');
	}
	
	public function __doRequest($request, $location, $action, $version, $one_way = 0)
	{
		if($this->node != null)
		{
			$document = new \DOMDocument();
			$document->loadXML($request);

			$node = $document->getElementsByTagNameNS('http://www.apis-it.hr/fin/2012/types/f73', $this->node)->item(0);

			$sign = new \XMLSecurityDSig();
			$sign->setCanonicalMethod(\XMLSecurityDSig::EXC_C14N);
			$sign->addReference($node, \XMLSecurityDSig::SHA1, array('http://www.w3.org/2000/09/xmldsig#enveloped-signature'));

			$sign->sign($this->key);
			$sign->add509Cert($this->certificate);

			$sign->appendSignature($node);
			$request = $document->saveXML();
		}
		
		return parent::__doRequest($request, $location, $action, $version, $one_way);
	}
	
	/**
	 * 
	 * @param Fina\RacunZahtjev $racun
	 * @return Fina\RacunOdgovor
	 */
	public function racuni(Fina\RacunZahtjev $zahtjev)
	{
		$this->node = 'RacunZahtjev';
		
		$racun = $zahtjev->getRacun();
		
		$temp = $racun->getOib();
		$temp .= \DateTime::createFromFormat('d.m.Y\TH:i:s', $racun->getDatVrijeme())->format('d.m.Y H:i:s');
		$temp .= $racun->getBrRac()->getBrOznRac();
		$temp .= $racun->getBrRac()->getOznPosPr();
		$temp .= $racun->getBrRac()->getOznNapUr();
		$temp .= $racun->getIznosUkupno();
		
		$sign = new \XMLSecurityDSig();
		$potpis = $sign->signData($this->key, $temp);
		
		$racun->setZastKod(md5($potpis));
		
		$data = $this->__soapCall('racuni', array($zahtjev));
		
		/* @var $data Fina\RacunOdgovor */
		if($data instanceof Fina\RacunOdgovor && $data->getGreska())
		{
			$greska = $data->getGreska()->Greska; /* @var $greska Fina\Greska */
			throw new \Exception($greska->getPorukaGreske());
		}
		else if($data instanceof Fina\RacunOdgovor)
		{
			return $data;
		}
		else
		{
			throw new \Exception("Nepoznata iznimka u komunikaciji sa servisima");
		}
	}
	
	/**
	 * 
	 * @param \Pro3x\Online\Fina\PoslovniProstorZahtjev $zahtjev
	 * @return Fina\PoslovniProstorOdgovor
	 */
	public function poslovniProstor(Fina\PoslovniProstorZahtjev $zahtjev)
	{
		$this->node = 'PoslovniProstorZahtjev';
		$data = $this->__soapCall('poslovniProstor', array($zahtjev));
		
		return $data;
	}
	
	public function test($message)
	{
		$this->node = null;
		return $this->__soapCall('echo', array($message));
	}
}

?>
