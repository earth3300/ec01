<?php
defined('C5_EXECUTE') or die("Access Denied.");
?>

<div class="form-group">
    <?php if ($view->supportsLabel()) { ?>
        <label class="control-label"><?php echo $view->getLabel()?></label>
    <?php } ?>

    <?php if ($view->isRequired()) { ?>
        <span class="text-muted small"><?php echo t('Required')?></span>
    <?php } ?>

    <?php $view->renderControl()?>
</div>
