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

class WMessage extends CWidget{

    /** @var  User */
    public $recipient;

    public $object = NULL;

    public $objectName;
    public $formBottom = false;

    public $showStatuses = false;

    public $buttonLabel = 'Добавить';
    public $successMessage = 'Сообщение успешно добавлено';

    public function run() {
		$message = new Message();

        if(!$this->object){
            throw new CException('Не задан объект');
        }
        $this->objectName = get_class($this->object);

        if($this->objectName == 'Dialog' && !$this->recipient){
            throw new CException('Не задан получатель');
        }

		if(isset($_POST['Message']) && Yii::app()->user->id){
			$message->attributes = $_POST['Message'];
			$message->sender_id = Yii::app()->user->id;
            $message->object_name = $this->objectName;
            $message->object_id = $this->object->id;

            if($this->recipient){
                $message->recipient_id = $this->recipient->id;
            }

            if($message->save()){
                if($this->objectName == 'Dialog' && $this->object->status == Dialog::STATUS_DRAFT){
                    $this->object->status = Dialog::STATUS_OPEN;
                    $this->object->update(array('status'));
                }

                Yii::app()->user->setFlash('success', $this->successMessage);
                $message = new Message();
            }
		}

        $criteria = new CDbCriteria();
        $criteria->compare('object_name', $this->objectName);
        $criteria->compare('object_id', $this->object->id);
        $criteria->order = 't.date_created DESC';

        $pages = new ReverseCPagination(Message::model()->count($criteria));
        $pages->pageSize = param('messagePageSize', 5);
        $pages->applyLimit($criteria);

		$this->render('message', array(
			'message' => $message,
			'messages' => Message::model()->findAll($criteria),
            'pages'=>$pages,
        ));
	}

}
