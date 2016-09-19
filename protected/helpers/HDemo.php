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

class HDemo
{
    public static function genProjects($num = 100)
    {
        // генерим задачи
        $subjectArray = array(
            1 => '{Сделать|Создать|Сверстать} сайт{ за| бюджет} {10 тр|1000$|{1000|2000|3000|5000|20000} рублей}',
            2 => '{Исправление|Тестирование и исправление} {ошибок|багов}, {нужен|требуется} специалист по {Yii|Yii2|Zend|Drupal|Laravel|Simfony|CodeIgniter|Kohana|WordPress|Joomla|MODx|1С-Битрикс}',
            3 => '{Разработка|Созданеие|Внедрение|Доработка} {saas сервиса|CMS|CMF|Движка|сервиса} ',
            4 => '{Разработка|Созданеие|Внедрение|Доработка} {доп.функционала|функционала} в {saas сервис|CMS|CMF|движок|самописный сайт}',
            5 => '{Требуется|Нужен} разработчик с {хорошим | }знанием {Yii|Yii2|Zend|Drupal|Laravel|Simfony|CodeIgniter|Kohana|WordPress|Joomla|MODx|1С-Битрикс}{ и {AngularJS|node.js|javascript|HTML5}| }',
            6 => '{Требуется|Нужен} {специалист по работе с клиентами|разработчик|менеджер|программист|тестировщик|верстальщик|дизайнер}',
        );

        for($i = 0; $i < $num; $i++){
            $_POST['Project']['skillsSave'] = array();

            $title = HString::genTheText($subjectArray[rand(1,6)]);
            $desc = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris varius risus at euismod viverra. Sed consequat erat et auctor fermentum. Nulla facilisis volutpat massa, quis malesuada leo imperdiet non.';
            echo '<pre>';
            print_r($title);
            echo '</pre>';

            $project = new Project();
            $project->title = $title;
            $project->description = $desc;
            $project->status = Project::STATUS_OPEN;
            $project->budget = rand(100, 3000);
            $project->payment_type = rand(1, 4);
            $project->date_open_until = date(HDate::MYSQL_DATE_FORMAT, strtotime('+15 year'));

            if(strpos($title, 'Yii') && strpos($title, 'Yii') !== 0){
                $_POST['Project']['skillsSave'][] = 1;
            }
            if(strpos($title, 'CodeIgniter') && strpos($title, 'CodeIgniter') !== 0){
                $_POST['Project']['skillsSave'][] = 2;
            }
            if(strpos($title, 'Zend') && strpos($title, 'Zend') !== 0){
                $_POST['Project']['skillsSave'][] = 3;
            }
            if(strpos($title, 'Symfony') && strpos($title, 'Symfony') !== 0){
                $_POST['Project']['skillsSave'][] = 4;
            }
            if(strpos($title, 'Kohana') && strpos($title, 'Kohana') !== 0){
                $_POST['Project']['skillsSave'][] = 5;
            }
            if(strpos($title, 'CakePHP') && strpos($title, 'CakePHP') !== 0){
                $_POST['Project']['skillsSave'][] = 6;
            }
            if(strpos($title, 'Drupal') && strpos($title, 'Drupal') !== 0){
                $_POST['Project']['skillsSave'][] = 9;
            }
            if(strpos($title, 'WordPress') && strpos($title, 'WordPress') !== 0){
                $_POST['Project']['skillsSave'][] = 10;
            }
            if(strpos($title, 'Joomla') && strpos($title, 'Joomla') !== 0){
                $_POST['Project']['skillsSave'][] = 11;
            }
            if(strpos($title, 'MODx') && strpos($title, 'MODx') !== 0){
                $_POST['Project']['skillsSave'][] = 12;
            }
            if(strpos($title, '1С-Битрикс') && strpos($title, '1С-Битрикс') !== 0){
                $_POST['Project']['skillsSave'][] = 13;
            }
            if(strpos($title, 'node.js') && strpos($title, 'node.js') !== 0){
                $_POST['Project']['skillsSave'][] = 26;
            }
            if(strpos($title, 'HTML') && strpos($title, 'HTML') !== 0){
                $_POST['Project']['skillsSave'][] = 27;
            }
            if(!$_POST['Project']['skillsSave']){
                $skillRand = rand(1, 5);
                for($s = 1; $s <= $skillRand; $s++){
                    if(1 + rand(1, 5) > 3){
                        $_POST['Project']['skillsSave'][] = rand(1,15);
                    }else{
                        $_POST['Project']['skillsSave'][] = rand(20,36);
                    }
                }
            }

            $project->user_id = 1;
            $project->save();
        }
    }
}