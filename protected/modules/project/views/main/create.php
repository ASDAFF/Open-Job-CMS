<?php
/* @var $this ProjectController
 * @var $model Project
 */

HView::echoAndSetTitle('Добавить заказ');

$this->renderPartial('//../modules/project/views/backend/main/_form', array(
    'model' => $model,
    'newUser' => $newUser,
    'modelLogin' => $modelLogin,
));

?>