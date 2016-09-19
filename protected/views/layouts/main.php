<?php
/**
 * @var $this Controller
 */

$cs = Yii::app()->clientScript;

$cs->registerCoreScript('jquery');
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/common.js', CClientScript::POS_HEAD);

$cs->registerScriptFile(Yii::app()->baseUrl . '/js/pnotify/jquery.pnotify.min.js', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/poshytip/jquery.poshytip.min.js', CClientScript::POS_END);

Yii::app()->bootstrap->register();
?>

<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="ru"/>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/form.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/common.css"/>

    <link rel="stylesheet" type="text/css"
          href="<?php echo Yii::app()->baseUrl; ?>/js/pnotify/jquery.pnotify.default.css"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo Yii::app()->baseUrl; ?>/js/pnotify/jquery.pnotify.default.icons.css"/>

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<?php
$this->widget('bootstrap.widgets.TbNavbar', array(
    'type' => 'inverse', // null or 'inverse'
    'brand' => Yii::app()->name,
    'brandUrl' => Yii::app()->getBaseUrl(true),
    'fixed' => 'top',
    //'fluid' => true,
    'collapse' => true, // requires bootstrap-responsive.css
    'items' => array(
        array(
            'class' => 'bootstrap.widgets.TbMenu',
            'encodeLabel' => false,
            'items' => HMenu::getTopMenuLeft(),
        ),
//		'<form class="navbar-search pull-left" action=""><input type="text" class="search-query span2" placeholder="Search"></form>',
        array(
            'class' => 'bootstrap.widgets.TbMenu',
            'encodeLabel' => false,
            'items' => HMenu::getTopMenuRight(),
            'htmlOptions' => array('class' => 'pull-right'),
        ),
    ),
)); ?>

<div class="container">

    <?php
    echo $content;
    ?>

    <hr>

    <footer>
        <p class="slogan">&copy; <?php echo Yii::app()->name . ' ' . OJC_VERSION . ' ' . date('Y'); ?></p>
    </footer>

</div>
<!-- /container -->

</body>

</html>