<?php

class ProfileController extends BaseUserController {
	public $defaultAction = 'profile';

	/**
	 * Shows a particular model.
	 */
	public function actionProfile() {
		HMenu::setState('user.profile');

		$model = $this->loadUser();
		$this->render('profile', array(
			'model' => $model,
			'profile' => $model->profile,
		));
	}


	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionEdit() {
		HMenu::setState('user.edit');

		$model = $this->loadUser();
		$profile = $model->profile;

		// ajax validator
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'profile-form') {
			echo UActiveForm::validate(array($model, $profile));
			Yii::app()->end();
		}

		if (isset($_POST['User'])) {

			$model->attributes = $_POST['User'];
			$profile->attributes = $_POST['Profile'];

			if ($model->validate() && $profile->validate()) {
				$model->save();
				$profile->save();
				Yii::app()->user->updateSession();
				Yii::app()->user->setFlash('success', UserModule::t("Changes are saved."));
				$this->redirect($model->getUrl());
			} else {
				$profile->validate();
			}
		}

		$this->render('edit', array(
			'model' => $model,
			'profile' => $profile,
		));
	}

	/**
	 * Change password
	 */
	public function actionChangepassword() {
		HMenu::setState('user.changepassword');

		$model = new UserChangePassword;
		if (Yii::app()->user->id) {

			// ajax validator
			if (isset($_POST['ajax']) && $_POST['ajax'] === 'changepassword-form') {
				echo UActiveForm::validate($model);
				Yii::app()->end();
			}

			if (isset($_POST['UserChangePassword'])) {
				demoCheck();
				$model->attributes = $_POST['UserChangePassword'];
				if ($model->validate()) {
					$new_password = User::model()->notsafe()->findbyPk(Yii::app()->user->id);
					$new_password->password = UserModule::encrypting($model->password);
					$new_password->activkey = UserModule::encrypting(microtime() . $model->password);
					$new_password->save();
					Yii::app()->user->setFlash('profileMessage', UserModule::t("New password is saved."));
					$this->redirect(array("profile"));
				}
			}
			$this->render('changepassword', array('model' => $model));
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadUser() {
		if ($this->_model === null) {
			if (Yii::app()->user->id) {
				$this->_model = Yii::app()->controller->module->user();
			}
			if ($this->_model === null) {
				$this->redirect(Yii::app()->controller->module->loginUrl);
			}

            if (!$this->_model->country_id){
                $this->_model->country_id = Countries::DEFAULT_ID;
            }
		}
		return $this->_model;
	}

    public function actionSkillList(){
         $this->renderPartial('_profile_skill_list');
    }

    public function actionAddSkill() {
        $data = array(
            'user_id' => Yii::app()->user->id,
            'skill_id' => Yii::app()->request->getPost('skill_id'),
        );

        $userSkill = UserSkills::model()->findByAttributes($data);

        $data = CMap::mergeArray($data, array(
            'level' => Yii::app()->request->getPost('level'),
            'experience' => Yii::app()->request->getPost('experience'),
        ));

        if(!$userSkill){
            $userSkill = new UserSkills();
        }

        $userSkill->attributes = $data;

        if($userSkill->save()){
            HAjax::jsonOk('Навык сохранен', array(
                'html' => $this->renderPartial('_profile_skill_list', array(), true)
            ));
        }
        HAjax::jsonError(HAjax::implodeModelErrors($userSkill));
    }

    public function actionUpload()
    {
        Yii::import("ext.EAjaxUpload.qqFileUploader");

        $user = UserModule::user();

        $oldAva = $user->ava;

        $folder = HUser::getUploadDirectory($user, HUser::UPLOAD_AVA) . DIRECTORY_SEPARATOR;// folder for uploaded files
        $allowedExtensions = array("jpg","jpeg","gif", "png");//array("jpg","jpeg","gif","exe","mov" and etc...
        $sizeLimit = 10 * 1024 * 1024;// maximum file size in bytes
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($folder);

        if($result['success'] == true){
            $fileSize = filesize($folder.$result['filename']);//GETTING FILE SIZE
            $fileName = $result['filename'];//GETTING FILE NAME

            Yii::import('ext.image.Image');
            // генерим тумбу
            $thumbName = User::AVA_PREFIX . $fileName;

            $image = new Image($folder . $fileName);
            $image->resize(96, 96);
            $image->save($folder . $thumbName);

            $user->ava = $fileName;
            $user->update('ava');

            $result['avaHtml'] = '<div class="user-ava-crop">'.CHtml::image($user->getAvaSrcThumb(), $user->username, array('class' => 'message_ava')).'</div>';

            if($oldAva){
                @unlink(HUser::getUploadDirectory($user, HUser::UPLOAD_AVA) . DIRECTORY_SEPARATOR . $oldAva);
                @unlink(HUser::getUploadDirectory($user, HUser::UPLOAD_AVA) . DIRECTORY_SEPARATOR . User::AVA_PREFIX . $oldAva);
            }
        }

        //$return = htmlspecialchars(json_encode($result), ENT_NOQUOTES);

        echo CJSON::encode($result);// it's array
    }
}