<?php
$this->breadcrumbs=array(
    'Мои навыки',
);

HView::echoAndSetTitle('Мои навыки');

$this->widget('BGridView', array(
    'id' => 'itemGrid',
    'dataProvider' => $dataProvider,
    'enablePagination' => true,
    'columns' => array(
        array(
            'name' => 'Навык',
            'value' => '$data->getSkillName()'
        ),
        array(
            'name' => 'Уровень',
            'value' => '$data->getLevelName()'
        ),
        array(
            'name' => 'Стаж',
            'value' => '$data->getExperienceName()'
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}',
        ),
    )
));
?>
