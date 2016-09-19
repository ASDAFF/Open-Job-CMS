<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrey Pasynkov
 * Date: 09.10.12
 * Time: 11:24
 * To change this template use File | Settings | File Templates.
 */
require_once "SxGeo.php";

class MyGeo {

	public $fileData = 'SxGeoCity.dat';

	public function init(){
	}

	/**
	 * @param $ip
	 * @return HLocation
	 */
	public function getLocation($ip = null){
		$ip = $ip ? $ip : Yii::app()->request->userHostAddress;

		$sxGeo = new SxGeo(dirname(__FILE__).'/'.$this->fileData);

		$sxData = $sxGeo->getCityFull($ip);

		unset($sxGeo);

		return new HLocation($sxData);
	}

	public function getCountryNameByIso($iso){
		$country = Countries::model()->findByAttributes(array('code' => $iso));

		return $country->name;
	}
}
