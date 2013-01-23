<?php

namespace Pro3x\Online\Fina;

class RacunOdgovor
{
	private $Zaglavlje;
	private $Jir;
	private $Greske;
			
	/**
	 * 
	 * @return Greska
	 */
	public function getGreska()
	{
		return $this->Greske;
	}

	public function setGreska($Greska)
	{
		$this->Greske = $Greska;
	}

	/**
	 * 
	 * @return Pro3x\Online\Fina\Zaglavlje
	 */
	public function getZaglavlje()
	{
		return $this->Zaglavlje;
	}

	public function setZaglavlje($Zaglavlje)
	{
		$this->Zaglavlje = $Zaglavlje;
	}

	public function getJir()
	{
		return $this->Jir;
	}

	public function setJir($Jir)
	{
		$this->Jir = $Jir;
	}
	
	public function getIdPoruke()
	{
		return $this->getZaglavlje()->getIdPoruke();
	}
	
	/**
	 * 
	 * @return DateTime
	 */
	public function getDatumVrijeme()
	{
		return $this->getZaglavlje()->getDatumVrijeme();
	}
}

?>
