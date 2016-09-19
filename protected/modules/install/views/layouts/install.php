<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="en"/>
    <meta name="description" content="<?php echo CHtml::encode($this->pageDescription); ?>"/>
    <meta name="keywords" content="<?php echo CHtml::encode($this->pageKeywords); ?>"/>

    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css"/>

    <link rel="icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" type="image/x-icon"/>

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <style>
        #layout {
            position: relative;
            padding-left: 0;
        }
        #layout.active #menu {
            left: 150px;
            width: 150px;
        }

        #layout.active .menu-link {
            left: 150px;
        }

        .content, .footer {
            margin: 0 auto;
            padding: 0 2em;
            max-width: 800px;
            margin-bottom: 50px;
            line-height: 1.6em;
            background: #edf4ff;
            border: 1px solid #8a8a8c;
            border-radius: 3px;
        }

        .header {
            color: #333;
            text-align: center;
        }

        .overlay {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            transition: opacity 500ms;
            visibility: hidden;
            opacity: 0;
        }

        .overlay:target {
            visibility: visible;
            opacity: 1;
        }

        .popup {
            margin: 70px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            width: 30%;
            position: relative;
            transition: all 5s ease-in-out;
        }

        .popup h2 {
            margin-top: 0;
            color: #333;
            font-family: Tahoma, Arial, sans-serif;
        }

        .popup .close {
            position: absolute;
            top: 20px;
            right: 30px;
            transition: all 200ms;
            font-size: 30px;
            font-weight: bold;
            text-decoration: none;
            color: #333;
        }

        .popup .close:hover {
            color: #06D85F;
        }

        .popup .content {
            max-height: 30%;
            overflow: auto;
        }

        @media screen and (max-width: 700px) {
            .box {
                width: 70%;
            }

            .popup {
                width: 70%;
            }
        }

        div.flash-error, div.flash-notice, div.flash-success{ clear:both; padding:10px; margin:0 5px 25px; border:2px solid #ddd;}
        div.flash-error{ background:#FBE3E4; color:#8a1f11; border-color:#FBC2C4;}
        div.flash-notice{ background:#FFF6BF; color:#514721; border-color:#FFD324;}
        div.flash-success{background:#E6EFC2; color:#264409;	border-color:#C6D880;}
        div.flash-error a { color:#8a1f11;}
        div.flash-notice a{ color:#514721;}
        div.flash-success a{ color:#264409;}

    </style>
</head>

<body>
<div id="layout">

    <div id="main">
        <div class="logo header">
            <h1><?php echo Yii::app()->name ?></h1>

            <h2>Установка в один шаг</h2>
        </div>

        <div class="content">
            <div class="pure-g">
                <div class="pure-u-5-5">
                    <?php
                    $messages = Yii::app()->user->getFlashes();
                    if ($messages) {
                        foreach($messages as $key => $message) {
                            ?>
                            <div class="flash-<?php echo $key;?>">
                                <?php echo $message;?>
                            </div>
                            <?php
                        }
                    }
                    ?>

                    <?php echo $content; ?>
                </div>
            </div>
        </div>
    </div>

</div>


<div class="clear"></div>

<div class="footer">
    <p class="slogan"><a href="http://monoray.ru/products/open-job-cms"><?php echo Yii::app()->name ?></a></p>
</div>

</body>
</html>