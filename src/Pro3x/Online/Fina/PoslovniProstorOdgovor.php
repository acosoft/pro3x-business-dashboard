<?php

namespace Pro3x\Online\Fina;

class PoslovniProstorOdgovor
{
	/**
	 *
	 * @var Zaglavlje
	 */
	private $Zaglavlje;

	public function getIdPoruke()
	{
		return $this->Zaglavlje->getIdPoruke();
	}
	
	/**
	 * 
	 * @return \DateTime
	 */
	public function getDatumVrijeme()
	{
		return \DateTime::createFromFormat('d.m.Y\TH:i:s', $this->Zaglavlje->getDatumVrijeme());
	}
}

?>
