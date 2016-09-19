<?php
HView::echoAndSetTitle('Редактирование навыка');

$this->renderPartial('_form', array('model' => $model));
?>