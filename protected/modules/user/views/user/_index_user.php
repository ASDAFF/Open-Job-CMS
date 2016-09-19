<?php
/**
 * @var User $data
 */
?>

<div class="well">
    <div class="user-left-block">
        <div class="user-ava">
            <?php echo $data->renderAva(); ?>
        </div>
    </div>

    <div class="user-right-block">
        <div class="user-name">
            <?php
            echo CHtml::link($data->username, $data->getUrl());
            if ($data->profile->firstname || $data->profile->lastname) {
                echo '<span class="user-full-name">' . $data->fullName . '</span>';
            }

            $cityName = $data->getCityName();
            if($cityName) {
                echo '&nbsp;&nbsp;<span class="user-city-name"><i class="icon-map-marker"></i>&nbsp;' . $cityName . '</span>';
            }
            ?>
        </div>

        <?php
        if ($data->isProgrammer()) {
            ?>
            <div class="user-activity"><?php echo $data->getActivityName(); ?></div>

            <?php if ($data->salary_per_hour > 0) { ?>
                <div class="user-salary">от <span><?php echo $data->salary_per_hour . ' ' . Project::getCurrencyName(); ?></span> в час</div>
            <?php }
        }

        if($data->profile->about_us){
            echo '<div class="clear"></div>';
            echo '<div class="user-about-us">';
            echo HString::truncate($data->profile->about_us);
            echo '</div>';
        }

        if ($data->userSkills) {
            echo '<div class="user-skills">';
                $skills = array();
                /** @var UserSkills $userSkill */
                foreach ($data->userSkills as $userSkill) {
                $skills[] = $userSkill->renderForList();
                }
                echo implode('&nbsp;', $skills);
                echo '</div>';
            }
        ?>
    </div>

<?php
//if ($data->portfolios) {
//    echo '<div class="user-portfolio">';
//    /** @var UserPortfolio $portfolio */
//    foreach ($data->portfolios as $portfolio) {
//        $img = CHtml::image($portfolio->getImgSrcThumb());
//        echo CHtml::link($img, $portfolio->getImgSrc(), array(
//            'title' => $portfolio->description,
//            'rel' => 'prettyPhoto[' . $data->id . ']',
//        ));
//    }
//    echo '</div>';
//}
?>
    <div class="clear"></div>

    <?php

    if(Yii::app()->user->id && Yii::app()->user->id != $data->id){
        echo '<div class="user-bottom-line">';
        echo CHtml::link('Личное сообщение', Dialog::getUrlWithUser($data->id), array('class' => 'btn btn-info btn-mini'));
        echo '</div>';
    }
    ?>
</div>