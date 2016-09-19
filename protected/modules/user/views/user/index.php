<?php

$cs = Yii::app()->clientScript;
$cs->registerCssFile(Yii::app()->baseUrl . '/js/prettyphoto/css/prettyPhoto.css');
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/prettyphoto/js/jquery.prettyPhoto.js');

HView::echoAndSetTitle('Исполнители');

?>

<a class="btn btn-info" href="<?php echo Yii::app()->createUrl('/user/user/feed');?>">
    <i class="icon-signal icon-white"></i>
    RSS
</a>

<?php

$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'/user/_index_user', // представление для одной записи
    'ajaxUpdate'=>false, // отключаем ajax поведение
    'emptyText'=>'Никого не найдено',
    'summaryText'=>"{start}&mdash;{end} из {count}",
    'template'=>'{summary} {items} <hr> {pager}',
));
?>

<script type="text/javascript">
    $(function(){
        $("a[rel^=\'prettyPhoto\']").prettyPhoto(
            {
                animation_speed: "fast",
                slideshow: 10000,
                hideflash: true,
                social_tools: "",
                gallery_markup: "",
                slideshow: 3000,
                autoplay_slideshow: false
                /*slideshow: false*/
            }
        );
    });
</script>