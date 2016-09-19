<?php
/**
 * @var $data Project
 */
?>

<div class="well">
	<h3><?php echo CHtml::link(CHtml::encode($data->title), $data->url); ?></h3>

    <div class="price pull-right"><span class="label label-success"><?php echo $data->getBudgetString() ?></span></div>

    <?php
    $skills = $data->getSkillsString();
    if($skills){
        echo '<div class="project-skills">'.$skills.'</div>';
    }
    ?>

    <?php $items = array(); ?>
	<div class="muted small">
        <?php
        $items[] = HDate::formatDate($data->date_created);
        $items[] = $data->getStatusName();
        $items[] = $data->count_view . ' ' . Yii::t('main', 'просмотр|просмотра|просмотров', $data->count_view);
        $items[] = $data->countRequest . ' ' . Yii::t('main', 'заявка|заявки|заявок', $data->countRequest);

        echo implode(' | ', $items);
        ?>
    </div>
</div>