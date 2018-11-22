<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<div class="ccm-block-express-form">
    <?php if (isset($renderer)) { ?>
        <div class="ccm-form">
            <a name="form<?php echo $bID?>"></a>

            <?php if (isset($success)) { ?>
                <div class="alert alert-success">
                    <?php echo $success?>
                </div>
            <?php } ?>

            <?php if (isset($error) && is_object($error)) { ?>
                <div class="alert alert-danger">
                    <?php echo $error->output()?>
                </div>
            <?php } ?>


            <form enctype="multipart/form-data" class="form-stacked" method="post" action="<?php echo $view->action('submit')?>#form<?php echo $bID?>">
            <?php
            print $renderer->render();

            if ($displayCaptcha) {
                $captcha = \Core::make('helper/validation/captcha');
                ?>
                <div class="form-group captcha">
                    <?php
                    $captchaLabel = $captcha->label();
                    if (!empty($captchaLabel)) {
                        ?>
                        <label class="control-label"><?php echo $captchaLabel;
                            ?></label>
                        <?php

                    }
                    ?>
                    <div><?php  $captcha->display(); ?></div>
                    <div><?php  $captcha->showInput(); ?></div>
                </div>
            <?php } ?>

            <div class="form-actions">
                <button type="submit" name="Submit" class="btn btn-primary"><?php echo t($submitLabel)?></button>
            </div>

            </form>

        </div>
    <?php } else { ?>
        <p><?php echo t('This form is unavailable.')?></p>
    <?php } ?>
</div>