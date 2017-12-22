<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2017 NRE
 *
 * @var array $fields
 */
?>

<div class="row">
    <?php foreach ($fields as $field): ?>
        <div class="<?= $itemWrapClass ?>">
            <?= $field ?>
        </div>
    <?php endforeach ?>
</div>
 