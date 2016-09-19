<?php
/**
 * @var User $recipient
 * @var $form BUserActiveForm
 * @var $message Message
 */

$title = 'Диалог с ' . $recipient->username;

$this->pageTitle = $title;

echo '<h5>' . $title . '</h5>';

$this->widget('application.modules.message.components.WMessage', array(
    'object' => $dialog,
    'recipient' => $recipient,
));

?>