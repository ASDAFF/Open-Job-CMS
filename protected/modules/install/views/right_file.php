<h2>Необходимые параметры для установки CMS Open Business Card</h2>
<table class="result">
    <tr>
        <th>Директория/Файл</th>
        <th>Комментарий</th>
    </tr>
    <?php foreach ($aCheckDirErr['dirs'] as $sDirPath => $sRes): ?>
    <tr>
        <td width="60%"><?php echo $sDirPath; ?></td>
        <td class="<?php echo ($sRes == 'ok') ? 'passed' : 'failed'; ?>">
            <?php echo ($sRes == 'ok') ? 'ОК' : $sRes; ?>
        </td>
    </tr>
    <?php endforeach;?>
</table>