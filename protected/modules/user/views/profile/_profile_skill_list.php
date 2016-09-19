<?php

$user = UserModule::user();

/** @var UserSkills $userSkill */
foreach($user->userSkills as $userSkill){
    echo '<div class="user-skill">';
    echo $userSkill->skill->name;
    echo ' - ' . $userSkill->getLevelName();
    echo ' - ' . $userSkill->getExperienceName();
    echo '</div>';
}