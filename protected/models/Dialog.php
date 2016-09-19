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
 * This is the model class for table "{{dialog}}".
 *
 * The followings are the available columns in table '{{dialog}}':
 * @property integer $id
 * @property integer $sender_id
 * @property integer $recipient_id
 * @property string $date_created
 * @property string $date_updated
 * @property integer $status
 */
class Dialog extends CActiveRecord
{
    const STATUS_OPEN = 1;
    const STATUS_DRAFT = 2;

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
	 * @return Dialog the static model class
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
		return '{{dialog}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sender_id, recipient_id', 'required'),
			array('sender_id, recipient_id, status', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sender_id, recipient_id, date_created, date_updated, status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'sender' => array(self::BELONGS_TO, 'User', 'sender_id'),
            'recipient' => array(self::BELONGS_TO, 'User', 'recipient_id'),
		);
	}

    public function scopes() {
        return array(
            'my' => array(
                'condition' => 't.sender_id = :my_id OR t.recipient_id = :my_id',
                'params' => array(':my_id' => Yii::app()->user->id ),
            ),
            'open' => array(
                'condition' => 't.status = :status_open',
                'params' => array(':status_open' => self::STATUS_OPEN),
            )
        );
    }

    public function withUser($userId){
        $this->getDbCriteria()->mergeWith(array(
            'condition' => '(recipient_id=:user_id AND sender_id=:me_id) OR (recipient_id=:me_id AND sender_id=:user_id)',
            'params' => array(':user_id' => $userId, ':me_id' => Yii::app()->user->id)
        ));

        return $this;
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sender_id' => 'Sender',
			'recipient_id' => 'Recipient',
			'date_created' => 'Date Created',
			'date_updated' => 'Date Updated',
			'status' => 'Status',
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
		$criteria->compare('sender_id',$this->sender_id);
		$criteria->compare('recipient_id',$this->recipient_id);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('date_updated',$this->date_updated,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public static function checkAndCreate(Message $message){
        $criteria = new CDbCriteria();
        $criteria->addCondition('(sender_id=:o_id AND recipient_id=:t_id)');
        $criteria->addCondition('(sender_id=:t_id AND recipient_id=:o_id)', 'OR');
        $criteria->params = array(
            'o_id' => $message->sender_id,
            't_id' => $message->recipient_id,
        );
        $dialog = Dialog::model()->find($criteria);

        $status = $message->id ? Dialog::STATUS_OPEN : Dialog::STATUS_DRAFT;

        if(!$dialog){
            $dialog = new Dialog();
            $dialog->sender_id = $message->sender_id;
            $dialog->recipient_id = $message->recipient_id;
            $dialog->status = $status;
            $dialog->save();
        } elseif($dialog && $message->id && $dialog->status == Dialog::STATUS_DRAFT){
            $dialog->status = Dialog::STATUS_OPEN;
            $dialog->update(array('status'));
        }

        return $dialog;
    }

    public function getUrl() {
        return Yii::app()->createUrl('/message/dialog/view', array('id' => $this->id));
    }

    public static function getUrlWithUser($userId){
        $dialog = Dialog::model()->withUser($userId)->find();
        if($dialog){
            return $dialog->getUrl();
        }

        return Yii::app()->createUrl('/message/dialog/create', array('id' => $userId));
    }
}