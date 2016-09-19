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

class HAjax {
	const STATUS_OK = 'ok';
	const STATUS_NONE = 'none';
	const STATUS_ERROR = 'error';

	private static $_loadedScripts;

	public static function getImgLoadingBig(){
		return Yii::app()->theme->baseUrl.'/images/ajax/loading_big.gif';
	}

	public static function jsonError($msg = 'Ошибка'){
		echo CJSON::encode(array(
			'status' => self::STATUS_ERROR,
			'msg' => $msg
		));
		Yii::app()->end();
	}


	public static function jsonOk($msg = 'Успешно', $params = array()){
		$params = CMap::mergeArray(array(
			'status' => self::STATUS_OK,
			'msg' => $msg
		), $params);

		echo CJSON::encode($params);
		Yii::app()->end();
	}

	public static function jsonNone(){
		echo CJSON::encode(array(
			'status' => self::STATUS_NONE,
		));
		Yii::app()->end();
	}

	public static function implodeModelErrors($model, $glue = '<br><br>'){
		if(empty($model->errors) || !is_array($model->errors)){
			return '';
			//throw new CException('HAjax::implodeModelErrors - нет модели');
		}

		$errorArray = array();

		foreach($model->errors as $field => $errors){
			$errorArray[] = implode($glue, $errors);
		}

		return implode($glue, $errorArray);
	}

	public static function loadScrips($viewUrl = '', $scripts){

		foreach($scripts as $script){
			$jsUrl = $viewUrl . '/' . $script . '.js';
			echo "<script src=\"$jsUrl\"></script>";
		}

	}
}
