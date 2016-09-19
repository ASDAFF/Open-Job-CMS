<?php
$this->beginContent('//layouts/main');
?>

<?php
if (UserModule::isAdmin()) {
    $this->widget('bootstrap.widgets.TbNavbar', array(
        'brand' => 'Управление',
        'fixed' => false,
        'collapse' => true,
        'type' => 'inner',
        'items' => array(
            array(
                'class' => 'bootstrap.widgets.TbMenu',
                'items' => HMenu::getAdminMenu(),
            )
        ),
    ));
}

$messages = Yii::app()->user->getFlashes();
if ($messages) {
    foreach($messages as $key => $message) {
        ?>
        <div class="alert in alert-block fade alert-<?php echo $key;?>">
            <a class="close" data-dismiss="alert">×</a>
            <?php echo $message;?>
        </div>
        <?php
    }
}
?>

    <div class="row">
        <div class="span9">
            <?php
            if ($this->showButtons) {
                $this->widget('bootstrap.widgets.TbMenu', array(
                    'type' => 'tabs', // '', 'tabs', 'pills' (or 'list')
                    'stacked' => false, // whether this is a stacked menu
                    'items' => $this->actionButtons,
                ));
            }
            ?>
            <?php echo $content; ?>

            <?php HSitebar::getProjectItems(); ?>
        </div>

        <div class="span3">
            <?php
            if (Yii::app()->user->id) {
                $this->widget('bootstrap.widgets.TbMenu', array(
                    'type' => 'tabs',
                    'encodeLabel' => false,
                    'stacked' => true,
                    'items' => HMenu::getLeftItems(),
                ));
            }

            if($this->categoryItems){
                echo '<h3>Категории</h3>';
                $this->widget('bootstrap.widgets.TbMenu', array(
                    'type' => 'tabs',
                    'encodeLabel' => false,
                    'stacked' => true,
                    'items' => $this->categoryItems,
                ));
            }
            ?>

        </div>

    </div>

<?php $this->endContent(); ?>