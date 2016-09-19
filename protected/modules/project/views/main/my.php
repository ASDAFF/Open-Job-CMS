<?php

HView::echoAndSetTitle('Мои заказы');

$this->widget('BGridView', array(
	'type'=>'striped bordered condensed',
	'dataProvider'=>$model->search(),
    'filter'=>$model,
	'template'=>"{items}",
	'columns'=>array(
		array(
            'name'=>'id',
            'header'=>'#',
            'htmlOptions'=>array('style'=>'width: 50px'),
        ),
		array(
			'name'=>'status',
			'value'=>'$data->getStatusName()',
            'filter' => Project::getStatusList()
		),
		array(
            'name'=>'title',
            'value'=>'CHtml::link($data->title, $data->getUrl())',
            'type'=>'raw',
        ),

		//array('name'=>'description'),

		array(
            'name'=>'budget',
            'value'=>'$data->getBudgetString()'
        ),
		array('name'=>'date_open_until'),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'htmlOptions'=>array('style'=>'width: 50px'),
		),
	),
));
?>