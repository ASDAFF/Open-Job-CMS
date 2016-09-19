<?php
class MainController extends Controller {
	public $modelName = 'installForm';
    public $layout='/layouts/install';

    public function beforeAction($action){
        if (file_exists(ALREADY_INSTALL_FILE)) throw new CHttpException(404);

        return parent::beforeAction($action);
    }

	public function actionIndex(){

        $model=new InstallForm;

        if(isset($_POST['ajax']) && $_POST['ajax']==='install-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        $this->checkRequirements();
        $this->checkRightFile();

        if(isset($_POST['InstallForm'])) {
            $model->attributes=$_POST['InstallForm'];
            if($model->validate())
            {
                // form inputs are valid, do something here
                try
                {
                    $ds = DIRECTORY_SEPARATOR;
                    $dbConfFile = Yii::app()->basePath . "{$ds}config{$ds}db.php";
                    $sqlFile = $this->module->basePath . "{$ds}data{$ds}database.sql";
                    $sqlFileLocation = $this->module->basePath . "{$ds}data{$ds}location.sql";

					$connectionString = "mysql:host={$model->dbHost};dbname={$model->dbName};port={$model->dbPort}";
                    $connection = new CDbConnection($connectionString, $model->dbUser, $model->dbPass);
                    $connection->connectionString = $connectionString;
                    $connection->username = $model->dbUser;
                    $connection->password = $model->dbPass;
                    $connection->emulatePrepare = true;
                    $connection->charset = 'utf8';
                    $connection->tablePrefix = $model->dbPrefix;
                    $connection->active = true;

                    Yii::app()->setComponent('db', $connection);

                    $dbParams = array(
                        'class' => 'CDbConnection',
                        'connectionString' => $connectionString,
                        'username' => $model->dbUser,
                        'password' => $model->dbPass,
                        'emulatePrepare' => true,
                        'charset' => 'utf8',
                        'enableParamLogging' => false,
                        'enableProfiling' => false,
                        'schemaCachingDuration' => 7200,
                        'tablePrefix' => $model->dbPrefix
                    );

                    $dbConfString = "<?php\n return " . var_export($dbParams, true) . " ;\n?>";

                    $fh = fopen($dbConfFile, 'w+');

                    if (!$fh) {
                        $model->addError('', "Не могу открыть файл '{$dbConfFile}' для записи!");
                    } else {

                        fwrite($fh, $dbConfString);
                        fclose($fh);
                        @chmod($dbConfFile, 0666);

                        $sql = file_get_contents($sqlFile);
                        $sql = str_replace('{dbPrefix}', $model->dbPrefix, $sql);

                        $arrReplace = array(
                            '{adminPass}',
                            '{adminEmail}',
                            '{adminLogin}'
                        );
                        $adminPass = UserModule::encrypting($model->adminPass);
                        $arrReplaceVal = array(
                            $adminPass,
                            $model->adminEmail,
                            $model->adminLogin,
                        );
                        $sql = str_replace($arrReplace, $arrReplaceVal, $sql);

                        $command = Yii::app()->db->createCommand($sql);
                        $command->execute();
                        sleep(2);

                        $sql = file_get_contents($sqlFileLocation);
                        $sql = str_replace('{dbPrefix}', $model->dbPrefix, $sql);
                        $command = Yii::app()->db->createCommand($sql);
                        $command->execute();
                        sleep(4);

                        Yii::app()->cache->flush();

                        if (isset($_SERVER['HTTP_HOST']) || isset($_SERVER['REQUEST_URI'])) {
                            if (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI']))
                                @file_get_contents('http://bc.monoray.ru/license.php?host='.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
                            elseif (isset($_SERVER['HTTP_HOST']))
                                @file_get_contents('http://bc.monoray.ru/license.php?host='.$_SERVER['HTTP_HOST']);
                        }

                        if(!@file_put_contents(ALREADY_INSTALL_FILE, 'ready')) {
                            Yii::app()->user->setFlash('notice', 'Не удалось создать файл protected\runtime\already_install, для избежания повторной установки, пожалуйста, создайте его самостоятельно.');
                        } else {
                            Yii::app()->user->setFlash('success', 'Установка завершена');
                        }
                        $this->redirect(Yii::app()->createUrl('/project/main/index'));
                    }

                } catch (Exception $e) {
                    $model->addError('', $e->getMessage());
                }

            }
        }

		$this->render('/index', array('model'=>$model));
	}

    public function actionSuccess() {
        $this->render('/success');
    }

    public function checkRequirements() {

        $requirements = array(
			array(
				Yii::t('yii', 'PHP version'),
				true,
				version_compare(PHP_VERSION, "5.3.0", ">="),
				'<a href="http://www.yiiframework.com">Yii Framework</a>',
				Yii::t('yii', 'PHP 5.3.0 or higher is required.')),
			array(
				Yii::t('yii', 'Reflection extension'),
				true,
				class_exists('Reflection', false),
				'<a href="http://www.yiiframework.com">Yii Framework</a>',
				''),
			array(
				Yii::t('yii', 'PCRE extension'),
				true,
				extension_loaded("pcre"),
				'<a href="http://www.yiiframework.com">Yii Framework</a>',
				''),
			array(
				Yii::t('yii', 'SPL extension'),
				true,
				extension_loaded("SPL"),
				'<a href="http://www.yiiframework.com">Yii Framework</a>',
				''),
			array(
				Yii::t('yii', 'DOM extension'),
				false,
				class_exists("DOMDocument", false),
				'<a href="http://www.yiiframework.com/doc/api/CHtmlPurifier">CHtmlPurifier</a>, <a href="http://www.yiiframework.com/doc/api/CWsdlGenerator">CWsdlGenerator</a>',
				''),
			array(
				Yii::t('yii', 'PDO extension'),
				true,
				extension_loaded('pdo'),
				Yii::t('yii', 'All <a href="http://www.yiiframework.com/doc/api/#system.db">DB-related classes</a>'),
				''),
			array(
				Yii::t('yii', 'PDO MySQL extension'),
				true,
				extension_loaded('pdo_mysql'),
				Yii::t('yii', 'All <a href="http://www.yiiframework.com/doc/api/#system.db">DB-related classes</a>'),
				Yii::t('yii', 'This is required if you are using MySQL database.')),
			array(
				Yii::t('yii', 'PDO PostgreSQL extension'),
				false,
				extension_loaded('pdo_pgsql'),
				Yii::t('yii', 'All <a href="http://www.yiiframework.com/doc/api/#system.db">DB-related classes</a>'),
				Yii::t('yii', 'This is required if you are using PostgreSQL database.')),
			array(
				Yii::t('yii', 'Memcache extension'),
				false,
				extension_loaded("memcache"),
				'<a href="http://www.yiiframework.com/doc/api/CMemCache">CMemCache</a>',
				''),
			array(
				Yii::t('yii', 'APC extension'),
				false,
				extension_loaded("apc"),
				'<a href="http://www.yiiframework.com/doc/api/CApcCache">CApcCache</a>',
				''),
			array(
				Yii::t('yii', 'Mcrypt extension'),
				false,
				extension_loaded("mcrypt"),
				'<a href="http://www.yiiframework.com/doc/api/CSecurityManager">CSecurityManager</a>',
				Yii::t('yii', 'This is required by encrypt and decrypt methods.')),
			array(
				Yii::t('yii', 'SOAP extension'),
				false,
				extension_loaded("soap"),
				'<a href="http://www.yiiframework.com/doc/api/CWebService">CWebService</a>, <a href="http://www.yiiframework.com/doc/api/CWebServiceAction">CWebServiceAction</a>',
				'')
		);

        $result = 1;

        foreach ($requirements as $i => $requirement)
        {
            if ($requirement[1] && !$requirement[2])
            {
                $result = 0;
            }
            else if ($result > 0 && !$requirement[1] && !$requirement[2])
            {
                $result = -1;
            }
            if ($requirement[4] === '')
            {
                $requirements[$i][4] = '&nbsp;';
            }
        }

        $arr = array(
            'result'=>$result,
            'requirements'=>$requirements
        );

        if ($result == 0) {
            $this->render('/requirements', array('req'=>$arr));
            Yii::app()->end();
        } else {
            return $arr;
        }
    }

    function checkRightFile() {
        $ds = DIRECTORY_SEPARATOR;
        $aCheckDir = array(
            ROOT_PATH . $ds . 'assets',
            ROOT_PATH . $ds . 'protected'.$ds.'runtime',
            ROOT_PATH . $ds . 'protected'.$ds.'config'.$ds.'db.php',
            ROOT_PATH . $ds . 'uploads',
			ROOT_PATH . $ds . 'uploads'.$ds.'ava',
			ROOT_PATH . $ds . 'uploads'.$ds.'portfolio',
        );
        $aCheckDirErr = array(
            'err' => 0
        );
        foreach($aCheckDir as $sDirPath) {
            if(is_writable($sDirPath)) {
                $aCheckDirErr['dirs'][$sDirPath] = 'ok';
            } else {
                $aCheckDirErr['err']++;
				if(is_file($sDirPath)){
					$aCheckDirErr['dirs'][$sDirPath] = 'Необходимо установить права 666';
				} else {
					$aCheckDirErr['dirs'][$sDirPath] = 'Необходимо установить права 777';
				}
            }
        }
        if ($aCheckDirErr['err'] > 0) {
            $this->render('/right_file', array('aCheckDirErr'=>$aCheckDirErr));
            Yii::app()->end();
        } else {
            return $aCheckDirErr;
        }
    }

}