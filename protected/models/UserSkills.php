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
 * This is the model class for table "{{user_skills}}".
 *
 * The followings are the available columns in table '{{user_skills}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $skill_id
 * @property integer $level
 * @property integer $experience
 * @property Skill $skill
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class UserSkills extends CActiveRecord
{
    const LEVEL_NO_SHOW = -1;
    const LEVEL_BEGINNER = 3;
    const LEVEL_ADVANCED = 6;
    const LEVEL_EXPERT = 9;

    private static $_levelList = array(
        self::LEVEL_NO_SHOW => 'не показывать',
        self::LEVEL_BEGINNER => 'начальный',
        self::LEVEL_ADVANCED => 'продвинутый',
        self::LEVEL_EXPERT => 'эксперт',
    );

    const EXPERIENCE_NO_SHOW = -1;
    const EXPERIENCE_LESS_1_YEAR = 0;
    const EXPERIENCE_1_YEAR = 12;
    const EXPERIENCE_2_YEAR = 24;
    const EXPERIENCE_3_YEAR = 36;
    const EXPERIENCE_4_YEAR = 48;
    const EXPERIENCE_5_YEAR = 60;
    const EXPERIENCE_6_YEAR = 72;
    const EXPERIENCE_7_YEAR = 84;
    const EXPERIENCE_8_YEAR = 96;
    const EXPERIENCE_9_YEAR = 108;
    const EXPERIENCE_MORE_10_YEAR = 120;

    private static $_experienceList = array(
        self::EXPERIENCE_NO_SHOW => 'не показывать',
        self::EXPERIENCE_LESS_1_YEAR => 'меньше года',
        self::EXPERIENCE_1_YEAR => '1 год',
        self::EXPERIENCE_2_YEAR => '2 года',
        self::EXPERIENCE_3_YEAR => '3 года',
        self::EXPERIENCE_4_YEAR => '4 года',
        self::EXPERIENCE_5_YEAR => '5 лет',
        self::EXPERIENCE_6_YEAR => '6 лет',
        self::EXPERIENCE_7_YEAR => '7 лет',
        self::EXPERIENCE_8_YEAR => '8 лет',
        self::EXPERIENCE_9_YEAR => '9 лет',
        self::EXPERIENCE_MORE_10_YEAR => 'больше 10 лет',
    );

    public static function getLevelList(){
        return self::$_levelList;
    }

    public function getLevelName() {
        return isset(self::$_levelList[$this->level]) ? self::$_levelList[$this->level] : '?';
    }

    public static function getExperienceList(){
        return self::$_experienceList;
    }

    public function getExperienceName() {
        return isset(self::$_experienceList[$this->experience]) ? self::$_experienceList[$this->experience] : '?';
    }

    public function getSkillName() {
        return isset($this->skill) ? $this->skill->name : '?';
    }

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserSkills the static model class
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
		return '{{user_skills}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, skill_id, level', 'required'),
			array('user_id, skill_id, level, experience', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, skill_id, level, experience', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
            'skill' =>array(self::BELONGS_TO, 'Skill', 'skill_id')
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
			'skill_id' => 'Навык',
			'level' => 'Уровень',
			'experience' => 'Стаж',
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
		$criteria->compare('skill_id',$this->skill_id);
		$criteria->compare('level',$this->level);
		$criteria->compare('experience',$this->experience);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function scopes() {
        return array(
            'my' => array(
                'condition' => 'user_id=:user_id',
                'params' => array(':user_id' => Yii::app()->user->id),
            ),
            'sort' => array(
                'order' => 'date_created DESC',
            ),
        );
    }

    public function renderForList() {
        $levels = array(
            self::LEVEL_BEGINNER => '<sup class="junior">j</sup>',
            self::LEVEL_ADVANCED => '<sup class="senior">s</sup>',
            self::LEVEL_EXPERT => '<sup class="expert">e</sup>',
        );

        $level = $this->level != self::LEVEL_NO_SHOW ? $levels[$this->level] : '';

        $ex = $this->experience != self::EXPERIENCE_NO_SHOW ? 'стаж ' . $this->getExperienceName() : '';

        return '<span class="label label-info" rel="tooltip" title="'.$ex.'">' . $this->skill->name . $level . '</span>';
    }
}