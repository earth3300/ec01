<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<?php $included = $permissionAccess->getAccessListItems(); ?>
<?php $excluded = $permissionAccess->getAccessListItems(PermissionKey::ACCESS_TYPE_EXCLUDE); ?>
<?php $pageTypes = PageType::getList(false, $permissionAccess->getPermissionObject()->getSiteTreeObject()->getSiteType()); ?>
<?php $form = Loader::helper('form'); ?>

<?php if (count($included) > 0 || count($excluded) > 0) {
    ?>

<?php if (count($included) > 0) {
    ?>

<h4><?php echo t('Who can add what?')?></h4>

<?php foreach ($included as $assignment) {
    $entity = $assignment->getAccessEntityObject();
    ?>


<div class="form-group">
	<label class="control-label"><?php echo $entity->getAccessEntityLabel()?></label>
	<?php echo $form->select('pageTypesIncluded[' . $entity->getAccessEntityID() . ']', array('A' => t('All Page Types'), 'C' => t('Custom')), $assignment->getPageTypesAllowedPermission())?>
	<div class="page-type-list inputs-list" <?php if ($assignment->getPageTypesAllowedPermission() != 'C') {
    ?>style="display: none"<?php 
}
    ?>>
		<?php foreach ($pageTypes as $ct) {
    ?>
			<div class="checkbox"><label><input type="checkbox" name="ptIDInclude[<?php echo $entity->getAccessEntityID()?>][]" value="<?php echo $ct->getPageTypeID()?>" <?php if (in_array($ct->getPageTypeID(), $assignment->getPageTypesAllowedArray())) {
    ?> checked="checked" <?php 
}
    ?> /> <?php echo $ct->getPageTypeDisplayName()?></label></div>
		<?php 
		}
    ?>
	</div>
	<div class="inputs-list">
		<div class="checkbox"><label><input type="checkbox" name="allowExternalLinksIncluded[<?php echo $entity->getAccessEntityID()?>]" value="1" <?php if ($assignment->allowExternalLinks()) {
    ?>checked="checked" <?php 
}
    ?> /> <?php echo t('Allow External Links')?></label></div>
	</div>

</div>


<?php 
}
}
    ?>


<?php if (count($excluded) > 0) {
    ?>

<h4><?php echo t('Who can\'t add what?')?></h4>

<?php foreach ($excluded as $assignment) {
    $entity = $assignment->getAccessEntityObject();
    ?>


<div class="form-group">
	<label class="control-label"><?php echo $entity->getAccessEntityLabel()?></label>
	<?php echo $form->select('pageTypesExcluded[' . $entity->getAccessEntityID() . ']', array('N' => t('No Page Types'), 'C' => t('Custom')), $assignment->getPageTypesAllowedPermission())?>
	<div class="page-type-list inputs-list" <?php if ($assignment->getPageTypesAllowedPermission() != 'C') {
    ?>style="display: none"<?php 
}
    ?>>
		<?php foreach ($pageTypes as $ct) {
    ?>
			<div class="checkbox"><label><input type="checkbox" name="ptIDExclude[<?php echo $entity->getAccessEntityID()?>][]" value="<?php echo $ct->getPageTypeID()?>" <?php if (in_array($ct->getPageTypeID(), $assignment->getPageTypesAllowedArray())) {
    ?> checked="checked" <?php 
}
    ?> /> <?php echo $ct->getPageTypeDisplayName()?></label></div>
		<?php 
}
    ?>
	</div>
	<div class="inputs-list">
		<div class="checkbox"><label><input type="checkbox" name="allowExternalLinksExcluded[<?php echo $entity->getAccessEntityID()?>]" value="1" <?php if ($assignment->allowExternalLinks()) {
    ?>checked="checked" <?php 
}
    ?> /> <?php echo t('Allow External Links')?></label></div>
	</div>
</div>



<?php 
}
}
    ?>

<?php 
} else {
    ?>
	<p><?php echo t('No users or groups selected.')?></p>
<?php 
} ?>

<script type="text/javascript">
$(function() {
	$("#ccm-tab-content-custom-options select").change(function() {
		if ($(this).val() == 'C') {
			$(this).parent().find('.page-type-list').show();
		} else {
			$(this).parent().find('.page-type-list').hide();
		}
	});
});
</script>