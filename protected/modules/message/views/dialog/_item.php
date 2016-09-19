<?php
/**
 * @var Dialog $data
 */
?>
<div class="well">

    <?php
    if($data->sender->id == Yii::app()->user->id){
        $he = $data->recipient;
    } else {
        $he = $data->sender;
    }
    echo CHtml::link('Диалог с ' . $he->username . $he->renderAva(true), $data->getUrl());

    echo '&nbsp;|&nbsp;';

    echo 'Начат ' . HDate::formatDate($data->date_created);
    ?>
    <div class="clear"></div>
</div>