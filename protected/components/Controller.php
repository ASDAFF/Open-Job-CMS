<?php
/* * ********************************************************************************************
 *								Open Job CMS
 *								------------
 * 	version				:	V1.0.0
 * 	copyright			:	(c) 2016 Monoray
 * 							http://monoray.net
 *							http://monoray.ru
 *
 * 	website				:	https://monoray.ru/products/open-job-cms
 *
 * 	contact us			:	http://open-real-estate.info/en/contact-us
 *
 * 	license:			:	http://open-real-estate.info/en/license
 * 							http://open-real-estate.info/ru/license
 *
 * This file is part of Open Job CMS
 *
 * ********************************************************************************************* */

class Controller extends CController
{
	public $pageDescription;
	public $pageKeywords;

	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column2';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	public $topMenu = array();

    public $showButtons = false;

	public $categoryItems = array();

	public function init(){

		parent::init();
		if (!file_exists(ALREADY_INSTALL_FILE) && !(Yii::app()->controller->module && Yii::app()->controller->module->id == 'install')) {
			$this->redirect(array('/install/main/index'));
		}

		if(Yii::app()->request->isAjaxRequest){
            $this->layout = false;
        } else {
            HMenu::setState('');
        }

		Yii::app()->name = param('siteName');
	}

	public function render($view,$data=null,$return=false) {
		if($this->beforeRender($view))
		{
			$output=$this->renderPartial($view,$data,true);
			if(($layoutFile=$this->getLayoutFile($this->layout))!==false) {
				$output=$this->renderFile($layoutFile,array('content'=>$output),true);
			}
			$this->afterRender($view,$output);
			$output=$this->processOutput($output);
			$output=call_user_func(array('GeoCoder', "in"), $output);
			if($return) {
				return $output;
			}
			else {
				echo $output;
			}
		}
	}
}