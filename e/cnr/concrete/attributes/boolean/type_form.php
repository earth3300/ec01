<fieldset>
    <legend><?php echo t('Checkbox Options') ?></legend>

    <div class="form-group">
        <label class="control-label"><?php echo t("Default Value") ?></label>
        <div class="checkbox"><label><?php echo $form->checkbox('akCheckedByDefault', 1,
                    $akCheckedByDefault) ?> <?php echo t('The checkbox will be checked by default.') ?></label>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php echo t("Label") ?></label>
        <?php echo $form->text('akCheckboxLabel', $akCheckboxLabel) ?>
        <p class="help-block"><?php echo t('This will be displayed next to the checkbox. If it is blank, "Yes" will be displayed.')?></p>
    </div>


</fieldset>