<?php
class requirementsWidget extends CWidget
{
    public $req;

    public function run()
    {
        $this->render('application.modules.install.views.requirements', array('req'=>$this->req));
    }
}