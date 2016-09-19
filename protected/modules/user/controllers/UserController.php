<?php

class UserController extends Controller
{
    public function actions()
    {
        return array(
            'feed' => array(
                'class'        => 'application.components.actions.YFeedAction',
                'data'         => User::model()->active()->programmers()->sort()->findAll(),

                'itemFields'   => array(
                    'title'           => 'fullName',
                    'content'         => 'preview',
                    'datetime'        => 'create_at',
                    'updated'         => 'lastvisit_at',
                ),
            ),
        );
    }

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	/**
	 * Displays a particular model.
	 */
	public function actionView($id = 0, $un = '')
	{
        if(!Yii::app()->user->isGuest){
            HMenu::setState('user.view');
            $this->layout = '//layouts/column2';
        }
        if(!$id && $un){
            $model = User::model()->findByAttributes(array(
                'username' => $un
            ));
        }else{
            $model = $this->loadModel();
        }

		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('User', array(
			'criteria'=>array(
		        'condition'=>'status>'.User::STATUS_BANNED,
		    ),

			'pagination'=>array(
				'pageSize'=>Yii::app()->controller->module->user_page_size,
			),
		));

		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=User::model()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadUser($id=null)
	{
		if($this->_model===null)
		{
			if($id!==null || isset($_GET['id']))
				$this->_model=User::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
}
