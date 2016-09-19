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

class BaseUserController extends Controller{
    protected $modelName;
    protected $_model;
    protected $with;
    protected $scenario;

    public $actionButtons;
    public $ignoreButton;

    public function init() {
        if(!Yii::app()->user->isGuest){
            $this->layout = '//layouts/column2';
        }
        parent::init();
    }

    public function beforeAction($action){
        $this->createActionButtons();
        return parent::beforeAction($action);
    }

    public function filters() {
        return array(
            //'ajaxOnly + requests, LoadCrop, json', // только ajax запросы
            array('AuthFilter') // доступ к данному разделу только авторизированным
        );
    }

    public function actionDelete($id){
        if(Yii::app()->request->isPostRequest){
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    public function loadModel($id = null){
        if(!$this->_model){
            $model = new $this->modelName;
        } else {
            $model = $this->_model;
        }
        if($id !== null){
            if($this->with){
                $model = $model->with($this->with)->findByPk($id);
            }
            else{
                $model = $model->findByPk($id);
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

    protected $buttonsName = array(
        'list' => 'Управление',
        'create' => 'Добавить',
        'update' => 'Редактировать',
        'delete' => 'Удалить',
    );

    protected function createActionButtons($arr = array('list', 'create'), $params = array()){
        $this->actionButtons = array();
        $currentActionId = isset(Yii::app()->controller->action->id) ? Yii::app()->controller->action->id : '';

        foreach($arr as $action){
            if($action == $this->ignoreButton){
                continue;
            }

            $url = Yii::app()->createUrl('/user/'.Yii::app()->controller->id.'/'.$action, $params);

            $this->actionButtons[] = array(
                'label' => $this->buttonsName[$action],
                'url' => $url,
                'active' => $currentActionId == $action
            );
        }
    }
}
