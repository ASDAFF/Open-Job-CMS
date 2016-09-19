<?php
HView::echoAndSetTitle('Добавить навык');

$this->renderPartial('_form', array('model' => $model));
?>