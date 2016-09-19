<?php
Yii::import('ext.bootstrap.widgets.TbPager');

class ReverseLinkPager extends TbPager {

	public $nextPageLabel = '<';
	public $prevPageLabel = '>';

//	const CSS_FIRST_PAGE = 'first';
//	const CSS_LAST_PAGE = 'last';
//	const CSS_PREVIOUS_PAGE = 'previous';
//	const CSS_NEXT_PAGE = 'next';

	protected function createPageButtons()
	{
		if(($pageCount=$this->getPageCount())<=1)
			return array();

		list($beginPage,$endPage)=$this->getPageRange();

		$currentPage=$this->getCurrentPage(false); // currentPage is calculated in getPageRange()

		$buttons=array();

		// first page
		$buttons[]=$this->createPageButton($this->firstPageLabel,$pageCount-1,self::CSS_FIRST_PAGE,$currentPage>=$pageCount-1,false);

		// next page
		if(($page=$currentPage+1)>=$pageCount-1)
			$page=$pageCount-1;
		$buttons[]=$this->createPageButton($this->nextPageLabel,$page,self::CSS_NEXT_PAGE,$currentPage>=$pageCount-1,false);

		// internal pages
		for($i=$endPage;$i>=$beginPage;--$i){
			$buttons[]=$this->createPageButton($i+1,$i,self::CSS_INTERNAL_PAGE,false,$i==$currentPage);
		}

		// prev page
		if(($page=$currentPage-1)<0)
			$page=0;
		$buttons[]=$this->createPageButton($this->prevPageLabel,$page,self::CSS_PREVIOUS_PAGE,$currentPage<=0,false);

		// last page
		$buttons[]=$this->createPageButton($this->lastPageLabel,0,self::CSS_LAST_PAGE,$currentPage<=0,false);

		return $buttons;
	}
}
