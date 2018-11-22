<?php
defined('C5_EXECUTE') or die("Access Denied.");
?>

<?php if (!empty($exportURL)): ?>
    <a class="btn btn-default" href="<?php echo $exportURL?>"><i class="fa fa-download"></i> <?php echo t('Export to CSV') ?></a>
<?php endif ?>

<?php if (!empty($supportsLegacy)): ?>
    <a href="<?php echo URL::to('/dashboard/reports/forms/legacy')?>" class="btn btn-default"><?php echo t('Legacy Forms')?></a>
<?php endif ?>