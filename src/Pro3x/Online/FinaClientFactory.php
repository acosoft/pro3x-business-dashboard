<?php

namespace Pro3x\Online;

class FinaClientFactory
{
	private $wsdl;
	
	function __construct($wsdl)
	{
		$this->wsdl = $wsdl;
	}
	
	public function getWsdlUrl()
	{
		return $this->wsdl;
	}

	/**
	 * 
	 * @param string $key
	 * @param string $certificate
	 * @param array $options
	 * @return \Pro3x\Online\FinaClient
	 */
	public function createInstance($key, $certificate, $options = array())
	{
		return new FinaClient($key, $certificate, $this->wsdl, $options);
	}
	
	public function isFiscalTransaction($transactionType)
	{
		//TODO: only cash invoices are sent to fina at the moment
		if($transactionType == 'G' || $transactionType == 'K')
			return true;
		else
			return false;
	}
}

?>
