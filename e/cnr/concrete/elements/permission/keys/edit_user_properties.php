<?php defined('C5_EXECUTE') or die('Access Denied.');

$included = $permissionAccess->getAccessListItems(PermissionKey::ACCESS_TYPE_INCLUDE);
$excluded = $permissionAccess->getAccessListItems(PermissionKey::ACCESS_TYPE_EXCLUDE);
$attributes = UserAttributeKey::getList();

$app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();
$form = $app->make('helper/form');
?>

<?php if (count($included) > 0 || count($excluded) > 0) { ?>
    <?php if (count($included) > 0) { ?>
        <h3><?php echo t('Who can edit what?')?></h3>

        <?php foreach ($included as $assignment) {
            $entity = $assignment->getAccessEntityObject();
        ?>
            <div class="clearfix">
            	<label class="control-label"><?php echo $entity->getAccessEntityLabel()?></label>
            	<div class="input">
            	    <?php echo $form->select('propertiesIncluded[' . $entity->getAccessEntityID() . ']', array('A' => t('All Attributes'), 'C' => t('Custom')), $assignment->getAttributesAllowedPermission())?>
                    <br>
                	<ul class="attribute-list inputs-list" <?php if ($assignment->getAttributesAllowedPermission() != 'C') { ?>style="display: none"<?php } ?>>
            		<?php foreach ($attributes as $ak) { ?>
            			<li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="akIDInclude[<?php echo $entity->getAccessEntityID()?>][]" value="<?php echo $ak->getAttributeKeyID()?>" <?php
                                    if ($assignment->getAttributesAllowedPermission() == 'A' || in_array($ak->getAttributeKeyID(), $assignment->getAttributesAllowedArray())) { ?> checked="checked" <?php } ?>>
                                    <span><?php echo $ak->getAttributeKeyDisplayName()?></span>
                                </label>
                            </div>
                        </li>
            		<?php
                    }
                    ?>
                	</ul>

                	<ul class="inputs-list">
                		<li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="allowEditUName[<?php echo $entity->getAccessEntityID()?>]" value="1" <?php if ($assignment->allowEditUserName()) { ?>checked="checked" <?php } ?>>
                                    <span><?php echo t('Username')?></span>
                                </label>
                            </div>
                        </li>
                		<li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="allowEditUEmail[<?php echo $entity->getAccessEntityID()?>]" value="1" <?php if ($assignment->allowEditEmail()) { ?>checked="checked" <?php } ?>>
                                    <span><?php echo t('Email Address')?></span>
                                </label>
                            </div>
                        </li>
                		<li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="allowEditUPassword[<?php echo $entity->getAccessEntityID()?>]" value="1" <?php if ($assignment->allowEditPassword()) { ?>checked="checked" <?php } ?>>
                                    <span><?php echo t('Password')?></span>
                                </label>
                            </div>
                        </li>
                		<li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="allowEditUAvatar[<?php echo $entity->getAccessEntityID()?>]" value="1" <?php if ($assignment->allowEditAvatar()) { ?>checked="checked" <?php } ?>>
                                    <span><?php echo t('Avatar')?></span>
                                </label>
                            </div>
                        </li>
                		<li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="allowEditUTimezone[<?php echo $entity->getAccessEntityID()?>]" value="1" <?php if ($assignment->allowEditTimezone()) { ?>checked="checked" <?php } ?>>
                                    <span><?php echo t('Timezone')?></span>
                                </label>
                            </div>
                        </li>
                		<li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="allowEditUDefaultLanguage[<?php echo $entity->getAccessEntityID()?>]" value="1" <?php if ($assignment->allowEditDefaultLanguage()) { ?>checked="checked" <?php } ?>>
                                    <span><?php echo t('Default Language')?></span>
                                </label>
                            </div>
                        </li>
                	</ul>
            	</div>
            </div>
        <?php
        }
    }
    ?>

    <?php if (count($excluded) > 0) { ?>
        <h3><?php echo t('Who can\'t edit what?')?></h3>
        <?php foreach ($excluded as $assignment) {
            $entity = $assignment->getAccessEntityObject();
        ?>
            <div class="clearfix">
            	<label class="control-label"><?php echo $entity->getAccessEntityLabel()?></label>
            	<div class="input">
                	<?php echo $form->select('propertiesExcluded[' . $entity->getAccessEntityID() . ']', array('N' => t('No Attributes'), 'C' => t('Custom')), $assignment->getAttributesAllowedPermission())?>
                    <br>
                	<ul class="attribute-list inputs-list" <?php if ($assignment->getAttributesAllowedPermission() != 'C') { ?>style="display: none"<?php } ?>>
                		<?php foreach ($attributes as $ak) { ?>
                			<li>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="akIDExclude[<?php echo $entity->getAccessEntityID()?>][]" value="<?php echo $ak->getAttributeKeyID()?>" <?php
                                        if (in_array($ak->getAttributeKeyID(), $assignment->getAttributesAllowedArray())) { ?> checked="checked" <?php } ?>>
                                        <span><?php echo $ak->getAttributeKeyDisplayName()?></span>
                                    </label>
                                </div>
                            </li>
                		<?php
                        }
                        ?>
                	</ul>

                	<ul class="inputs-list">
                		<li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="allowEditUNameExcluded[<?php echo $entity->getAccessEntityID()?>]" value="1" <?php if ($assignment->allowEditUserName()) { ?>checked="checked" <?php } ?>>
                                    <span><?php echo t('Username')?></span>
                                </label>
                            </div>
                        </li>
                		<li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="allowEditUEmailExcluded[<?php echo $entity->getAccessEntityID()?>]" value="1" <?php if ($assignment->allowEditEmail()) { ?>checked="checked" <?php } ?>>
                                    <span><?php echo t('Email Address')?></span>
                                </label>
                            </div>
                        </li>
                		<li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="allowEditUPasswordExcluded[<?php echo $entity->getAccessEntityID()?>]" value="1" <?php if ($assignment->allowEditPassword()) { ?>checked="checked" <?php } ?>>
                                    <span><?php echo t('Password')?></span>
                                </label>
                            </div>
                        </li>
                		<li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="allowEditUAvatarExcluded[<?php echo $entity->getAccessEntityID()?>]" value="1" <?php if ($assignment->allowEditAvatar()) { ?>checked="checked" <?php } ?>>
                                    <span><?php echo t('Avatar')?></span>
                                </label>
                            </div>
                        </li>
                		<li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="allowEditUTimezoneExcluded[<?php echo $entity->getAccessEntityID()?>]" value="1" <?php if ($assignment->allowEditTimezone()) { ?>checked="checked" <?php } ?>>
                                    <span><?php echo t('Timezone')?></span>
                                </label>
                            </div>
                        </li>
                		<li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="allowEditUDefaultLanguageExcluded[<?php echo $entity->getAccessEntityID()?>]" value="1" <?php if ($assignment->allowEditDefaultLanguage()) { ?>checked="checked" <?php } ?>>
                                    <span><?php echo t('Default Language')?></span>
                                </label>
                            </div>
                        </li>
                	</ul>
            	</div>
            </div>
        <?php
        }
    }
    ?>

<?php
} else { ?>
	<p><?php echo t('No users or groups selected.')?></p>
<?php
}
?>

<script>
$(function() {
	$("#ccm-tab-content-custom-options select").change(function() {
		if ($(this).val() == 'C') {
			$(this).parent().find('ul.attribute-list').show();
		} else {
			$(this).parent().find('ul.attribute-list').hide();
		}
	});
});
</script>
