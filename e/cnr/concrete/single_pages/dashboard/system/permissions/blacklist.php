<?php
defined('C5_EXECUTE') or die('Access Denied.');

/* @var Concrete\Core\Page\View\PageView $view */
/* @var Concrete\Core\Validation\CSRF\Token $token */
/* @var Concrete\Core\Form\Service\Form $form */

/* @var bool $banEnabled */
/* @var int $allowedAttempts */
/* @var int $attemptsTimeWindow */
/* @var int $banDuration */

$view->element('dashboard/system/permissions/blacklist/menu', ['type' => null]);
?>

<form method="post" id="ipblacklist-form" action="<?php echo $view->action('update_ipblacklist') ?>">
    <?php $token->output('update_ipblacklist') ?>
    <div class="ccm-pane-body">
        <div class="form-group form-inline">
            <?php echo $form->checkbox('banEnabled', 1, $banEnabled) ?> <?php echo t('Lock IP after') ?>
            <?php echo $form->number('allowedAttempts', $allowedAttempts, ['style' => 'width:70px', 'min' => 1]) ?>
            <?php echo t(/*i18n: before we have the number of failed logins, after we have a time duration */'failed login attempts in') ?>
            <?php echo $form->number('attemptsTimeWindow', $attemptsTimeWindow, ['style' => 'width:90px', 'min' => 1]) ?>
            <?php echo t('seconds') ?>
        </div>

        <div class="form-inline form-group radio">
            <?php echo t('Ban IP For') ?>
            <br />
            <label class="radio">
                <?php echo $form->radio('banDurationUnlimited', 0, $banDuration ? 0 : 1) ?>
                <?php echo $form->number('banDuration', $banDuration ?: 300, ['style' => 'width:90px', 'min' => 1]) ?>
                <?php echo t('minutes') ?>
            </label>
            <br />
            <label class="radio">
                <?php echo $form->radio('banDurationUnlimited', 1, $banDuration ? 0 : 1) ?> <?php echo t('Forever') ?>
            </label>
        </div>
    </div>

    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <input type="submit" class="btn btn-primary pull-right" value="<?php echo t('Save') ?>" />
        </div>
    </div>
</form>
