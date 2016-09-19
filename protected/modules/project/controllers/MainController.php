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

class MainController extends BaseUserController {

    public $modelName = 'Project';

    public function filters() {
        return array(
            //'ajaxOnly + requests, LoadCrop, json', // только ajax запросы
            array('AuthFilter - feed, view, index, create, captcha') // доступ к данному разделу только авторизированным
        );
    }

     public function actions()
     {
         return array(
             'feed' => array(
                 'class'        => 'application.components.actions.YFeedAction',
                 'data'         => Project::model()
                     ->forIndex()
                     ->sort()
                     ->scopeBySkillAlias(Yii::app()->request->getParam('skill'), false)
                     ->findAll(),

                 'itemFields'   => array(
                     // author_object, если не задан - у
                     // item-елемента запросится author_nickname
                     'author_object'   => 'owner',
                     'author_nickname' => 'fullName',
                     'title'           => 'title',
                     'content'         => 'preview',
                     'datetime'        => 'date_created',
                     'updated'         => 'date_updated',
                 ),
             ),

             'captcha' => array(
                 'class' => 'CCaptchaAction',
                 'backColor' => 0xFFFFFF,
             ),
         );
     }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        Project::closeByUntil();

        HMenu::setState('project');

        if(!Yii::app()->user->isGuest){
            $this->layout = '//layouts/column2';
        }

        $model = Project::model()->forIndex()->open()->sort();

        $skill = null;
        $skillAlias = Yii::app()->request->getParam('skill');
        if($skillAlias){
            $skill = Skill::model()->findByAttributes(array('alias' => $skillAlias));
            if($skill){
                $model->scopeBySkillID($skill->id);
            } else {
                throw new CHttpException(404);
            }
        }

        $dataProvider = new CActiveDataProvider($model, array(
            'pagination'=>array(
                'pageSize'=>param('indexPageSize', 10),
                'pageVar'=>'page',
            ),
        ));

        $this->categoryItems = HSitebar::getProjectItems();

        $this->render('index', array(
            'skill' => $skill,
            'dataProvider' => $dataProvider,
        ));
    }

	public function actionView($id){
		$project = Project::model()->with('owner')->findByPk($id);

		if(!$project){
			HPage::show404();
		}

		if($project->user_id != Yii::app()->user->id){
			$project->count_view++;
			$project->update('count_view');
		}

		$this->render('view', array('project' => $project));
	}


	public function actionCreate() {
		$project = new Project();
        HMenu::setState('project.create');
		$project->date_open_until = date('d.m.Y', strtotime('+3 month'));

        $newUser = NULL;
        $modelLogin = NULL;
        if(Yii::app()->user->isGuest){
            $newUser = new RegistrationForm();
            $modelLogin = new UserLogin;
            $newUser->iam = RegistrationForm::I_AM_NEW_USER;
        }

        $user = UserModule::user();

		if (isset($_POST['Project'])) {
			$project->attributes = $_POST['Project'];

            $project->status = param('projectModeration') ? Project::STATUS_ON_MODERATION : Project::STATUS_OPEN;

            if(Yii::app()->user->isGuest){
                $newUser->attributes = $_POST['RegistrationForm'];
                $modelLogin->attributes = $_POST['UserLogin'];
                $newUser->scenario = 'create_project';

                if($newUser->iam == RegistrationForm::I_AM_NEW_USER && $project->validate()){

                    if($newUser->validate()){

                        $soucePassword = $newUser->password;
                        $userModule = Yii::app()->getModule('user');
                        
                        $newUser->activkey = UserModule::encrypting(microtime() . $newUser->password);
                        $newUser->password = UserModule::encrypting($newUser->password);
                        $newUser->verifyPassword = UserModule::encrypting($newUser->verifyPassword);
                        $newUser->superuser = 0;
                        $newUser->status = (($userModule->activeAfterRegister) ? User::STATUS_ACTIVE : User::STATUS_NOACTIVE);

                        if($newUser->save()){
                            $profile = new Profile;
                            $profile->regMode = true;
                            $project->user_id = $newUser->id;
                            $project->save();

                            if ($userModule->sendActivationMail) {
                                $activation_url = $this->createAbsoluteUrl('/user/activation/activation', array("activkey" => $newUser->activkey, "email" => $newUser->email));
                                UserModule::sendMail($newUser->email, UserModule::t("You registered from {site_name}", array('{site_name}' => Yii::app()->name)), UserModule::t("Please activate you account go to {activation_url}", array('{activation_url}' => $activation_url)));
                            }

                            if (($userModule->loginNotActiv || ($userModule->activeAfterRegister && $userModule->sendActivationMail == false)) && $userModule->autoLogin) {
                                $identity = new UserIdentity($newUser->username, $soucePassword);
                                $identity->authenticate();
                                Yii::app()->user->login($identity, 0);
                                //$this->redirect($userModule->returnUrl);
                            } else {
                                if (!$userModule->activeAfterRegister && !$userModule->sendActivationMail) {
                                    Yii::app()->user->setFlash('registration', UserModule::t("Thank you for your registration. Contact Admin to activate your account."));
                                } elseif ($userModule->activeAfterRegister && $userModule->sendActivationMail == false) {
                                    Yii::app()->user->setFlash('registration', UserModule::t("Thank you for your registration. Please {{login}}.", array('{{login}}' => CHtml::link(UserModule::t('Login'), $userModule->loginUrl))));
                                } elseif ($userModule->loginNotActiv) {
                                    Yii::app()->user->setFlash('registration', UserModule::t("Thank you for your registration. Please check your email or login."));
                                } else {
                                    Yii::app()->user->setFlash('registration', UserModule::t("Thank you for your registration. Please check your email."));
                                }
                                //$this->refresh();
                            }
                            $user = $newUser;
                        }
                    }
                } elseif($newUser->iam == RegistrationForm::I_AM_OLD_UER && $project->validate()){
                    if($modelLogin->validate()) {
                        HUser::setLastVisit();
                        $user = UserModule::user();
                    }
                }
            }

            if($user){
                $project->user_id = $user->id;

                if ($project->save()) {
                    $success = 'Ваш заказ успешно добавлен.';
                    if($project->status == Project::STATUS_ON_MODERATION){
                        $success .= ' Он будет опубликован после проверки модератором.';
                    }
                    Yii::app()->user->setFlash('success', $success);
                    if(Yii::app()->user->isGuest){
                        $this->redirect(Yii::app()->createUrl('/'));
                    } else {
                        $this->redirect(Yii::app()->createUrl('/project/my'));
                    }
                }
            }
		}

		$this->render('create', array(
            'model' => $project,
            'newUser' => $newUser,
            'modelLogin' => $modelLogin,
        ));
	}

	public function actionUpdate($id) {
		$model = Project::model()->findByPk($id);

		if (isset($_POST['Project'])) {
			$model->attributes = $_POST['Project'];
			if ($model->save()) {

				$this->redirect(Yii::app()->createUrl('/project/my'));
			}
		}
		$this->render('update', array('model' => $model));
	}

	public function actionMy() {
        $model = Project::model()->my();
        HMenu::setState('project.my');

        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Project'])){
            $model->attributes=$_GET['Project'];
        }
        $this->render('my', array(
            'model' => $model,
        ));
	}

    public function actionSetExecutor($projectID, $userID){
        if(Yii::app()->user->isGuest){
            throw new CHttpException(403);
        }

        /*@var Project $project */
        $project = Project::model()->open()->findByPk($projectID);

        $user = User::model()->active()->findByPk($userID);

        if(!$project || !$user){
            throw new CHttpException(404, 'Неверные данные');
        }

        if($project->owner->id != Yii::app()->user->id){
            throw new CHttpException(403);
        }

        $project->executor_id = $userID;
        $project->status = Project::STATUS_IN_WORK;
        if($project->update(array('executor_id', 'status'))){
            Yii::app()->user->setFlash('success', 'Исполнитель успешно выбран');
        } else {
            Yii::app()->user->setFlash('error', 'Ошибка. Попробуйте позже');
        }

        $this->redirect(Yii::app()->createUrl('/project/view', array('id' => $projectID)));
    }

    public function actionUnsetExecutor($projectID, $userID){
        if(Yii::app()->user->isGuest){
            throw new CHttpException(403);
        }

        /*@var Project $project */
        $project = Project::model()->findByPk($projectID);

        $user = User::model()->active()->findByPk($userID);

        if(!$project || !$user){
            throw new CHttpException(404, 'Неверные данные');
        }

        if($project->owner->id != Yii::app()->user->id){
            throw new CHttpException(403, 'Вы не можете этого');
        }

        $project->executor_id = 0;
        $project->status = Project::STATUS_OPEN;
        if($project->update(array('executor_id', 'status'))){
            Yii::app()->user->setFlash('success', 'Исполнитель успешно отклонен');
        } else {
            Yii::app()->user->setFlash('error', 'Ошибка. Попробуйте позже');
        }

        $this->redirect(Yii::app()->createUrl('/project/view', array('id' => $projectID)));
    }
}