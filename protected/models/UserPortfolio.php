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
 * This is the model class for table "{{user_portfolio}}".
 *
 * The followings are the available columns in table '{{user_portfolio}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $status
 * @property string $description
 * @property string $img
 * @property string $date_created
 * @property string $date_updated
 * @property User $owner
 */
class UserPortfolio extends CActiveRecord
{
    const THUMB_PREFIX = 'thumb_';

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
	 * @return UserPortfolio the static model class
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
		return '{{user_portfolio}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('description', 'required'),
			array('user_id, status', 'numerical', 'integerOnly'=>true),
			array('img', 'length', 'max'=>100),
			array('description', 'length', 'max'=>255),

			array('id, user_id, description, img, date_created, date_updated', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()	{
		return array(
			'owner' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

    public function scopes() {
        return array(
            'my' => array(
                'condition' => 't.user_id=:user_id',
                'params' => array(':user_id' => Yii::app()->user->id),
            ),
            'open' => array(
                'condition' => 't.status=:status_open',
                'params' => array(':status_open' => self::STATUS_OPEN),
            ),
        );
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'description' => 'Description',
			'img' => 'Img',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('img',$this->img);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('date_updated',$this->date_updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public $sImgSrc;
    public $sImgSrcThumb;

    public function getImgSrc() {
        $url = HUser::getUploadUrl($this->owner, HUser::UPLOAD_PORTFOLIO);

        return $url . '/' . $this->img;
    }

    public function getImgSrcThumb() {
        $url = HUser::getUploadUrl($this->owner, HUser::UPLOAD_PORTFOLIO);

        return $url . '/' . self::THUMB_PREFIX . $this->img;
    }

    public static function deleteDraft(){
        $criteria = new CDbCriteria();
        $criteria->compare('t.status', self::STATUS_DRAFT);

        $portfolios = UserPortfolio::model()->with('owner')->findAll($criteria);
        foreach($portfolios as $portfolio){
            $portfolio->delete();
        }
    }

    public function beforeDelete(){
        if($this->owner){
            $dir = HUser::getUploadDirectory($this->owner, HUser::UPLOAD_PORTFOLIO) . DIRECTORY_SEPARATOR;

            @unlink($dir . $this->img);
            @unlink($dir . self::THUMB_PREFIX . $this->img);
        }

        return parent::beforeDelete();
    }
}