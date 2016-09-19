<div class="row">
    <label>Вы проживаете</label>

	<?php
	echo $form->dropDownList($model, "country_id", Country::getList(), array(
		'ajax' => array(
			'type' => 'POST',
			'url' => Yii::app()->createUrl('/ajax/country'),
			'data' => 'js:"country_id="+this.value',
			//'update'=>'#location_region_id',
			'success' => 'js: function(html){
				$("#location_region_id").html(html);
				$("#location_region_id").change();
			}'),
		//'empty' => Yii::t("main", 'Страна')
	));
	?>
</div>
<div class="row">

	<?php
	echo $form->dropDownList($model, "region_id", $model->country_id ? Region::getList($model->country_id) : array(),
		array(
			//'empty' => Yii::t("main", "Регион"),
			'id' => 'location_region_id',
			'ajax' => array(
				'type' => 'POST',
				'data' => 'js:"region_id="+this.value',
				'url' => Yii::app()->createUrl('/ajax/region'),
				//'update' => "#location_city_id",
				'success' => 'function(html){
					$("#location_city_id").html(html);
					$("#location_city_id").change();
				}'
			),
		));
	?>
</div>
<div class="row">
	<?php
	echo $form->dropDownList($model, "city_id", $model->region_id ? City::getList($model->region_id) : array(),
		array(
			//'empty' => Yii::t("main", "Город"),
			'id' => 'location_city_id'
		));
	?>
</div>
