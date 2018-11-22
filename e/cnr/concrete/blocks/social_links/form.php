<?php defined('C5_EXECUTE') or die("Access Denied.");  ?>

<div class="form-group">
    <label class="control-label"><?php echo t('Choose Social Links to Show')?></label>
    <div id="ccm-block-social-links-list">
    <?php
    if (count($links) == 0) {
        ?>
        <p><?php echo t('You have not added any social links.')?></p>
    <?php
    }

    foreach ($links as $link) {
        $service = $link->getServiceObject();
        ?>
        <div class="checkbox">
            <label><input type="checkbox" name="slID[]"
               <?php if (is_array($selectedLinks) && in_array($link, $selectedLinks)) {
    ?>
                 checked
                <?php
}
        ?>
            value="<?php echo $link->getID()?>" />
            <?php echo $service->getDisplayName()?>
            </label>

            <i class="fa fa-arrows"></i>
        </div>
    <?php
    } ?>
    </div>
</div>
<div class="alert alert-info">
    <?php echo t('Add social links <a href="%s">in the dashboard</a>', URL::to('/dashboard/system/basics/social'))?>
</div>

<style>
    #ccm-block-social-links-list {
        -webkit-user-select: none;
        position: relative;
    }

    #ccm-block-social-links-list .checkbox {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
                align-items: center;
        margin-bottom: 0;
        padding: 6px;
    }

    #ccm-block-social-links-list .checkbox:hover {
        background: #e7e7e7;
        border-radius: 4px;
        transition: background-color .1s linear;
    }

    #ccm-block-social-links-list .checkbox.ui-sortable-helper {
        background: none;
    }

    #ccm-block-social-links-list i.fa-arrows {
        display: none;
        color: #666;
        cursor: move;
        margin-left: auto;
    }

    #ccm-block-social-links-list div.checkbox:hover i.fa-arrows {
        display: block;
    }
</style>

<script>
$(function() {
    $('#ccm-block-social-links-list').sortable({
        axis: 'y'
    });
});
</script>
