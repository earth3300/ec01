<?php
defined('C5_EXECUTE') or die("Access Denied.");
?>

<div class="form-group">
    <?php if ($view->supportsLabel()) { ?>
        <label class="control-label"><?php echo $view->getLabel()?></label>
    <?php } ?>

    <?php if ($context->isRequired()) : ?>
        <span class="label label-info"><?php echo t('Required') ?></span>
    <?php endif; ?>

    <?php if ($context->getTooltip()): ?>
        <i class="fa fa-question-circle launch-tooltip" title="" data-original-title="<?php echo $context->getTooltip()?>"></i>
    <?php endif; ?>

    <?php $view->renderControl()?>
</div>
