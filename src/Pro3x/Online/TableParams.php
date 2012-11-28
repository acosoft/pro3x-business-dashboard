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
	private $pagerVisible;
	private $icon;
	private $columns;
	private $toolsWidth;
	private $deleteColumn;
	private $deleteType;
	private $searchVisible;
	private $pagerParams;
	private $selectParam;
	private $selectColumn;
	private $noSelection;
	private $placeholder;
	
	public function getPlaceholder()
	{
		return $this->placeholder;
	}

	public function setPlaceholder($placeholder)
	{
		$this->placeholder = $placeholder;
		return $this;
	}

	public function getNoSelection()
	{
		return $this->noSelection;
	}

	public function setNoSelection($noSelection)
	{
		$this->noSelection = $noSelection;
	}

	public function getSelectParam()
	{
		return $this->selectParam;
	}

	public function setSelectParam($selectParam)
	{
		$this->selectParam = $selectParam;
		return $this;
	}

	public function getSelectColumn()
	{
		return $this->selectColumn;
	}

	public function setSelectColumn($selectColumn)
	{
		$this->selectColumn = $selectColumn;
		return $this;
	}

	public function addPagerParam($name, $value)
	{
		$this->pagerParams[$name] = $value;
		return $this;
	}
	
	public function getPagerParams()
	{
		return $this->pagerParams;
	}

	public function getSearchVisible()
	{
		return $this->searchVisible;
	}

	public function setSearchVisible($searchVisible)
	{
		$this->searchVisible = $searchVisible;
		return $this;
	}

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
		$this->pagerVisible = true;
		$this->title = "";
		$this->pagerParams = array();
		$this->searchVisible = false;
		$this->selectParam = 'id';
		$this->selectColumn = 'id';
		$this->noSelection = true;
	}

	public function getToolsWidth()
	{
		return $this->toolsWidth;
	}

	public function setToolsWidth($toolsWidth)
	{
		$this->toolsWidth = $toolsWidth;
		return $this;
	}

	public function addColumnTrans($name, $label = null, $width = 0, $align="left")
	{
		return $this->addColumn($name, $label, $width, $align, true);
	}
	
	public function addColumn($name, $label = null, $width = 0, $align="left", $translate = false)
	{
		$column = new TableColumn($name, $label, $width, $align, $translate);

		$this->columns[] = $column;
		
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

	public function getPagerVisible()
	{
		return $this->pagerVisible;
	}

	public function setPagerVisible($pager)
	{
		$this->pagerVisible = $pager;
		return $this;
	}
	
	public function getParams()
	{
		return array(
			'count'				=> $this->getPageCount(), 
			'page'				=> $this->getPage(), 
			'title'				=> $this->getTitle(),
			'route'				=> $this->getAddRoute(),
			'pager'				=> $this->getPagerVisible(),
			'items'				=> $this->getItems(),
			'icon'				=> $this->getIcon(),
			'columns'			=> $this->getColumns(),
			'toolsWidth'		=> $this->getToolsWidth(),
			'editRoute'			=> $this->getEditRoute(),
			'deleteRoute'		=> $this->getDeleteRoute(),
			'deleteColumn'		=> $this->getDeleteColumn(),
			'type'				=> $this->getDeleteType(),
			'search'			=> $this->getSearchVisible(),
			'pagerParams'		=> $this->getPagerParams(),
			'selectParam'		=> $this->getSelectParam(),
			'selectColumn'		=> $this->getSelectColumn(),
			'noSelection'		=> $this->getNoSelection(),
			'placeholder'		=> $this->getPlaceholder()
		);
	}
}

?>
