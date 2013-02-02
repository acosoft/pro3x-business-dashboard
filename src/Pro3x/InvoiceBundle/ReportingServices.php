<?php

namespace Pro3x\InvoiceBundle;

class ReportingServices
{
	private $dailyReport;
	
	function __construct($dailyReport)
	{
		$this->dailyReport = $dailyReport;
	}

	public function getDailyReportTemplate()
	{
		return $this->dailyReport;
	}
}

?>
