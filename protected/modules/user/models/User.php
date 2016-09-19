<?php

/**
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property integer $type
 * @property integer $activity
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $activkey
 * @property string $ava
 * @property integer $createtime
 * @property integer $lastvisit
 * @property integer $superuser
 * @property integer $status
 * @property integer $country_id
 * @property integer $region_id
 * @property integer $city_id
 * @property integer $only_yii
 * @property integer $salary_per_hour
 * @property integer $count_view
 * @property integer $sbs_newMess
 * @property integer $sbs_newRequest
 * @property integer $sbs_newReview
 * @property timestamp $create_at
 * @property timestamp $lastvisit_at
 * @property Profile $profile
 * @property array $userSkills
 * @property array $portfolios
 */
class User extends CActiveRecord
{
    public $iam;

	const STATUS_NOACTIVE=0;
	const STATUS_ACTIVE=1;
	const STATUS_BANNED=-1;

	//TODO: Delete for next version (backward compatibility)
	const STATUS_BANED=-1;

    const AVA_PREFIX = 'ava_';

    const TYPE_PROGRAMMER = 1;
    const TYPE_CUSTOMER = 2;

    private static $_types = array(
        self::TYPE_PROGRAMMER => 'Программист',
        self::TYPE_CUSTOMER => 'Заказчик',
    );

    const ACTIVITY_FREE = 0;
    const ACTIVITY_BUSY = 1;
    const ACTIVITY_HIDE = 2;

    private static $_activity = array(
        self::ACTIVITY_FREE => 'Свободен',
        self::ACTIVITY_BUSY => 'Занят',
        //self::ACTIVITY_HIDE => 'Невидим',
    );

    public static function getListTypes() {
        return self::$_types;
    }

    public function getTypeName() {
        return isset(self::$_types[$this->type]) ? self::$_types[$this->type] : '';
    }

    public static function getActivityList() {
        return self::$_activity;
    }

    public function getActivityName($withTag = true){
        $tagOpen = '';
        $tagClose = '';

        if($withTag){
            switch($this->activity){
                case self::ACTIVITY_BUSY:
                    $tagOpen = '<span class="label label-inverse">';
                    $tagClose = '</span>';
                    break;

                case self::ACTIVITY_FREE:
                    $tagOpen = '<span class="label label-success">';
                    $tagClose = '</span>';
                    break;

                case self::ACTIVITY_HIDE:
                    $tagOpen = '<span class="label">';
                    $tagClose = '</span>';
                    break;
            }
        }
        return isset(self::$_activity[$this->activity]) ? $tagOpen . self::$_activity[$this->activity] . $tagClose : '';
    }

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
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
		return Yii::app()->getModule('user')->tableUsers;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return ( Yii::app()->getModule('user')->isAdmin() ? array(
			array('username', 'length', 'max'=>20, 'min' => 3,'message' => UserModule::t("Incorrect username (length between 3 and 20 characters).")),
			array('password', 'length', 'max'=>128, 'min' => 4,'message' => UserModule::t("Incorrect password (minimal length 4 symbols).")),
            array('superuser, status, type, country_id, region_id, city_id, only_yii, salary_per_hour, activity, count_view, sbs_newMess, sbs_newRequest, sbs_newReview', 'numerical', 'integerOnly'=>true),
            array('ava, city_name', 'length', 'max'=>128),
			array('email', 'email'),
			array('username', 'unique', 'message' => UserModule::t("This user's name already exists.")),
			array('email', 'unique', 'message' => UserModule::t("This user's email address already exists.")),
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u','message' => UserModule::t("Incorrect symbols (A-z0-9).")),
			array('status', 'in', 'range'=>array(self::STATUS_NOACTIVE, self::STATUS_ACTIVE, self::STATUS_BANNED)),
			array('superuser', 'in', 'range'=>array(0,1)),
            array('create_at', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
            array('lastvisit_at', 'default', 'value' => '0000-00-00 00:00:00', 'setOnEmpty' => true, 'on' => 'insert'),
			array('username, email, superuser, status', 'required'),
			array('id, username, password, email, activkey, create_at, lastvisit_at, superuser, status', 'safe', 'on'=>'search'),
        ) : ( (Yii::app()->user->id==$this->id) ? array(
            array('superuser, status, type, country_id, region_id, city_id, only_yii, salary_per_hour, activity, count_view, sbs_newMess, sbs_newRequest, sbs_newReview', 'numerical', 'integerOnly'=>true),
			array('username, email', 'required'),
			array('username', 'length', 'max'=>20, 'min' => 3,'message' => UserModule::t("Incorrect username (length between 3 and 20 characters).")),
            array('ava, city_name', 'length', 'max'=>128),
			array('email', 'email'),
			array('username', 'unique', 'message' => UserModule::t("This user's name already exists.")),
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u','message' => UserModule::t("Incorrect symbols (A-z0-9).")),
			array('email', 'unique', 'message' => UserModule::t("This user's email address already exists.")),
		):array()));
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
        $relations = Yii::app()->getModule('user')->relations;
        if (!isset($relations['profile']))
            $relations['profile'] = array(self::HAS_ONE, 'Profile', 'user_id');

		$relations['country'] = array(self::BELONGS_TO, 'Country', 'country_id');
		$relations['region'] = array(self::BELONGS_TO, 'Region', 'region_id');
		$relations['city'] = array(self::BELONGS_TO, 'City', 'city_id');

        $relations['userSkills'] = array(self::HAS_MANY, 'UserSkills', 'user_id');

        $relations['portfolios'] = array(self::HAS_MANY, 'UserPortfolio', 'user_id', 'condition' => 'portfolios.status = '.UserPortfolio::STATUS_OPEN);
        $relations['countPortfolio'] = array(self::STAT, 'UserPortfolio', 'user_id', 'condition' => 'status = '.UserPortfolio::STATUS_OPEN);

        return $relations;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => UserModule::t("Id"),
			'username'=>UserModule::t("username"),
			'password'=>UserModule::t("password"),
			'verifyPassword'=>UserModule::t("Retype Password"),
			'email'=>UserModule::t("E-mail"),
			'verifyCode'=>UserModule::t("Verification Code"),
			'activkey' => UserModule::t("activation key"),
			'createtime' => UserModule::t("Registration date"),
			'create_at' => UserModule::t("Registration date"),

			'lastvisit_at' => UserModule::t("Last visit"),
			'superuser' => UserModule::t("Superuser"),
			'status' => UserModule::t("Status"),
            'type' => 'Вы',
            'only_yii' => 'Интересуют проекты только на Yii',
            'salary_per_hour' => 'Тариф',
            'activity' => 'Статус',
            'sbs_newMess' => 'о новом сообщении',
            'sbs_newRequest' => 'о новой заявке к моему заказу',
            'sbs_newReview' => 'о новом отзыве',
		);
	}

	public function scopes()
    {
        return array(
            'active'=>array(
                'condition'=>$this->getTableAlias() . '.status='.self::STATUS_ACTIVE,
            ),
            'programmers'=>array(
                'condition'=>$this->getTableAlias() . '.type='.self::TYPE_PROGRAMMER,
            ),
            'notactive'=>array(
                'condition'=>$this->getTableAlias() . '.status='.self::STATUS_NOACTIVE,
            ),
            'banned'=>array(
                'condition'=>$this->getTableAlias() . '.status='.self::STATUS_BANNED,
            ),
            'superuser'=>array(
                'condition'=>'superuser=1',
            ),
            'notsafe'=>array(
            	'select' => 'id, username, password, email, activkey, create_at, lastvisit_at, superuser, status',
            ),
            'sort'=>array(
                'order'=>'create_at DESC',
            ),
        );
    }

	public function defaultScope()
    {
        return CMap::mergeArray(Yii::app()->getModule('user')->defaultScope, array(
            'alias'=>'user',
            //'select' => 'user.id, user.username, user.email, user.create_at, user.lastvisit_at, user.superuser, user.status, user.country_id, user.region_id, user.city_id',
        ));
    }

	public static function itemAlias($type,$code=NULL) {
		$_items = array(
			'UserStatus' => array(
				self::STATUS_NOACTIVE => UserModule::t('Not active'),
				self::STATUS_ACTIVE => UserModule::t('Active'),
				self::STATUS_BANNED => UserModule::t('Banned'),
			),
			'AdminStatus' => array(
				'0' => UserModule::t('No'),
				'1' => UserModule::t('Yes'),
			),
		);
		if (isset($code))
			return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
		else
			return isset($_items[$type]) ? $_items[$type] : false;
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
        $criteria->compare('username',$this->username,true);
        $criteria->compare('password',$this->password);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('activkey',$this->activkey);
        $criteria->compare('create_at',$this->create_at);
        $criteria->compare('lastvisit_at',$this->lastvisit_at);
        $criteria->compare('superuser',$this->superuser);
        $criteria->compare('status',$this->status);

        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
        	'pagination'=>array(
				'pageSize'=>Yii::app()->getModule('user')->user_page_size,
			),
        ));
    }

    public function getCreatetime() {
        return strtotime($this->create_at);
    }

    public function setCreatetime($value) {
        $this->create_at=date('Y-m-d H:i:s',$value);
    }

    public function getLastvisit() {
        return strtotime($this->lastvisit_at);
    }

    public function setLastvisit($value) {
        $this->lastvisit_at=date('Y-m-d H:i:s',$value);
    }

	public function getCountryName(){
		return isset($this->country) ? $this->country->name : NULL;
	}

	public function getRegionName(){
		return isset($this->region) ? $this->region->name : NULL;
	}

	public function getCityName(){
		return isset($this->city) ? $this->city->name : NULL;
	}

	public function getDateRegistrationFormatted() {
		return HDate::getSmart(strtotime($this->create_at));
	}


	public function getDateLastVisitFormatted() {
		return HDate::getAwait(strtotime($this->lastvisit_at)) . ' назад';
	}

    public function getFullName() {
        if(isset($this->profile) && $this->profile->firstname) {
            return $this->profile->firstname . ' ' . $this->profile->lastname;
        }
        return $this->username;
    }

    public function getUrl($absolute = false) {
        //return Yii::app()->createUrl('/user/user/view', array('id' => $this->id));
        if($absolute){
            return Yii::app()->createAbsoluteUrl('/user/user/view', array('un' => $this->username));
        }else{
            return Yii::app()->createUrl('/user/user/view', array('un' => $this->username));
        }
    }

    public function getLink2Profile() {
        return CHtml::link($this->getFullName(), $this->getUrl());
    }

    public function renderAva($linkToProfile = true, $sizeClass = ''){
        echo '<div class="user-ava" id="user-ava-'.$this->id.'">';

        echo '<div class="user-ava-crop">';

        if($linkToProfile){
            echo '<a href="'.$this->getUrl().'">';
        }else{
            echo '<a href="'.$this->getAvaSrc().'" rel="prettyPhoto">';
        }
        if($this->ava){
            echo CHtml::image($this->getAvaSrcThumb(), $this->username, array('class' => 'message_ava '.$sizeClass));
        } else {
            Yii::app()->controller->widget('ext.yii-gravatar.YiiGravatar', array(
                'email' => $this->email,
                'size' => 50,
                //'defaultImage' => Yii::app()->createAbsoluteUrl('/images/default-ava.jpg'),
                'secure' => false,
                'rating' => 'r',
                'emailHashed' => false,
                'htmlOptions' => array(
                    'alt' => $this->username,
                    'title' => $this->username,
                    'class' => 'message_ava '.$sizeClass
                )
            ));
        }
        echo '</a>';
        echo '</div>';

        echo '</div>';
    }

    public function getAvaSrc() {
        $url = HUser::getUploadUrl($this, HUser::UPLOAD_AVA);

        return $url . '/' . $this->ava;
    }

    public function getAvaSrcThumb() {
        $url = HUser::getUploadUrl($this, HUser::UPLOAD_AVA);

        return $url . '/' . self::AVA_PREFIX . $this->ava;
    }

    public function isProgrammer() {
        return $this->type == User::TYPE_PROGRAMMER;
    }

    public function getSkillString() {
        $skillArr = array();
        if($this->userSkills){
            /** @var UserSkills $userSkill */
            foreach($this->userSkills as $userSkill){
                $nameArr = array();
                $nameArr[] = $userSkill->skill->name;
                if($userSkill->level != UserSkills::LEVEL_NO_SHOW){
                    $nameArr[] = $userSkill->getLevelName();
                }
                if($userSkill->experience != UserSkills::EXPERIENCE_NO_SHOW){
                    $nameArr[] = $userSkill->getExperienceName();
                }

                $skillArr[] = implode(', ', $nameArr);
            }
        }

        return implode('<br>', $skillArr);
    }

    public function getPreview(){
        if(isset($this->profile)){
            return HString::truncate($this->profile->about_us);
        }
        return '';
    }

    public function isAdmin(){
        return $this->superuser == 1;
    }

    public function beforeSave() {
        if($this->isNewRecord){
            $this->sbs_newMess = 1;
            $this->sbs_newRequest = 1;
            $this->sbs_newReview = 1;
        }

        if($this->city_name){
            $city = City::model()->findByAttributes(array('name' => $this->city_name));
            if($city){
                $this->city_id = $city->id;
                $this->region_id = $city->region_id;
                $this->country_id = $city->country_id;
            } else {
                $this->city_id = 0;
                $this->region_id = 0;
                $this->country_id = 0;
            }
        }

        return parent::beforeSave();
    }

    public function afterDelete()
    {
        $sql = 'DELETE FROM {{user_skills}} WHERE user_id=:id';
        Yii::app()->db->createCommand($sql)->execute(array(':id' => $this->id));

        $sql = 'DELETE FROM {{project}} WHERE user_id=:id';
        Yii::app()->db->createCommand($sql)->execute(array(':id' => $this->id));

        $sql = 'DELETE FROM {{review}} WHERE sender_id=:id OR recipient_id=:id';
        Yii::app()->db->createCommand($sql)->execute(array(':id' => $this->id));

        $sql = 'DELETE FROM {{dialog}} WHERE sender_id=:id OR recipient_id=:id';
        Yii::app()->db->createCommand($sql)->execute(array(':id' => $this->id));

        $sql = 'DELETE FROM {{message}} WHERE sender_id=:id OR recipient_id=:id';
        Yii::app()->db->createCommand($sql)->execute(array(':id' => $this->id));

        $portfolio = UserPortfolio::model()->findByAttributes(array('user_id' => $this->id));
        if($portfolio)
            $portfolio->delete();

        $profile = Profile::model()->findByPk($this->id);
        if($profile)
            $profile->delete();
    }
}