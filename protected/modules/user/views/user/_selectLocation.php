<?php
echo CHtml::label('Город', 'city_name');

$this->widget('CAutoComplete', array(
    'model'=>$model,
    'attribute'=>'city_name',
    'url'=>array('/ajax/autoCompleteCity'),
    'minChars'=>2,
    'autoFill'=>true,
    'selectFirst'=>true,
    'value' => $model->city_name ? $model->city_name : (isset($location) && isset($location->city) ? $location->city->name : ''),
));
