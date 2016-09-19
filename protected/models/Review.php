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
 * This is the model class for table "{{review}}".
 *
 * The followings are the available columns in table '{{review}}':
 * @property integer $id
 * @property integer $type
 * @property integer $status
 * @property integer $sender_id
 * @property integer $recipient_id
 * @property string $text
 * @property string $object_name
 * @property integer $object_id
 * @property string $date_created
 * @property string $date_updated
 */
class Review extends CActiveRecord
{
    const STATUS_OPEN = 1;
    const STATUS_ON_MODERATION = 2;

    private static $_statuses = array(
        self::STATUS_OPEN => 'открыт',
        self::STATUS_ON_MODERATION => 'на модерации',
    );

    const TYPE_NONE = 0;
    const TYPE_NEGATIVE = 1;
    const TYPE_POSITIVE = 2;

    private static $_types = array(
        self::TYPE_POSITIVE => 'Хвалю',
        self::TYPE_NEGATIVE => 'Ругаю',
    );

    public static function getTypeList(){
        return self::$_types;
    }

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
	 * @return Review the static model class
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
		return '{{review}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('text, type', 'required'),
			array('id, type, status, sender_id, recipient_id, object_id', 'numerical', 'integerOnly'=>true),
			array('object_name', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type, status, sender_id, recipient_id, text, object_name, object_id, date_created, date_updated', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
    /**
     * @return array relational rules.
     */
    public function relations()	{
        return array(
            'sender' => array(self::BELONGS_TO, 'User', 'sender_id'),
            'recipient' => array(self::BELONGS_TO, 'User', 'recipient_id'),
        );
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type' => 'Ваше отношение',
			'status' => 'Status',
			'sender_id' => 'Sender',
			'recipient_id' => 'Recipient',
			'text' => 'Отзыв',
			'object_name' => 'Object Name',
			'object_id' => 'Object',
			'date_created' => 'Date Created',
			'date_updated' => 'Date Updated',
		);
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
		$criteria->compare('type',$this->type);
		$criteria->compare('status',$this->status);
		$criteria->compare('sender_id',$this->sender_id);
		$criteria->compare('recipient_id',$this->recipient_id);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('object_name',$this->object_name,true);
		$criteria->compare('object_id',$this->object_id);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('date_updated',$this->date_updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function getCssClass(){
        if($this->type == Review::TYPE_NEGATIVE) {
            return 'red';
        } elseif($this->type == Review::TYPE_POSITIVE) {
            return 'green';
        } else {
            return '';
        }
    }

    public function afterSave(){
        if($this->recipient->sbs_newReview && $this->isNewRecord){
            HEmail::send($this->recipient->email, 'Новый отзыв', 'newReview', array(
                'fullName' => $this->recipient->getFullName(),
                'senderFullName' => $this->sender->getFullName(),
                'message' => $this->text,
            ));
        }
    }
}