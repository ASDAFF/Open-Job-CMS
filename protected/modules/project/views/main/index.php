<div class="hero-unit" style="background: #0E4369; background: linear-gradient(to top right, #0E4369, #56BCE4); color: #fafafa;">
    <h2><?php echo Yii::app()->name ?></h2>
    <p>Бесплатный скрипт фриланс биржи с открытым кодом, каталог заказов и исполнителей.</p>
</div>

<?php
if($skill){
    $title = 'Заказы по '.$skill->name;
} else {
    $title = 'Заказы';
}

HView::echoAndSetTitle($title);

$param = $skill ? array('skill' => $skill->alias) : array();
$feedUrl = Yii::app()->createUrl('/project/feed', $param);
?>

<a class="btn btn-info" href="<?php echo $feedUrl;?>">
    <i class="icon-signal icon-white"></i>
    RSS
</a>

<a class="btn btn-success" href="<?php echo Yii::app()->createUrl('/project/create');?>">
    <i class="icon-plus-sign icon-white"></i>
    Добавить заказ
</a>

<?php
$this->widget('ext.bootstrap.widgets.TbListView', array(
	'dataProvider' => $dataProvider,
	'itemView'=>'_list_item',
	'template'=>"{summary}\n{pager}\n{items}\n{pager}",
	'summaryText'=>'{start}-{end} из {count} заказов',
    'ajaxUpdate'=>false,
));

if($skill && $skill->description){
    echo $skill->description;
}
?>


