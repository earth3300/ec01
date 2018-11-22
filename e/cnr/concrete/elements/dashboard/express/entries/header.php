<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<div class="ccm-header-search-form ccm-ui" data-header="express-search">
    <form method="get" action="<?php echo URL::to('/ccm/system/search/express/basic')?>?exEntityID=<?php echo $entity->getID()?>">
        <div class="input-group">

            <div class="ccm-header-search-form-input">
                <a class="ccm-header-reset-search" href="#"
                   data-button-action-url="<?php echo URL::to('/ccm/system/search/express/clear') ?>?exEntityID=<?php echo $entity->getID()?>"
                   data-button-action="clear-search"><?php echo t('Reset Search') ?></a>
                <a class="ccm-header-launch-advanced-search"
                   href="<?php echo URL::to('/ccm/system/dialogs/express/advanced_search')?>?exEntityID=<?php echo $entity->getID()?>"
                   data-launch-dialog="advanced-search"><?php echo t('Advanced') ?></a>
                <input type="text" class="form-control" autocomplete="off" name="eKeywords"
                       placeholder="<?php echo t('Search') ?>">
            </div>

              <span class="input-group-btn">
                <button class="btn btn-info" type="submit"><i class="fa fa-search"></i></button>
              </span>
        </div>

        <ul class="ccm-header-search-navigation">
            <li>
                <a href="<?php echo $exportURL ?>" class="link-primary">
                    <i class="fa fa-download"></i> <?php echo t('Export to CSV') ?>
                </a>
            </li>
            <li><a href="<?php echo $createURL ?>" class="link-primary"><i class="fa fa-plus"></i> <?php echo t('New %s',
                        $entity->getEntityDisplayName()) ?></a></li>
        </ul>

    </form>
</div>