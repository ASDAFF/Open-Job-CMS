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

class HMenu {
    private static $_active;

	public static function isActive($str){
		if(self::$_active == $str){
			return true;
		}

		if(strpos($str, '.')){
			$ex = explode('.', $str);

			$module = $ex[0];
			$controller = $ex[1];

			if(isset(Yii::app()->controller->module) && Yii::app()->controller->module->id == $module &&
				Yii::app()->controller->id == $controller){
				return true;
			}
		} else {
			$module = $str;
			if(isset(Yii::app()->controller->module) && Yii::app()->controller->module->id == $module){
				return true;
			}
		}
		return false;
	}

	public static function setState($val){
		self::$_active = $val;
	}

    public static function getLeftItems(){
        if(Yii::app()->user->isGuest){
            return array();
        }

        $user = UserModule::user();

        $countUnreadPersonal = Message::getCountUnread('Dialog');
        $countUnread = $countUnreadPersonal ? ' <span class="leftMenu-unread">(' . $countUnreadPersonal . ')</span>' : '';

        $items = array(
            array('label'=>'Мой профиль', 'url'=>$user->getUrl(), 'active' => HMenu::isActive('user.view')),
            array('label'=>'Мои заказы', 'url'=>Yii::app()->createUrl('/project/my'), 'active' => HMenu::isActive('project.my')),
            array('label'=>'Добавить заказ', 'url'=>Yii::app()->createUrl('/project/create'), 'active' => HMenu::isActive('project.create')),
            array('label'=>'Мои сообщения' . $countUnread, 'url'=>Yii::app()->createUrl('/message/dialog'), 'active' => HMenu::isActive('message.dialog')),
            array('label'=>'Мое портфолио', 'url'=>Yii::app()->createUrl('/user/portfolio'), 'active' => HMenu::isActive('user.portfolio')),
            array('label'=>'Мои навыки', 'url'=>Yii::app()->createUrl('/user/skill/list'), 'active' => HMenu::isActive('user.skill'), 'visible' => $user->isProgrammer()),
            array('label'=>UserModule::t('Edit'), 'url'=>Yii::app()->createUrl('/user/profile/edit'), 'active' => HMenu::isActive('user.edit')),
            array('label'=>UserModule::t('Change password'), 'url'=>Yii::app()->createUrl('/user/profile/changepassword'), 'active' => HMenu::isActive('user.changepassword')),
            array('label'=>UserModule::t('Logout'), 'url'=>Yii::app()->createUrl('/user/logout'), 'active' => HMenu::isActive('user.logout')),
        );

        return $items;
    }

    public static function getAdminMenu()
    {
        return array(
            array('label'=>'Заказы', 'url'=>Yii::app()->createUrl('/project/backend/main/admin'), 'active' =>  HMenu::isActive('project.admin')),
            array('label'=>'Пользователи', 'url'=>Yii::app()->createUrl('/user/admin'), 'active' =>  HMenu::isActive('user.admin')),
            array('label'=>'Навыки', 'url'=>Yii::app()->createUrl('/skill/backend/main/admin'), 'active' =>  HMenu::isActive('skill.admin')),
            //array('label'=>UserModule::t('Manage Profile Field'), 'url'=> Yii::app()->createUrl('/user/profileField/admin'), 'active' => HMenu::isActive('user.profileField')),
        );
    }

    public static function getTopMenuLeft(){
        $topMenu = array();

        $topMenu[] = array('label' => 'Заказы', 'url' => Yii::app()->createAbsoluteUrl('/'), 'active' => HMenu::isActive('project'));
        $topMenu[] = array('label' => 'Исполнители', 'url' => Yii::app()->createUrl('/user/default/index'), 'active' => HMenu::isActive('user.list'));

        if(isDemo()){
            $topMenu[] = array('divider' => 1);
            $itemOptions = array('class' => 'top-info');
            $topMenu[] = array('label' => 'Скачать', 'url' => 'http://static.monoray.ru/OpenJobCmsV1.zip', 'itemOptions' => $itemOptions);
            $topMenu[] = array('label' => 'О продукте', 'url' => 'https://monoray.ru/products/open-job-cms', 'itemOptions' => $itemOptions);
            $topMenu[] = array('label' => 'Связаться с нами', 'url' => 'https://monoray.ru/contact', 'itemOptions' => $itemOptions);
            $topMenu[] = array('divider' => 1);
        }

        return $topMenu;
    }

    public static function getTopMenuRight(){
        $topMenu = array();
        $user = UserModule::user();
        if($user){
            $topMenu[] = array('label' => $user->getFullName(), 'url' => $user->getUrl(), 'items' => HMenu::getLeftItems());
        }else{
            $userModule = Yii::app()->getModule('user');
            $topMenu[] = array('label' => $userModule->t("Login"), 'url' => $userModule->loginUrl, 'active' => HMenu::isActive('user.login'));
        }
        return $topMenu;
    }

}
