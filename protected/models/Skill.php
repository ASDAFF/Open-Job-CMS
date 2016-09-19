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
 * This is the model class for table "{{skill}}".
 *
 * The followings are the available columns in table '{{skill}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $status
 * @property string $name
 * @property string $description
 * @property integer $sort
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class Skill extends CActiveRecord
{
    public $childs;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_MODERATION = 2;

	public $is_root;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Skill the static model class
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
		return '{{skill}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status, name', 'required'),
			array('status, sort, parent_id', 'numerical', 'integerOnly'=>true),
			array('name, alias', 'length', 'max'=>255),
			array('alias', 'match', 'pattern' => '#^[-a-zA-Z0-9]{1,255}$#', 'message' => 'Допускаются символы  "a-zA-Z0-9-" без пробелов'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('description,is_root', 'safe'),
			array('parent_id', 'validParent'),
			array('alias', 'unique'),
			array('id, parent_id, status, name, sort, is_root', 'safe', 'on'=>'search'),
		);
	}

	public function validParent()
	{
		if(!$this->is_root && $this->parent_id == $this->id){
			$this->addError('parent_id', 'Нельзя указывать родителем эту же категорию');
		}
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'parent' => array(self::BELONGS_TO, 'Skill', 'parent_id'),
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
			'status' => 'Статус',
			'name' => 'Название',
			'sort' => 'Сортировка',
			'description' => 'Описание',
			'is_root' => 'Главная категория',
			'parent_id' => 'Категория',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;
		$alias = $this->getTableAlias();
		$criteria->compare($alias.'.id', $this->id);
		$criteria->compare($alias.'.status', $this->status);
		$criteria->compare($alias.'.name', $this->name, true);
		$criteria->compare($alias.'.parent_id',$this->parent_id);
		$criteria->with = array('parent');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort' => array(
				'defaultOrder' => $this->getTableAlias().'.id DESC',
			),
			'pagination'=>array(
				'pageSize' => param('adminTableSize', 20),
			),
		));
	}

    public static function getListForAutocomplete(){
        $sql = "SELECT name FROM {{skill}} WHERE status = :status ORDER BY sort, id";
        $command =Yii::app()->db->createCommand($sql);
        return $command->queryColumn(array(':status' => self::STATUS_ACTIVE));
    }

    public static function getList(){
        $sql = "SELECT id, name FROM {{skill}} WHERE status = :status ORDER BY sort, id";
        $command = Yii::app()->db->createCommand($sql);
        $data = $command->queryAll(true, array(':status' => self::STATUS_ACTIVE));
        return CHtml::listData($data, 'id', 'name');
    }

	public static function getModelList()
	{
		$all = Skill::model()->findAll();
		$list = array();
		foreach($all as $model){
			$list[$model->id] = $model;
		}
		return $list;
	}

    public static function getParentList($withRoot = false){
        $sql = "SELECT id, name FROM {{skill}} WHERE status = :status AND parent_id = 0 ORDER BY sort, id";
        $command = Yii::app()->db->createCommand($sql);
        $data = $command->queryAll(true, array(':status' => self::STATUS_ACTIVE));
        $list = CHtml::listData($data, 'id', 'name');
		if($withRoot){
			$list = CMap::mergeArray(array(0 => '/'), $list);
		}
		return $list;
    }

    # Здесь мы делаем выборку категорий, по нужной сортировке (Именно сортировка по родителю играет тут большую роль)
    public static function getCategories($userID = null) {
        $criteria = new CDbCriteria;
        $criteria->order = 'parent_id ASC, id ASC';
		if($userID){
			$userSkills = Yii::app()->db->createCommand('SELECT skill_id FROM {{user_skills}} WHERE user_id=:id')->queryColumn(array(':id' => $userID));
			if($userSkills){
				$criteria->addNotInCondition('id', $userSkills);
			}
		}
        $categories = self::model()->findAll($criteria);
        return self::buildTree($categories);
    }

    # Тут мы создаем дерево категорий, которое будем использовать для реализации наших целей.
    protected static function buildTree(&$data, $rootID = 0) {
        $tree = array();
        foreach ($data as $id => $node) {
            if ($node->parent_id == $rootID) {
                unset($data[$id]);
                $node->childs = self::buildTree($data, $node->id);
                $tree[] = $node;
            }
        }
        return $tree;
    }

    # Основная функция которая и возвращает нужный результат
    public static function getCategoriesListData($userID = null){
        $categoriesTree = self::getCategories($userID);
        $categories = array();
        foreach($categoriesTree as $category) {
            if($category->parent_id == 0) {
                $categories[$category->name] = CHtml::listData($category->childs, 'id', 'name');
            }
        }
        return $categories;
    }

	public static function getUrlBySkillName($name)
	{
		return Yii::app()->createUrl('/project/skill/'.HString::translit($name));
	}

	public function afterDelete()
	{
		Yii::app()->db->createCommand("DELETE FROM {{skill_to_project}} WHERE skill_id=:id;")->execute(array(':id' => $this->id));
		Yii::app()->db->createCommand("DELETE FROM {{user_skills}} WHERE skill_id=:id;")->execute(array(':id' => $this->id));
		Yii::app()->db->createCommand("UPDATE {{project}} SET skills_cache='' WHERE skills_cache!='';")->execute(array(':id' => $this->id));
	}

	public static function getStatusList()
	{
		return array(
			self::STATUS_INACTIVE => 'Не активен',
			self::STATUS_ACTIVE => 'Активен',
			self::STATUS_MODERATION => 'На модерации',
		);
	}

	public function getStatusName()
	{
		$list = self::getStatusList();
		return isset($list[$this->status]) ? $list[$this->status] : '?';
	}

	public function beforeSave()
	{
		if($this->is_root){
			$this->parent_id = 0;
		}

		Yii::app()->cache->delete(HSitebar::CACHE_PROJECT_ITEMS_KEY);

		return parent::beforeSave();
	}

	public function getUrlForProject()
	{
		return Yii::app()->createUrl('/project/main/index', array('skill' => $this->alias));
	}
}