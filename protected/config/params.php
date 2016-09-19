<?php
return array(
    'titleTag' => 'h1',

    'siteName'=>'Open Job CMS',
    'siteDescription'=>'Open Job CMS - открытый движок для поиска работы',

    'projectModeration' => 1, // Предварительная модерация заказов
    'portfolioMaxPhoto' => 7,

    'indexPageSize' => 10, // кол-во элементов на главной

    'reviewPageSize' => 5, // кол-во элементов в отзвывах
    'messagePageSize' => 5, // кол-во элементов в сообщениях

    'adminTableSize' => 20, // кол-во элементов в админ. таблицах

    'currency_name' => 'руб.',

    'cache_category_items' => 60, // время кэширования меню категорий из сайтбара в сек.

    // email
    'adminEmail'=>'webmaster@example.com',

    'mailUseSMTP' => 0,
    'mailSMTPHost' => 'localhost',
    'mailSMTPPort' => 25,
    'mailSMTPSecure' => '', //ssl,tls
    'mailSMTPLogin' => '',
    'mailSMTPPass' => '',
);