<?php
defined('C5_EXECUTE') or die("Access Denied.");
?>
<fieldset>
    <?php if ($view->supportsLabel()) { ?>
        <legend class="control-label"><?php echo $view->getLabel()?></legend>
    <?php } ?>

    <?php if ($context->getTooltip()): ?>
        <i class="fa fa-question-circle launch-tooltip" title="" data-original-title="<?php echo $context->getTooltip()?>"></i>
    <?php endif; ?>

    <?php $view->renderControl()?>
</fieldset>
