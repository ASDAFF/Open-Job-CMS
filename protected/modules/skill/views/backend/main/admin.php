<?php
/**
 * @var $model Project
 */

HView::echoAndSetTitle('Управление навыками');
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
            'filter' => Skill::getStatusList()
        ),
        array(
            'name'=>'parent_id',
            'value'=>'$data->parent ? $data->parent->name : ""',
            'filter'=>Skill::getParentList(true),
        ),
        array(
            'name'=>'name',
        ),

        array(
            'class'=>'BButtonColumn',
            'htmlOptions'=>array('style'=>'width: 50px'),
        ),
	),
));