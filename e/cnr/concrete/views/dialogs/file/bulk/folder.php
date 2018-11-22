<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<form method="post" data-dialog-form="move-to-folder" action="<?php echo $controller->action('submit')?>">
    <?php foreach ($files as $f) {
    ?>
        <input type="hidden" name="fID[]" value="<?php echo $f->getFileID()?>" />
    <?php 
} ?>

    <div class="ccm-ui">
        <?php Loader::element('files/bulk/move_to_folder', array('files' => $files))?>
    </div>

    <div class="dialog-buttons">
        <button class="btn btn-default pull-left" data-dialog-action="cancel"><?php echo t('Cancel')?></button>
        <button type="button" data-dialog-action="submit" class="btn btn-primary pull-right"><?php echo t('Save')?></button>
    </div>

</form>

<script type="text/javascript">
    $(function() {
        ConcreteEvent.unsubscribe('AjaxFormSubmitSuccess.updateFolder');
        ConcreteEvent.subscribe('AjaxFormSubmitSuccess.updateFolder', function(e, data) {
            if (data.form == 'move-to-folder') {
                ConcreteEvent.publish('FolderUpdateRequestComplete', {
                    'folder': data.response.folder
                });
            }
        });

    });
</script>