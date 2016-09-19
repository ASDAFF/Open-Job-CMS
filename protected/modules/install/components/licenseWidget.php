<?php
class licenseWidget extends CWidget
{
    public $autoOpen = true;

    public function run() {

        $license = '';
        //$license = @file_get_contents('http://bc.monoray.ru/license_obc.php?host='.$_SERVER['HTTP_HOST']);

        if ($license){
            echo $license;
        }else{
            $this->render('application.modules.install.views.license');
        }

    }
}