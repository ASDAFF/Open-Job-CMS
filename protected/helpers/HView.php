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

class HView {
    public static function echoAndSetTitle($title, $category = NULL) {
        $titleArr = array();
        $titleArr[] = $title;
        if($category){
            $titleArr[] = $category;
        }
        $titleArr[] = Yii::app()->name;
        Yii::app()->controller->pageTitle = implode(' - ', $titleArr);

        echo CHtml::tag(param('titleTag'), array(), $title);
    }
}