<?php
/**
 * @var User $model
 */

$cs = Yii::app()->clientScript;
$cs->registerCssFile(Yii::app()->baseUrl . '/js/prettyphoto/css/prettyPhoto.css');
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/prettyphoto/js/jquery.prettyPhoto.js');

$this->breadcrumbs = array(
    UserModule::t('Users') => array('index'),
    $model->username,
);

$fullName = '';

if ($model->profile->firstname || $model->profile->lastname) {
    $fullName = $model->fullName;
}

HView::echoAndSetTitle($model->getFullName(), 'Фрилансеры');

?>

<div class="well">

    <?php
    if(Yii::app()->user->id == $model->id){
        echo CHtml::link('Рекдактировать профиль', Yii::app()->createUrl('/user/profile/edit'), array('class' => 'btn pull-right'));
    } elseif (UserModule::isAdmin()) {
        echo CHtml::link('Рекдактировать', Yii::app()->createUrl('/user/admin/update', array('id' => $model->id)), array('class' => 'btn pull-right'));
    }
    ?>

    <div class="user-left-block">
        <div class="user-ava">
            <?php echo $model->renderAva(false); ?>
        </div>
    </div>

    <div class="user-right-block">
        <div class="user-name">
            <?php
            echo CHtml::link($model->username, $model->getUrl());
            if ($fullName) {
                echo '<span class="user-full-name">' . $fullName . '</span>';
            }
            $cityName = $model->getCityName();
            if($cityName) {
                echo '&nbsp;&nbsp;<span class="user-city-name"><i class="icon-map-marker"></i>&nbsp;' . $cityName . '</span>';
            }
            ?>
        </div>

        <?php
        if ($model->isProgrammer()) {
            ?>
            <div class="user-activity"><?php echo $model->getActivityName(); ?></div>

            <?php if ($model->salary_per_hour > 0) { ?>
                <div class="user-salary">от <span><?php echo $model->salary_per_hour . ' ' . Project::getCurrencyName(); ?></span> в час</div>
            <?php }
        }

        if($model->profile->about_us){
            echo '<div class="clear"></div>';
            echo '<div class="user-about-us">';
            echo $model->profile->about_us;
            echo '</div>';
        }
        ?>
    </div>

    <div class="user-view-skills">
        <b>Навыки:</b>
        <?php
        if ($model->userSkills) {
            echo '<div class="user-skills">';
            $skills = array();
            /** @var UserSkills $userSkill */
            foreach ($model->userSkills as $userSkill) {
                $skills[] = $userSkill->renderForList();
            }
            echo implode('&nbsp;', $skills);
            echo '</div>';
        }
        ?>
    </div>

    <div class="user-view-portfolio">
        <b>Портфолио:</b>
        <?php
        if ($model->portfolios) {
            echo '<div class="user-portfolio">';
            /** @var UserPortfolio $portfolio */
            foreach ($model->portfolios as $portfolio) {
                $img = CHtml::image($portfolio->getImgSrcThumb());
                echo CHtml::link($img, $portfolio->getImgSrc(), array(
                    'title' => $portfolio->description,
                    'rel' => 'prettyPhoto[' . $model->id . ']',
                ));
            }
            echo '</div>';
        }
        ?>
    </div>

    <?php
    if(Yii::app()->user->id && Yii::app()->user->id != $model->id){
        echo '<div class="user-bottom-line">';
        echo CHtml::link('Личное сообщение', Dialog::getUrlWithUser($model->id), array('class' => 'btn btn-info btn-mini'));
        echo '</div>';
    }
    ?>
</div>

<?php $this->widget('application.widgets.WReview', array(
    'recipient' => $model,
    'object' => $model,
));?>

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