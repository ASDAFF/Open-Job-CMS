<?php
class InstallForm extends CFormModel {
	public $agreeLicense;
	public $dbHost = 'localhost';
	public $dbUser = 'root';
	public $dbPort = '3306';
	public $dbPass;
	public $dbName;
	public $dbPrefix = 'ojc_';

	public $adminPass;
	public $adminLogin;
	public $adminEmail;

	public function rules()	{
		return array(
			array('dbUser, dbHost, dbName, adminPass, adminEmail, adminLogin, dbPrefix', 'required'),
			array('agreeLicense', 'required', 'requiredValue' => true, 'message'=>'Вы должны согласиться с "лицензионным соглашением"'),
			array('adminEmail', 'email'),
			array('dbUser, dbPass, dbName', 'length', 'max' => 30),
			array('dbHost', 'length', 'max' => 50),
			array('adminPass', 'length', 'max' => 20, 'min' => 6),
			array('dbPort', 'length', 'max' => 5),
			array('dbPort', 'numerical', 'allowEmpty' => true, 'integerOnly' => true),
			array('dbPrefix', 'length', 'max' => 7, 'min' => 1),
			array('dbPrefix', 'match', 'pattern' => '#^[a-zA-Z0-9_]{1,7}$#', 'message'=> 'Разрешается использовать знаки  "a-zA-Z0-9_" без пробелов'),
			array('dbPrefix, dbPort', 'safe'),
			array('adminLogin', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u','message' => UserModule::t("Incorrect symbols (A-z0-9).")),
		);
	}

	public function attributeLabels() {
		return array(
			'agreeLicense' => 'Я согласен с ' . CHtml::link('лицензионным соглашением', '#popup1',
					array('class'=>'fancy mgp-open-inline')),
			'dbHost' => 'Сервер базы данных',
			'dbPort' => 'Порт базы данных',
			'dbUser' => 'Имя пользователя БД',
			'dbPass' => 'Пароль пользователя БД',
			'dbName' => 'Имя базы данных',
			'dbPrefix' => 'Префикс для таблиц',
			'adminPass' => 'Пароль администратора',
			'adminLogin' => 'Логин администратора',
			'adminEmail' => 'Email администратора',
		);
	}
}