<?php

namespace Pro3x\Online\Fina;

class Racun
{
	private $Oib;
	private $USustPdv;
	private $DatVrijeme;
	private $OznSlijed;
	private $BrRac;
	private $Pdv;
	private $Pnp;
	private $IznosUkupno;
	private $NacinPlac;
	private $ZastKod;
	private $NakDost;
	private $ParagonBrRac;
	private $OibOper;
	
	function __construct()
	{
		$this->BrRac = new BrRac();
		$this->Pdv = array();
	}

	public function getOib()
	{
		return $this->Oib;
	}

	public function setOib($Oib)
	{
		$this->Oib = $Oib;
	}

	public function getUSustPdv()
	{
		return $this->USustPdv;
	}

	public function setUSustPdv($USustPdv)
	{
		$this->USustPdv = $USustPdv;
	}

	public function getDatVrijeme()
	{
		return $this->DatVrijeme;
	}

	public function setDatVrijeme($DatVrijeme)
	{
		$this->DatVrijeme = $DatVrijeme;
	}

	public function getOznSlijed()
	{
		return $this->OznSlijed;
	}

	public function setOznSlijed($OznSlijed)
	{
		$this->OznSlijed = $OznSlijed;
	}

	/**
	 * 
	 * @return BrRac
	 */
	public function getBrRac()
	{
		return $this->BrRac;
	}

	public function setBrRac($BrRac)
	{
		$this->BrRac = $BrRac;
	}

	public function getPdv()
	{
		return $this->Pdv;
	}

	public function setPdv($Pdv)
	{
		$this->Pdv = $Pdv;
	}
	
	public function getPnp()
	{
		return $this->Pnp;
	}

	public function setPnp($Pnp)
	{
		$this->Pnp = $Pnp;
	}

	public function getIznosUkupno()
	{
		return $this->IznosUkupno;
	}

	public function setIznosUkupno($IznosUkupno)
	{
		$this->IznosUkupno = number_format($IznosUkupno, 2, '.', '');
	}

	public function getNacinPlac()
	{
		return $this->NacinPlac;
	}

	public function setNacinPlac($NacinPlac)
	{
		$this->NacinPlac = $NacinPlac;
	}

	public function getZastKod()
	{
		return $this->ZastKod;
	}

	public function setZastKod($ZastKod)
	{
		$this->ZastKod = $ZastKod;
	}

	public function getNakDost()
	{
		return $this->NakDost;
	}

	public function setNakDost($NakDost)
	{
		$this->NakDost = $NakDost;
	}

	public function getParagonBrRac()
	{
		return $this->ParagonBrRac;
	}

	public function setParagonBrRac($ParagonBrRac)
	{
		$this->ParagonBrRac = $ParagonBrRac;
	}

	public function getOibOper()
	{
		return $this->OibOper;
	}

	public function setOibOper($OibOper)
	{
		$this->OibOper = $OibOper;
	}
}

?>
