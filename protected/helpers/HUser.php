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

class HUser {
    const UPLOAD_MAIN = 'main';
    const UPLOAD_PORTFOLIO = 'portfolio';
    const UPLOAD_AVA = 'ava';

    public static function getUploadDirectory(User $user, $category = self::UPLOAD_MAIN) {
        $DS = DIRECTORY_SEPARATOR;
        $root = ROOT_PATH . $DS . 'uploads' . $DS . $category;
        self::genDir($root);

        $year = date('Y', strtotime($user->create_at));
        $path = $root . $DS . $year;
        self::genDir($path);

        $month = date('m', strtotime($user->create_at));
        $path = $path . $DS . $month;
        self::genDir($path);

        return $path;
    }

    public static function getUploadUrl(User $user, $category = self::UPLOAD_MAIN){
        $DS = '/';
        $root = $DS . 'uploads' . $DS . $category;

        $year = date('Y', strtotime($user->create_at));
        $path = $root . $DS . $year;

        $month = date('m', strtotime($user->create_at));
        $path = $path . $DS . $month;

        return Yii::app()->createUrl($path);
    }

    public static function genDir($path){
        if(!is_dir($path)){
            if(!mkdir($path)){
                throw new CException('HUser невозможно создать директорию ' . $path);
            }
        }
    }

    public static function setLastVisit()
    {
        $user = UserModule::user();
        if($user){
            $user->lastvisit = time();
            $user->save();
        }
    }
}