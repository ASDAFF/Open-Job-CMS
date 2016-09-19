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

function deb($var)
{
    echo '<pre>';
    CVarDumper::dump($var, 10, true);
    echo '</pre>';
}

function logs($mVal) {
    $file = fopen(ROOT_PATH.'/protected/runtime/logs.txt', 'a+');
    $sLogs = date("d.m.y H:i : ") . var_export($mVal, true) . "\n";
    fwrite($file, $sLogs);
    fclose($file);
}

function param($name, $default = null) {
    if (isset(Yii::app()->params[$name])) {
        return Yii::app()->params[$name];
    } else {
        return $default;
    }
}

function isDemo(){
    if(defined('IS_DEMO') && IS_DEMO){
        return true;
    } else {
        return false;
    }
}

function demoCheck(){
    if(isDemo())
        throw new CHttpException(403, 'В демо версии это сделать нельзя');
}
