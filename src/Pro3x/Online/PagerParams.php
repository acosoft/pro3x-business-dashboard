<?php

namespace Pro3x\Online;

class PagerParams
{
	private $page;
	private $pageCount;
	private $routeParams;
	
	public function getPage()
	{
		return $this->page;
	}

	public function setPage($page)
	{
		$this->page = $page;
		return $this;
	}

	public function getPageCount()
	{
		return $this->pageCount;
	}

	public function setPageCount($pageCount)
	{
		$this->pageCount = $pageCount;
		return $this;
	}

	public function getRouteParams()
	{
		return $this->routeParams;
	}

	public function addRouteParam($name, $value)
	{
		$this->routeParams[$name] = $value;
		return $this;
	}
	
	public function getParams()
	{
		return array('page' => $this->getPage(),
			'pagerParams' => $this->getRouteParams(),
			'count' => $this->getPageCount());
	}
}

?>
