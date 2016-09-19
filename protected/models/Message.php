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

/**
 * This is the model class for table "{{message}}".
 *
 * The followings are the available columns in table '{{message}}':
 * @property integer $id
 * @property string $object_name
 * @property integer $type
 * @property integer $sender_id
 * @property integer $recipient_id
 * @property integer $object_id
 * @property string $body
 * @property string $date_created
 * @property string $date_updated
 * @property integer $status
 * @property integer $rating
 * @property integer $viewed
 * @property User $sender
 * @property User $recipient
 * @property Project $project
 */
class Message extends CActiveRecord
{
	const STATUS_HIDE = 0;
	const STATUS_OPEN = 1;

    private static $_statuses = array(
        self::STATUS_HIDE => 'Видно только мне и заказчику',
        self::STATUS_OPEN => 'Видно всем'
    );

    public $status = self::STATUS_OPEN;

    public static function getStatusesList() {
        return self::$_statuses;
    }

	public function behaviors() {
		return array(
			'AutoTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'date_created',
				'updateAttribute' => 'date_updated',
			),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Message the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{message}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('body', 'required'),
			array('sender_id, recipient_id, object_id, status, rating, viewed, type', 'numerical', 'integerOnly'=>true),
			array('object_name', 'length', 'max'=>30),
			array('email', 'length', 'max'=>50),
			array('email', 'email'),

			array('id, object_name, sender_id, body, date_created, date_updated, status, rating, viewed', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()	{
		return array(
            'sender' => array(self::BELONGS_TO, 'User', 'sender_id'),
            'recipient' => array(self::BELONGS_TO, 'User', 'recipient_id'),
            'project' => array(self::BELONGS_TO, 'Project', 'object_id'),
            'dialog' => array(self::HAS_ONE, 'Dialog', 'dialog_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'object_name' => 'Object Name',
			'sender_id' => 'Отправитель',
			'recipient_id' => 'Получитель',
			'body' => 'Сообщение',
			'date_created' => 'Создано',
			'date_updated' => 'Обновлено',
			'status' => 'Статус',
			'rating' => 'Rating',
			'viewed' => 'Viewed',
		);
	}

    public function scopes() {
        return array(
            'open' => array(
                'condition' => 'status = :status',
                'params' => array(':status' => self::STATUS_OPEN)
            ),
        );
    }

    public function personal($userID) {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => '((recipient_id=:he_id AND sender_id=:my_id) OR (recipient_id=:my_id AND sender_id=:he_id)) AND type=:type_personal',
            'params' => array(
                ':he_id' => $userID,
                ':my_id' => Yii::app()->user->id,
                ':type_personal' => self::TYPE_PERSONAL,
            ),
            'order' => 'date_created DESC'
        ));

        return $this;
    }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('object_name',$this->object_name,true);
		$criteria->compare('sender_id',$this->sender_id);
		$criteria->compare('recipient_id',$this->recipient_id);
		$criteria->compare('body',$this->body,true);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('date_updated',$this->date_updated,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('rating',$this->rating);
		$criteria->compare('viewed',$this->viewed);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public static function getCountUnread($objectName) {
        return Message::model()->count('object_name=:object_name AND viewed=0 AND sender_id!=:user_id AND recipient_id=:user_id', array(
            ':object_name' => $objectName,
            ':user_id' => Yii::app()->user->id,
        ));
    }

    public function isISender(){
        return Yii::app()->user->id == $this->sender_id;
    }

    public function canShow() {
        if($this->status != Message::STATUS_HIDE){
            return true;
        }

        if($this->status == Message::STATUS_HIDE && $this->sender_id == Yii::app()->user->id){
            return true;
        }

        if($this->object_name == 'Project' && $this->project->user_id == Yii::app()->user->id){
            return true;
        }

        return false;
    }

    public function afterSave(){
        if($this->isNewRecord){
            switch($this->object_name){
                case 'Dialog':
                    if($this->recipient->sbs_newMess){
                        HEmail::send($this->recipient->email, 'Новое сообщение', 'newMessageInDialog', array(
                            'fullName' => $this->recipient->getFullName(),
                            'senderFullName' => $this->sender->getFullName(),
                            'message' => $this->body,
                        ));
                    }
                    break;

                case 'Project':
                    if($this->recipient->sbs_newRequest){
                        HEmail::send($this->recipient->email, 'Новое заявка к заказу', 'newRequest', array(
                            'fullName' => $this->recipient->getFullName(),
                            'senderFullName' => $this->sender->getFullName(),
                            'message' => $this->body,
                            'projectLink' => $this->project->getUrl(true),
                        ));
                    }
                    break;
            }
        }
    }
}