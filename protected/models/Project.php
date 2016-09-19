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
 * This is the model class for table "{{project}}".
 *
 * The followings are the available columns in table '{{project}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $executor_id
 * @property string $title
 * @property string $description
 * @property integer $budget_id
 * @property integer $budget
 * @property integer $payment_type
 * @property string $date_open_until
 * @property integer $count_view
 * @property string $date_created
 * @property string $date_updated
 * @property string $skills_cache
 * @property integer $status
 * @property integer $budget_agreement
 * @property User $owner
 */
class Project extends CActiveRecord
{
	const STATUS_DELETE = 0;
	const STATUS_OPEN = 1;
	const STATUS_CLOSE = 2;
	const STATUS_IN_WORK = 3;
	const STATUS_COMPLETE = 4;
	const STATUS_ON_MODERATION = 5;

	const PAY_TYPE_INTERNET = 1;
	const PAY_TYPE_CASH = 2;
	const PAY_TYPE_BANK = 3;
	const PAY_TYPE_USER_CHOICE = 4;

	const CURRENCY = 'руб.';

	public $verifyCode;

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
	 * @return Project the static model class
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
		return '{{project}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, description, payment_type', 'required'),
			array('budget_id, budget_agreement, user_id, budget, payment_type, count_view, status', 'numerical', 'integerOnly'=>true),
			array('title, date_open_until', 'length', 'max'=>50),
            array('budget', 'checkBudget'),
			array('id, title, description, budget_id, budget, payment_type, date_open_until, count_view, date_created, date_updated, status', 'safe', 'on'=>'search'),

			array('verifyCode', Yii::app()->user->isGuest ? 'required' : 'safe'),
			array('verifyCode', 'captcha', 'allowEmpty'=> !Yii::app()->user->isGuest),
		);
	}

    public function checkBudget() {
        if(!$this->budget_agreement && $this->budget <= 0){
            $this->addError('budget', 'Бюджет должен быть больше нуля');
        }
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
            'owner' => array(self::BELONGS_TO, 'User', 'user_id'),
            'executor' => array(self::BELONGS_TO, 'User', 'executor_id'),
            'countRequest' => array(self::STAT, 'Message', 'object_id', 'condition' => 'object_name = "Project"'),
            'skills' => array(self::MANY_MANY, 'Skill', '{{skill_to_project}}(project_id, skill_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Название',
			'description' => 'Описание',
			'budget_id' => 'Бюджет',
			'budget' => 'Бюджет',
			'payment_type' => 'Способ оплаты',
			'date_open_until' => 'Открыто до',
			'count_view' => 'Кол-во просмотров',
			'date_created' => 'Дата создания',
			'date_updated' => 'Дата обновления',
			'status' => 'Статус',
            'budget_agreement' => 'по догворенности',
            'skillsSave' => 'Навыки',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('budget_id',$this->budget_id);
		$criteria->compare('budget',$this->budget);
		$criteria->compare('payment_type',$this->payment_type);
		$criteria->compare('date_open_until',$this->date_open_until,true);
		$criteria->compare('count_view',$this->count_view);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('date_updated',$this->date_updated,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort' => array(
                'defaultOrder' => 'date_created DESC',
            ),
			'pagination'=>array(
				'pageSize' => param('adminTableSize', 20),
			),
		));
	}

	public function scopes() {
		return array(
			'my' => array(
				'condition' => 'user_id=:user_id',
				'params' => array(':user_id' => Yii::app()->user->id),
			),
			'open' => array(
				'condition' => 'status=:status',
				'params' => array(':status' => Project::STATUS_OPEN),
			),
            'notDeleted' => array(
				'condition' => 'status!=:status',
				'params' => array(':status' => Project::STATUS_DELETE),
			),
            'forIndex' => array(
				'condition' => 'status NOT IN (:status_del, :status_close, :status_moder)',
				'params' => array(
                    ':status_del' => Project::STATUS_DELETE,
                    ':status_close' => Project::STATUS_CLOSE,
                    ':status_moder' => Project::STATUS_ON_MODERATION,
                ),
			),
            'sort' => array(
                'order' => 'date_created DESC',
            ),
		);
	}

	public function scopeBySkillAlias($alias = '', $exception = true) {
		$skill = null;
		if($alias){
			$skill = Skill::model()->findByAttributes(array('alias' => $alias));
		}

		if($skill){
			return $this->scopeBySkillID($skill->id);
		} elseif ($exception){
			throw new CHttpException(404);
		}

		return $this;
	}

	public function scopeBySkillID($skillID) {
		$this->getDbCriteria()->mergeWith(array(
			'condition' => 's.skill_id = :skill_id',
			'join' => 'INNER JOIN {{skill_to_project}} s ON s.project_id=t.id',
			'params' => array(':skill_id' => $skillID)
		));

		return $this;
	}

	public static function getPayTypeArray(){
		return array(
			self::PAY_TYPE_INTERNET => 'Электронные платежные системы',
			self::PAY_TYPE_CASH => 'Наличный расчет',
			self::PAY_TYPE_BANK => 'Безналичный расчет',
			self::PAY_TYPE_USER_CHOICE => 'На выбор исполнителя',
		);
	}

	private static $_statusArr = array(
		self::STATUS_OPEN => 'Открыт',
		self::STATUS_IN_WORK => 'В работе',
		self::STATUS_COMPLETE => 'Выполнен',
		self::STATUS_CLOSE => 'Закрыт',
		self::STATUS_ON_MODERATION => 'На модерации',
	);

	public static function getStatusList(){

		return self::$_statusArr;
	}

	public function getStatusName(){
		return isset(self::$_statusArr[$this->status]) ? self::$_statusArr[$this->status] : '';
	}

	protected function beforeSave() {
		if(!$this->user_id){
			$this->user_id = Yii::app()->user->id;
		}
		if(!$this->status){
			$this->status = self::STATUS_OPEN;
		}

        $this->description = HString::clearHtml($this->description);

		if(parent::beforeSave()) {
			$this->date_open_until = date(HDate::MYSQL_FORMAT, strtotime($this->date_open_until));
			return true;
		} else {
			return false;
		}
	}

    public function afterSave() {
        if (isset($_POST['Project']['skillsSave'])) {
            $sql = "DELETE FROM {{skill_to_project}} WHERE project_id=:project_id";
            Yii::app()->db->createCommand($sql)->execute(array('project_id' => $this->id));

            foreach ($_POST['Project']['skillsSave'] as $skill_id) {
                $sql = "INSERT INTO {{skill_to_project}} SET project_id=:project_id, skill_id=:skill_id";
                Yii::app()->db->createCommand($sql)
                    ->execute(array(':skill_id' => $skill_id, ':project_id' => $this->id));
            }

            $criteria = new CDbCriteria();
            $criteria->addInCondition('id', $_POST['Project']['skillsSave']);
            $skills = Skill::model()->findAll($criteria);

            if($skills){
                self::updateSkillCache($skills, $this->id);
            }
        }
        return parent::afterSave();
    }

	private static function updateSkillCache($skills, $id){
		$sql = "UPDATE {{project}} SET skills_cache=:name WHERE id=:id";
		Yii::app()->db->createCommand($sql)->execute(array(
			':name' => implode('|', CHtml::listData($skills, 'id', 'name')),
			':id' => $id,
		));
	}

    public function getSkillsSave(){
        if(!$this->isNewRecord && $this->skills){
            return CHtml::listData($this->skills, 'id', 'id');
        }
        return array();
    }

	public function getUrl($absolute = false) {
        if($absolute){
            return Yii::app()->createAbsoluteUrl('/project/main/view', array('id' => $this->id));
        }else{
            return Yii::app()->createUrl('/project/main/view', array('id' => $this->id));
        }
	}

    public function getBudgetString() {
        if($this->budget_agreement){
            return $this->getAttributeLabel('budget_agreement');
        }

        return $this->budget . ' ' . Project::getCurrencyName();
    }

    public static function closeByUntil() {
        $sql = "UPDATE {{project}} SET status=:status_close WHERE date_open_until <= NOW() AND status = :status_open";
        return Yii::app()->db->createCommand($sql)
            ->execute(array(
                ':status_open' => Project::STATUS_OPEN,
                ':status_close' => Project::STATUS_CLOSE,
            ));
    }

    public function getPreview(){
        return HString::truncate($this->description, 20);
    }

    public function getSkillsString(){
        $skillsLabels = array();
        if($this->skills_cache){
            if(strpos($this->skills_cache, '|') !== false){
                $skills = explode('|', $this->skills_cache);
            } else {
                $skills = array($this->skills_cache);
            }

            foreach($skills as $skillName){
                $skillsLabels[] = '<span class="label label-info">' . $skillName . '</span>';
            }
        } elseif($this->skills) {
			self::updateSkillCache($this->skills, $this->id);
			foreach($this->skills as $skill){
				$skillsLabels[] = '<span class="label label-info">' . $skill->name . '</span>';
			}
		}

        return implode(' ', $skillsLabels);
    }

    public function afterDelete(){
        $sql = "DELETE FROM {{skill_to_project}} WHERE project_id=:id";
        Yii::app()->db->createCommand($sql)->execute(array(':id' => $this->id));

        return parent::afterDelete();
    }

	public static function getCurrencyName()
	{
		return param('currency_name');
	}
}