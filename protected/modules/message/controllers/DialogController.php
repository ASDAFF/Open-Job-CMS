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

class DialogController extends BaseUserController {
    public function actionIndex(){
        //$criteria = new CDbCriteria();
        // создаем экземпляр CSort для сортировки в CGridView,
        // можно так же описать простым массивом
        $sort = new CSort();
        // имя $_GET параметра для сортировки,
        // по умолчанию ModelName_sort
        $sort->sortVar = 'sort';
        // сортировка по умолчанию
        $sort->defaultOrder = 't.date_created DESC';
        // включает поддержку мультисортировки,
        // т.е. можно отсортировать сразу и по названию и по цене
        $sort->multiSort = true;
        // здесь описываем аттрибуты, по которым будет сортировка
        // ключ может быть произвольный, это будет $_GET параметр
        $sort->attributes = array(
            'name'=>array(
                'label'=>'имени',
                'asc'=>'sender.username ASC',
                'desc'=>'sender.username DESC',
                'default'=>'desc',
            ),
            'date'=>array(
                'label'=>'дате',
                'asc'=>'t.date_created ASC',
                'desc'=>'t.date_created DESC',
                'default'=>'desc',
            ),
        );
        $dataProvider = new CActiveDataProvider(Dialog::model()->my()->open()->with('sender'),
            array(
                //'criteria'=>$criteria,
                'sort'=>$sort,
                'pagination'=>array(
                    'pageSize'=>12,
                ),
            )
        );
        $this->render('index', array(
            'dataProvider'=>$dataProvider
        ));
    }

    public function actionCreate($id){
        $message = new Message();
        $message->recipient_id = $id;
        $message->sender_id = Yii::app()->user->id;

        $dialog = Dialog::checkAndCreate($message);

        $this->render('view', array(
            'recipient' => $dialog->recipient,
            'dialog' => $dialog,
        ));
    }

    public function actionView($id){
        $dialog = Dialog::model()->findByPk($id);

        if(!$dialog){
            throw new CHttpException(404, 'Такого диалога нет');
        }

        $this->render('view', array(
            'recipient' => $dialog->recipient,
            'dialog' => $dialog,
        ));
    }
}