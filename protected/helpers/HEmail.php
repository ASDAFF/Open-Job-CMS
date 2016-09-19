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

class HEmail {
    private static $_layout = 'layout';

    public static function send($email, $subject, $view, $data = array())
    {
        Yii::import('ext.mailer.EMailer');

        $mailer = new EMailer();

        if (param('mailUseSMTP', 0)) {
            $mailer->setSmtp(
                param('mailSMTPHost', 'localhost'),
                param('mailSMTPPort', 25),
                param('mailSMTPSecure'),
                true,
                param('mailSMTPLogin'),
                param('mailSMTPPass')
            );
        }

        $mailer->From = Yii::app()->params['adminEmail'];
        $mailer->FromName = Yii::app()->name;
        $mailer->AddAddress($email);
        $mailer->Subject = $subject;
        $mailer->Body = HEmail::render($view, $data);
        $mailer->CharSet = 'UTF-8';
        $mailer->IsHTML(true);

        $ret = $mailer->Send();

        if(!$ret){
            H:logs("Ошибка отправки на ".$email." error: ".$mailer->ErrorInfo);
        }

        return $ret;
    }

    public static function setLayout($view){
        self::$_layout = $view;
    }

    private static function render($view, array $data = array()){
        $contentMail = self::renderPartial($view, $data);

        return self::renderPartial(self::$_layout, array('contentMail' => $contentMail));
    }

    private static function renderPartial($view, array $data = array()){
        $viewPath = Yii::getPathOfAlias('application.views.mail.'.$view).'.php';
        if(!file_exists($viewPath)) throw new Exception('HEmail template '.$viewPath.' does not exist.');
        return Yii::app()->controller->renderInternal($viewPath, $data, true);
    }
}