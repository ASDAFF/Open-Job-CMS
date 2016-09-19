<?php
$this->pageTitle = 'Диалоги';

HView::echoAndSetTitle('Диалоги');

$this->widget('ext.bootstrap.widgets.TbListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_item', // представление для одной записи
    'ajaxUpdate'=>false, // отключаем ajax поведение
    'emptyText'=>'Диалогов нет.',
    'summaryText'=>"{start}&mdash;{end} из {count}",
    'template'=>'{summary} {sorter} {items} <hr> {pager}',
    'sorterHeader'=>'Сортировать по:',
    // ключи, которые были описаны $sort->attributes
    // если не описывать $sort->attributes, можно использовать атрибуты модели
    // настройки CSort перекрывают настройки sortableAttributes
    'sortableAttributes'=>array('date', 'name'),
)); ?>