<?php
/**
 * @var $project Project
 */

HView::echoAndSetTitle($project->title, 'Заказ');

$this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$project,
	'attributes'=>array(
        array(
            'label' => 'Заказчик',
            'value' => $project->owner->getLink2Profile(),
            'type' => 'raw'
        ),
		array(
			'name' => 'budget',
			'value' => $project->getBudgetString(),
		),
        array(
            'name' => 'skillsSave',
            'value' => $project->getSkillsString(),
            'type' => 'raw',
        ),
		array(
			'name' => 'date_open_until',
			'value' => HDate::rdate('d M Y', strtotime($project->date_open_until)),
		),
		'count_view',
        array(
            'label' => 'Кол-во заявок',
            'value' => $project->countRequest,
        ),
		array(
			'name' => 'status',
			'value' => $project->getStatusName()
		),
	),
));
?>

<?php //$this->beginWidget('CHtmlPurifier'); ?>

<p><?php echo $project->description; ?></p>

<?php //$this->endWidget(); ?>

<?php
if(Yii::app()->user->isGuest){
    echo CHtml::link('Зарегистрируйтесь', Yii::app()->createUrl('/user/registration')) . ' чтобы оставить заявку';
}else{
    $this->widget('application.modules.message.components.WMessage', array(
        'recipient' => $project->owner,
        'object' => $project,
        'showStatuses' => true,
    ));
}

?>