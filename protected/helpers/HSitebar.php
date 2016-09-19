<?php
/* * ********************************************************************************************
 *								Open Job CMS
 *								------------
 * 	version				:	V1.0.0
 * 	copyright			:	(c) 2016 Monoray
 * 							http://monoray.net
 *							http://monoray.ru
 *
 * 	website				:	https://monoray.ru/products/open-job-cms
 *
 * 	contact us			:	http://open-real-estate.info/en/contact-us
 *
 * 	license:			:	http://open-real-estate.info/en/license
 * 							http://open-real-estate.info/ru/license
 *
 * This file is part of Open Job CMS
 *
 * ********************************************************************************************* */

class HSitebar
{
    const CACHE_PROJECT_ITEMS_KEY = 'project_item';

    public static function getProjectItems()
    {
        $cache = Yii::app()->cache->get(self::CACHE_PROJECT_ITEMS_KEY);
        if($cache === false) {
            $skills = Skill::getModelList();

            $countSkills = Yii::app()->db->createCommand('SELECT skill_id AS id, COUNT(skill_id) AS count FROM {{skill_to_project}} GROUP BY skill_id')->queryAll();
            $countSkillsList = CHtml::listData($countSkills, 'id', 'count');

            $items = array();
            foreach ($countSkillsList as $id => $count) {
                $items[] = array('label' => $skills[$id]->name . ' (' . $count . ')', 'url' => $skills[$id]->getUrlForProject());
            }
            Yii::app()->cache->set(self::CACHE_PROJECT_ITEMS_KEY, serialize($items), param('cache_category_items', 30));
        } else {
            $items = unserialize($cache);
        }

        return $items;
    }
}