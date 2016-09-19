<?php

class PortfolioController extends BaseUserController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */

	/**
	 * @return array action filters
	 */
//	public function filters()
//	{
//		return array(
//			'accessControl', // perform access control for CRUD operations
//			'postOnly + delete', // we only allow deletion via POST request
//		);
//	}
//
//	/**
//	 * Specifies the access control rules.
//	 * This method is used by the 'accessControl' filter.
//	 * @return array access control rules
//	 */
//	public function accessRules()
//	{
//		return array(
//			array('allow',  // allow all users to perform 'index' and 'view' actions
//				'actions'=>array('index','view'),
//				'users'=>array('*'),
//			),
//			array('allow', // allow authenticated user to perform 'create' and 'update' actions
//				'actions'=>array('create','update'),
//				'users'=>array('@'),
//			),
//			array('allow', // allow admin user to perform 'admin' and 'delete' actions
//				'actions'=>array('admin','delete'),
//				'users'=>array('admin'),
//			),
//			array('deny',  // deny all users
//				'users'=>array('*'),
//			),
//		);
//	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new UserPortfolio;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UserPortfolio']))
		{
			$model->attributes=$_POST['UserPortfolio'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UserPortfolio']))
		{
			$model->attributes=$_POST['UserPortfolio'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('UserPortfolio');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new UserPortfolio('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UserPortfolio']))
			$model->attributes=$_GET['UserPortfolio'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return UserPortfolio the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id = null)
	{
		$model=UserPortfolio::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param UserPortfolio $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-portfolio-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    public function actionDelDraft(){
        // TODO: Сделать крон
        UserPortfolio::deleteDraft();
        echo 'OK';
    }

	public function actionUpload()
	{
		Yii::import("ext.EAjaxUpload.qqFileUploader");

        $user = UserModule::user();
        if($user->countPortfolio >= param('portfolioMaxPhoto')){
            $result['error'] = 'Максимальное количество фото ' . param('portfolioMaxPhoto');
            echo CJSON::encode($result);
            Yii::app()->end();
        }

		$folder = HUser::getUploadDirectory($user, HUser::UPLOAD_PORTFOLIO) . DIRECTORY_SEPARATOR;// folder for uploaded files
		$allowedExtensions = array("jpg","jpeg","gif", "png");//array("jpg","jpeg","gif","exe","mov" and etc...
		$sizeLimit = 10 * 1024 * 1024;// maximum file size in bytes
		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		$result = $uploader->handleUpload($folder);

		$fileSize = filesize($folder.$result['filename']);//GETTING FILE SIZE
		$fileName = $result['filename'];//GETTING FILE NAME

        Yii::import('ext.image.Image');
        // генерим тумбу
        $thumbName = UserPortfolio::THUMB_PREFIX . $fileName;

        $image = new Image($folder . $fileName);
        $image->resize(144, 89);
        $image->save($folder . $thumbName);

        $portfolio = new UserPortfolio();
        $portfolio->user_id = $user->id;
        $portfolio->img = $fileName;
        $portfolio->status = UserPortfolio::STATUS_OPEN;
        $portfolio->save();

        $data = $portfolio->attributes;
        $data['sImgSrc'] = $portfolio->getImgSrc();
        $data['sImgSrcThumb'] = $portfolio->getImgSrcThumb();

        $result['portfolio'] = $data;

        $return = htmlspecialchars(json_encode($result), ENT_NOQUOTES);

		echo $return;// it's array
	}

    public function actionAjaxDelete($id) {
        $user = UserModule::user();
        $portfolio = UserPortfolio::model()->my()->open()->findByPk($id);

        if(!$portfolio){
            throw new CHttpException(404);
        }

        $portfolio->status = UserPortfolio::STATUS_DRAFT;
        if($portfolio->update('status')){
            HAjax::jsonOk('Успешно', array(
                'countPortfolio' => $user->countPortfolio,
            ));
        }

        HAjax::jsonError();
    }

    public function actionAjaxSave($id){
        $portfolio = UserPortfolio::model()->my()->findByPk($id);

        if(!$portfolio){
            throw new CHttpException(404);
        }

        $portfolio->description = Yii::app()->request->getPost('desc');
        if($portfolio->update('description')){
            HAjax::jsonOk('Успешно сохранили описание');
        }

        HAjax::jsonError(HAjax::implodeModelErrors($portfolio));
    }

    public function actionAjaxRestore($id) {
        $user = UserModule::user();
        if($user->countPortfolio >= param('portfolioMaxPhoto')){
            HAjax::jsonError('Максимальное количество фото ' . param('portfolioMaxPhoto'));
        }

        $portfolio = UserPortfolio::model()->my()->findByPk($id);

        if(!$portfolio){
            HAjax::jsonError('Похоже фото уже удалено :/');
        }

        $portfolio->status = UserPortfolio::STATUS_OPEN;
        if($portfolio->update('status')){
            HAjax::jsonOk('Успешно', array(
                'countPortfolio' => $user->countPortfolio,
            ));
        }

        HAjax::jsonError();
    }

    public function actionAjaxLoadList() {
        $user = UserModule::user();
        $portfolios = UserPortfolio::model()->my()->open()->findAll();

        $dataAll = array();
        foreach($portfolios as $portfolio){
            $data = $portfolio->attributes;
            $data['sImgSrc'] = $portfolio->getImgSrc();
            $data['sImgSrcThumb'] = $portfolio->getImgSrcThumb();
            $dataAll[] = $data;
        }

        echo CJSON::encode(array('portfolios' => $dataAll, 'countPortfolio' => $user->countPortfolio));
    }
}
