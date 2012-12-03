<?php

namespace Pro3x\Online\Fina;

class Adresa
{
	private $Ulica;
	private $KucniBroj;
	private $KucniBrojDodatak;
	private $BrojPoste;
	private $Naselje;
	private $Opcina;
	
	public function getUlica()
	{
		return $this->Ulica;
	}

	public function setUlica($Ulica)
	{
		$this->Ulica = $Ulica;
	}

	public function getKucniBroj()
	{
		return $this->KucniBroj;
	}

	public function setKucniBroj($KucniBroj)
	{
		$this->KucniBroj = $KucniBroj;
	}

	public function getKucniBrojDodatak()
	{
		return $this->KucniBrojDodatak;
	}

	public function setKucniBrojDodatak($KucniBrojDodatak)
	{
		$this->KucniBrojDodatak = $KucniBrojDodatak;
	}

	public function getBrojPoste()
	{
		return $this->BrojPoste;
	}

	public function setBrojPoste($BrojPoste)
	{
		$this->BrojPoste = $BrojPoste;
	}

	public function getNaselje()
	{
		return $this->Naselje;
	}

	public function setNaselje($Naselje)
	{
		$this->Naselje = $Naselje;
	}

	public function getOpcina()
	{
		return $this->Opcina;
	}

	public function setOpcina($Opcina)
	{
		$this->Opcina = $Opcina;
	}
}

?>
