<?php

namespace Pro3x\Online\Fina;

class Porez
{
	private $Stopa;
	private $Osnovica;
	private $Iznos;
	
	public function getStopa()
	{
		return $this->Stopa;
	}

	public function setStopa($Stopa)
	{
		$this->Stopa = $Stopa;
	}

	public function getOsnovica()
	{
		return $this->Osnovica;
	}

	public function setOsnovica($Osnovica)
	{
		$this->Osnovica = $Osnovica;
	}

	public function getIznos()
	{
		return $this->Iznos;
	}

	public function setIznos($Iznos)
	{
		$this->Iznos = $Iznos;
	}
}

?>
