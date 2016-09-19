<?php
/* @var $this ProjectController
 * @var $model Project
 */

HView::echoAndSetTitle('Редактирование заказа');

$this->renderPartial('//../modules/project/views/backend/main/_form', array('model' => $model)); ?>