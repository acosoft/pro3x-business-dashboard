<?php

namespace Pro3x\Online\Fina;

class PoslovniProstorZahtjev
{
	private $Zaglavlje;
	private $PoslovniProstor;
	
	function __construct($uuid)
	{
		$this->Zaglavlje = new Zaglavlje($uuid);
		$this->PoslovniProstor = new PoslovniProstor;
	}

	/**
	 * 
	 * @return Zaglavlje
	 */
	public function getZaglavlje()
	{
		return $this->Zaglavlje;
	}

	public function setZaglavlje($zaglavlje)
	{
		$this->Zaglavlje = $zaglavlje;
	}
	
	/**
	 * 
	 * @return PoslovniProstor
	 */
	public function getPoslovniProstor()
	{
		return $this->PoslovniProstor;
	}

	public function setPoslovniProstor($PoslovniProstor)
	{
		$this->PoslovniProstor = $PoslovniProstor;
	}


}

?>
