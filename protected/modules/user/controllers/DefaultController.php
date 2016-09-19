<?php

class DefaultController extends Controller
{
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        if(!Yii::app()->user->isGuest){
            $this->layout = '//layouts/column2';
        }
		HMenu::setState('user.list');

		$dataProvider=new CActiveDataProvider('User', array(
			'criteria'=>array(
		        'condition'=>'status>'.User::STATUS_BANNED,
		    ),
            'sort'=>array(
                'defaultOrder'=>'create_at DESC',
            ),
			'pagination'=>array(
				'pageSize'=>Yii::app()->controller->module->user_page_size,
			),
		));

//		if(Yii::app()->request->isAjaxRequest){
//			$this->renderPartial('/user/index',array(
//				'dataProvider'=>$dataProvider,
//			));
//		}else{
			$this->render('/user/index',array(
				'dataProvider'=>$dataProvider,
			));
//		}
	}

}