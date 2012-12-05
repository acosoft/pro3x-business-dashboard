<?php

namespace Pro3x\Online\Fina;

class RacunZahtjev
{
	private $Zaglavlje;
	private $Racun;
	
	function __construct($idPoruke = null)
	{
		$this->Racun = new Racun();
		$this->Zaglavlje = new Zaglavlje($idPoruke);
	}

	/**
	 * 
	 * @return Zaglavlje
	 */
	public function getZaglavlje()
	{
		return $this->Zaglavlje;
	}

	public function setZaglavlje($Zaglavlje)
	{
		$this->Zaglavlje = $Zaglavlje;
	}

	/**
	 * 
	 * @return Racun
	 */
	public function getRacun()
	{
		return $this->Racun;
	}

	public function setRacun($Racun)
	{
		$this->Racun = $Racun;
	}
}

?>
