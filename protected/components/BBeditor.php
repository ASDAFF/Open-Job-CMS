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

Yii::import('application.extensions.wysibb.WysiBBWidget');

// https://github.com/brussens/Yii-WysiBB
//$this->widget('application.extensions.wysibb.WysiBBWidget', array(
//    'model'=>$model,//модель (если вы используете activeForm)
//    'attribute'=>'text',//название атрибута
//    'buttons'=>'bold,italic,underline,|,img,|',//набор кнопок, где bold, italic, underline - нужные кнопки,
//    а | - разграничитель.
////'selector'=>'#ArticleForm_text', //заменяемый селетор(определяется по умолчанию).
//));

class BBeditor extends  WysiBBWidget {
    public $buttons = 'bold,italic,underline,|,img,link,|,code,quote';
}