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

class AjaxController extends Controller {

    public function filters() {
        return array(
            'ajaxOnly',
        );
    }

	public function actionCountry() {
		$country_id = Yii::app()->request->getPost('country_id', null);

		$data = Region::getList($country_id);

		//echo '<select class="region">';
		//echo '<option value=""></option>';
		foreach ($data as $value => $name) {
			echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
		}
		//echo '</select>';

		Yii::app()->end();
	}

	public function actionRegion() {
		$region_id = Yii::app()->request->getPost('region_id', null);

		$data = City::getList($region_id);

		//echo '<option value=""></option>';
		foreach ($data as $value => $name) {
			echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
		}
		Yii::app()->end();
	}

    public function actionAutocompleteSkill() {
        $res =array();

        if (isset($_GET['term'])) {
            $qtxt ="SELECT name FROM {{skill}} WHERE name LIKE :name";
            $command =Yii::app()->db->createCommand($qtxt);
            $command->bindValue(":name", '%'.$_GET['term'].'%', PDO::PARAM_STR);
            $res = $command->queryColumn();
        }

        echo CJSON::encode($res);
        Yii::app()->end();
    }

    public function actionAutoCompleteCity() {
        if (isset($_GET['q'])) {
            $criteria = new CDbCriteria;
            $criteria->condition = 'name LIKE :name';
            $criteria->params = array(':name'=>$_GET['q'].'%');

            if (isset($_GET['limit']) && is_numeric($_GET['limit'])) {
                $criteria->limit = $_GET['limit'];
            }

            //$countries = Country::model()->findAll($criteria);
            $city = City::model()->findAll($criteria);

            //$mix = CMap::mergeArray($countries, $city);

            $resStr = '';
            foreach ($city as $row) {
                $resStr .= $row->name;
                $resStr .= '|';
                $resStr .= $row->id;
                $resStr .= '|';
                $resStr .= $row->region_id;
                $resStr .= '|';
                $resStr .= $row->country_id;
                $resStr .= "\n";
            }
            echo $resStr;
        }
    }
}
