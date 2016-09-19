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

class BaseAdminController extends Controller {
    public $params = array();

    public $scenario = null;
    public $with = array();

    public $modelName;
    protected $_model = null;

    public $title;
    public $actionButtons;

    public $actionId;

    public $ignoreButton = NULL;

    // Кол-во заказов требующих реакции
    public $countOrdersReqResponce = 0;

    public $layout = '//layouts/column2';

    public $showButtons = true;

    public function filters() {
        return array(
            //'ajaxOnly + requests, LoadCrop, json', // только ajax запросы
            array('AdminFilter') // доступ к данному разделу только авторизированным
        );
    }

    public function beforeAction($action){
        $this->createActionButtons();

        return parent::beforeAction($action);
    }

    public function actionView($id){
        $this->createActionButtons(array('admin', 'create', 'update'), array('id' => $id));

        $this->render('view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    public function actionCreate(){
        $this->createActionButtons(array('admin', 'create'));
        $model=new $this->modelName;
        if($this->scenario){
            $model->scenario = $this->scenario;
        }
        $this->performAjaxValidation($model);

        if(isset($_POST[$this->modelName])){
            $model->attributes=$_POST[$this->modelName];
            if($model->save()){
                Yii::app()->user->setFlash('success', 'Данные успешно сохранены');

                if (!empty($this->redirectTo))
                    $this->redirect($this->redirectTo);
                else
                    $this->redirect(array('view','id'=>$model->id));
            }
        }

        $this->render('create',array_merge(
            array('model'=>$model),
            $this->params
        ));
    }

    public function actionUpdate($id){

        $this->createActionButtons(array('admin', 'create', 'update'));

        if($this->_model === null){
            $model = $this->loadModel($id);
        }
        else{
            $model = $this->_model;
        }

        $this->performAjaxValidation($model);

        if(isset($_POST[$this->modelName])){
            $model->attributes=$_POST[$this->modelName];

            if($model->save()){
                Yii::app()->user->setFlash('success', 'Данные успешно сохранены');

                if(!(isset($_FILES['uploader']['name'][0]) && $_FILES['uploader']['name'][0])){
                    if (!empty($this->redirectTo))
                        $this->redirect($this->redirectTo);
                    else
                        $this->redirect(array('view','id'=>$model->id));
                }
                else{
                    $this->photoUpload = true;
                }
            }
        }

        $this->render('update',
            array_merge(
                array('model'=>$model),
                $this->params
            )
        );
    }

    public function actionDelete($id){
        if(Yii::app()->request->isPostRequest){
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            Yii::app()->user->setFlash('success', 'Данные успешно удалены');
            if(!isset($_GET['ajax'])){
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    public function actionIndex(){
        $dataProvider=new CActiveDataProvider($this->modelName);
        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));
    }

    public function actionAdmin(){
        $model = new $this->modelName('search');
        $model->resetScope();

        if($this->scenario){
            $model->scenario = $this->scenario;
        }

        if($this->with){
            $model = $model->with($this->with);
        }

        $model->unsetAttributes();  // clear any default values
        if(isset($_GET[$this->modelName])){
            $model->attributes=$_GET[$this->modelName];
        }
        $this->render('admin',
            array_merge(array('model'=>$model), $this->params)
        );
    }

    /**
     * @param null $id
     * @return Orders || null
     * @throws CHttpException
     */
    public function loadModel($id = null){
        if(!$this->_model){
            $model = new $this->modelName;
        } else {
            $model = $this->_model;
        }
        if($id !== null){
            if($this->with){
                $model = $model->resetScope()->with($this->with)->findByPk($id);
            }
            else{
                $model = $model->resetScope()->findByPk($id);
            }
        }
        if($this->scenario){
            $model->scenario = $this->scenario;
        }

        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');

        $this->_model = $model;
        return $this->_model;
    }

    public function loadModelWith($with) {
        if(isset($_GET['id'])) {
            $model = new $this->modelName;
            if($this->scenario){
                $model->scenario = $this->scenario;
            }
            if($model===null){
                throw new CHttpException(404,'The requested page does not exist.');
            }
            return $model/*->resetScope()*/->with($with)->findByPk($_GET['id']);
        }
    }

    protected function performAjaxValidation($model){
        if(isset($_POST['ajax']) && $_POST['ajax']===$this->modelName.'-form'){
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    protected $buttonsName = array(
        'admin' => 'Управление',
        'create' => 'Добавить',
        'update' => 'Редактировать',
        'delete' => 'Удалить',
    );

    protected function createActionButtons($arr = array('admin', 'create'), $params = array()){
        $this->actionButtons = array();
        $actionId = isset($this->action->id) ? $this->action->id : '';
        foreach($arr as $action){
            if($action == $this->ignoreButton){
                continue;
            }

            $url = Yii::app()->createUrl(Yii::app()->controller->module->id . '/'.Yii::app()->controller->id.'/'.$action, $params);

            $this->actionButtons[] = array(
                'label' => $this->buttonsName[$action],
                'url' => $url,
                'active' => $actionId == $action
            );
        }
    }
}