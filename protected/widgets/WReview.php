<?php
/**
 * Created by PhpStorm.
 * User: Andrey Pasynkov
 * Date: 01.11.13
 * Time: 12:49
 */

class WReview extends CWidget {

    public $recipient;

    public $object = NULL;
    public $objectName;

    public $buttonText = 'Добавить отзыв';

    public function run(){
        if(!$this->object){
            throw new CException('Не задан объект');
        }

        $this->objectName = get_class($this->object);

        if(!$this->recipient){
            throw new CException('Не задан получатель');
        }

        if($this->recipient->id == Yii::app()->user->id){
            $this->buttonText = 'Добавить комментарий';
        }

        $review = new Review();

        if(isset($_POST['Review']) && Yii::app()->user->id){
            $review->attributes = $_POST['Review'];
            $review->sender_id = Yii::app()->user->id;
            $review->object_name = $this->objectName;
            $review->object_id = $this->object->id;
            $review->status = Review::STATUS_ON_MODERATION;
            $review->recipient_id = $this->recipient->id;
            if($this->recipient->id == Yii::app()->user->id){
                $review->type = Review::TYPE_NONE;
            }

            if($review->save()){
                Yii::app()->user->setFlash('success', 'Спасибо, Ваш отзыв добавлен и будет показан после модерации');
                $review = new Review();
                unset($_POST['Review']);
            }
        }

        $criteria = new CDbCriteria();
        $criteria->compare('object_name', $this->objectName);
        $criteria->compare('object_id', $this->object->id);
        $criteria->order = 't.date_created DESC';

        $pages = new ReverseCPagination(Review::model()->count($criteria));
        $pages->pageSize = param('reviewPageSize', 5);
        $pages->applyLimit($criteria);

        $this->render('review', array(
            'review' => $review,
            'reviews' => Review::model()->with('sender')->findAll($criteria),
            'pages'=>$pages,
        ));
    }
}