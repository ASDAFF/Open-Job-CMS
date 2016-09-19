<?php
/**
 * @var $model Project
 */

HView::echoAndSetTitle('Управление заказами');
?>

<?php
$this->widget('BGridView', array(
	'id'=>'profgroup-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	//'afterAjaxUpdate' => 'function(){$("a[rel=\'tooltip\']").tooltip(); $("div.tooltip-arrow").remove(); $("div.tooltip-inner").remove();}',
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
            'value'=>'CHtml::link($data->title, $data->getUrl(), array("target" => "_blank"))',
            'type'=>'raw',
        ),

        //array('name'=>'description'),

        array(
            'name'=>'budget',
            'value'=>'$data->getBudgetString()'
        ),
        array('name'=>'date_open_until'),
        array(
            'class'=>'BButtonColumn',
            'htmlOptions'=>array('style'=>'width: 50px'),
            'template' => '{update} {delete}',
//            'buttons' => array(
//
//            ),
        ),
	),
));