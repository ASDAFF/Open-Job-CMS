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
 * This is the model class for table "{{countries}}".
 *
 * The followings are the available columns in table '{{countries}}':
 * @property integer $id
 * @property string $name_en
 * @property string $name_ru
 * @property string $currency_code
 * @property string $flag_icon
 * @property string $code
 * @property integer $_order
 * @property integer $independent
 */
class Countries extends CActiveRecord
{
    const DEFAULT_ID = 1;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Countries the static model class
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
		return '{{countries}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name_en, code', 'required'),
			array('_order, independent', 'numerical', 'integerOnly'=>true),
			array('name_en, name_ru, flag_icon', 'length', 'max'=>128),
			array('currency_code', 'length', 'max'=>3),
			array('code', 'length', 'max'=>2),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name_en, name_ru, currency_code, flag_icon, code, _order, independent', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name_en' => 'Name En',
			'name_ru' => 'Name Ru',
			'currency_code' => 'Currency Code',
			'flag_icon' => 'Flag Icon',
			'code' => 'Code',
			'_order' => 'Order',
			'independent' => 'Independent',
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
		$criteria->compare('name_en',$this->name_en,true);
		$criteria->compare('name_ru',$this->name_ru,true);
		$criteria->compare('currency_code',$this->currency_code,true);
		$criteria->compare('flag_icon',$this->flag_icon,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('_order',$this->_order);
		$criteria->compare('independent',$this->independent);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getName(){
		$field = 'name_'.Yii::app()->language;
		return $this->$field;
	}
}