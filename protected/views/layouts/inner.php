<?php
$this->beginContent('//layouts/main');

$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'tabs', // '', 'tabs', 'pills' (or 'list')
    'stacked' => true, // whether this is a stacked menu
    'items' => $this->actionButtons,
));

echo $content;

$this->endContent();
?>

