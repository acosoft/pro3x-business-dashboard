<?php

namespace Pro3x\Online\Fina;

class PoslovniProstor
{
	private $Oib;
	private $OznPoslProstora;
	private $AdresniPodatak;
	private $RadnoVrijeme;
	private $DatumPocetkaPrimjene;
	private $SpecNamj;
	
	function __construct()
	{
		$this->AdresniPodatak = array();
		$this->AdresniPodatak['Adresa'] = new Adresa();
	}

		public function getOib()
	{
		return $this->Oib;
	}

	public function setOib($Oib)
	{
		$this->Oib = $Oib;
	}

	public function getOznPoslProstora()
	{
		return $this->OznPoslProstora;
	}

	public function setOznPoslProstora($OznPoslProstora)
	{
		$this->OznPoslProstora = $OznPoslProstora;
	}

	public function getAdresniPodatak()
	{
		return $this->AdresniPodatak['Adresa'];
	}

	public function setAdresniPodatak($adresa)
	{
		$this->AdresniPodatak['Adresa'] = $adresa;
	}

	public function getRadnoVrijeme()
	{
		return $this->RadnoVrijeme;
	}

	public function setRadnoVrijeme($RadnoVrijeme)
	{
		$this->RadnoVrijeme = $RadnoVrijeme;
	}

	public function getDatumPocetkaPrimjene()
	{
		return $this->DatumPocetkaPrimjene;
	}

	public function setDatumPocetkaPrimjene($DatumPocetkaPrimjene)
	{
		$this->DatumPocetkaPrimjene = $DatumPocetkaPrimjene;
	}

	public function getSpecNamj()
	{
		return $this->SpecNamj;
	}

	public function setSpecNamj($SpecNamj)
	{
		$this->SpecNamj = $SpecNamj;
	}
}

?>
