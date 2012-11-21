<?php

namespace Pro3x\Online;

class TableParams
{
	private $items;
	private $pageCount;
	private $page;
	private $addRoute;
	private $editRoute;
	private $deleteRoute;
	private $title;
	private $pager;
	private $icon;
	private $columns;
	private $toolsWidth;
	private $deleteColumn;
	private $deleteType;
	
	public function getDeleteType()
	{
		return $this->deleteType;
	}

	public function setDeleteType($deleteType)
	{
		$this->deleteType = $deleteType;
		return $this;
	}

	public function getDeleteColumn()
	{
		return $this->deleteColumn;
	}

	public function setDeleteColumn($deleteColumn)
	{
		$this->deleteColumn = $deleteColumn;
		return $this;
	}

	public function getEditRoute()
	{
		return $this->editRoute;
	}

	public function setEditRoute($editRoute)
	{
		$this->editRoute = $editRoute;
		return $this;
	}

	public function getDeleteRoute()
	{
		return $this->deleteRoute;
	}

	public function setDeleteRoute($deleteRoute)
	{
		$this->deleteRoute = $deleteRoute;
		return $this;
	}

	function __construct()
	{
		$this->columns = array();
		$this->toolsWidth = 200;
		$this->page = 1;
		$this->pageCount = 0;
		$this->pager = true;
		$this->title = "";
	}

	public function getToolsWidth()
	{
		return $this->toolsWidth;
	}

	public function setToolsWidth($toolsWidth)
	{
		$this->toolsWidth = $toolsWidth;
	}

	public function addColumn($name, $label = null, $width = 0)
	{
		$this->columns[] = new TableColumn($name, $label, $width);
		return $this;
	}
	
	public function getColumns()
	{
		return $this->columns;
	}

	public function getIcon()
	{
		return $this->icon;
	}

	public function setIcon($icon)
	{
		$this->icon = $icon;
		return $this;
	}

	public function getItems()
	{
		return $this->items;
	}

	public function setItems($items)
	{
		$this->items = $items;
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

	public function getPage()
	{
		return $this->page;
	}

	public function setPage($page)
	{
		$this->page = $page;
		return $this;
	}

	public function getAddRoute()
	{
		return $this->addRoute;
	}

	public function setAddRoute($addRoute)
	{
		$this->addRoute = $addRoute;
		return $this;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}

	public function getPager()
	{
		return $this->pager;
	}

	public function setPager($pager)
	{
		$this->pager = $pager;
		return $this;
	}
	
	public function getParams()
	{
		return array(
			'count'			=> $this->getPageCount(), 
			'page'			=> $this->getPage(), 
			'title'			=> $this->getTitle(),
			'route'			=> $this->getAddRoute(),
			'pager'			=> $this->getPager(),
			'items'			=> $this->getItems(),
			'icon'			=> $this->getIcon(),
			'columns'		=> $this->getColumns(),
			'toolsWidth'	=> $this->getToolsWidth(),
			'editRoute'		=> $this->getEditRoute(),
			'deleteRoute'	=> $this->getDeleteRoute(),
			'deleteColumn'	=> $this->getDeleteColumn(),
			'type'			=> $this->getDeleteType()
		);
	}
}

?>
