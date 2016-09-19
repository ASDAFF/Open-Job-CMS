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

class HLocation {
	const DEFAULT_CITY_ID = 1;

	public $city;
	public $region;
	public $country;

	public function __construct($sxData){
		if(isset($sxData['city'])){
			$this->city = City::model()->findByAttributes(array('name' => $sxData['city']));
		}

		if(!$this->city){
			$this->city = City::model()->findByPk(self::DEFAULT_CITY_ID);
		}
	}

	public function getString(){
		return $this->city->country->name . ', ' . $this->city->region->name . ', ' . $this->city->name;
	}

	public function setDataForModel(&$model){
		$model->city_id = $this->city->id;
		$model->region_id = $this->city->region->id;
		$model->country_id = $this->city->country->id;
	}
}
