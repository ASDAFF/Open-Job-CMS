<h2>Необходимые параметры для установки CMS Open Business Card</h2>
<table class="result">
    <tr>
        <th>Значение</th>
        <th>Результат</th>
        <th>Комментарий</th>
    </tr>
    <?php foreach ($req['requirements'] as $requirement): ?>
    <tr>
        <td width="200"><?php echo $requirement[0]; ?></td>
        <td class="<?php echo $requirement[2] ? 'passed' : ($requirement[1]
            ? 'failed' : 'warning'); ?>">
            <?php echo $requirement[2] ? 'ОК' : ($requirement[1] ? 'Ошибка'
            : 'Предупреждение'); ?>
        </td>
        <td><?php echo $requirement[4]; ?></td>
    </tr>
    <?php endforeach;?>
</table>