<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrey Pasynkov
 * Date: 07.10.13
 * Time: 14:38
 */

class SkillController extends BaseUserController {

    public $modelName = 'UserSkills';

    public $showButtons = true;

    public function actionList(){
        $dataProvider = new CActiveDataProvider(UserSkills::model()->my());

        $this->render('list', array('dataProvider' => $dataProvider));
    }

    public function actionCreate(){
        $model = new UserSkills();

        if(isset($_POST['UserSkills'])){
            $userSkill = UserSkills::model()->findByAttributes(array(
                'user_id' => Yii::app()->user->id,
                'skill_id' => $_POST['UserSkills']['skill_id'],
            ));

            if($userSkill){
                $model->addError('', 'Такой навык Вы уже добавляли');
            } else {
                $model->attributes = $_POST['UserSkills'];
                $model->user_id = Yii::app()->user->id;

                if($model->save()){
                    Yii::app()->user->setFlash('success', 'Навык успешно сохранен');

                    if(isset($_POST['add_more']) && $_POST['add_more']){
                        $_POST = array();
                        $this->redirect(array('create'));
                    } else {
                        $this->redirect(array('list'));
                    }
                }
            }
        }

        $this->render('create', array('model' => $model));
    }


    public function actionUpdate($id){
        $model = UserSkills::model()->my()->findByPk($id);

        if(!$model){
            throw new CHttpException(404);
        }

        if(isset($_POST['UserSkills'])){

            $criteria = new CDbCriteria();
            $criteria->condition = 'id != :id';
            $criteria->compare('user_id', Yii::app()->user->id);
            $criteria->compare('skill_id', $_POST['UserSkills']['skill_id']);
            $criteria->params[':id'] = $model->id;

            $userSkill = UserSkills::model()->find($criteria);
            if($userSkill){
                $model->addError('', 'Такой навык Вы уже добавляли');
            } else {
                $model->attributes = $_POST['UserSkills'];
                if($model->save()){
                    Yii::app()->user->setFlash('success', 'Навык успешно сохранен');

                    if(isset($_POST['add_more']) && $_POST['add_more']){
                        $_POST = array();
                        $this->redirect(array('create'));
                    }

                    $this->redirect(array('list'));
                }
            }
        }

        $this->render('create', array('model' => $model));
    }

    public function actionDelete($id){
        parent::actionDelete($id);
    }

}