<?php
// Arguments
/* @var Concrete\Core\Localization\Service\Date $dateHelper */
/* @var Concrete\Core\Localization\Translation\Local\Stats $local */
/* @var Concrete\Core\Localization\Translation\Remote\Stats $remote */

?>
<table class="table">
    <caption><h4><?php echo t('Local translations file') ?></h4></caption>
    <tbody>
        <tr>
            <th><?php echo t('File path') ?></th>
            <td><code><?php echo h($local->getFileDisplayName()) ?></code></td>
        </tr>
        <tr>
            <th><?php echo t('Version') ?></th>
            <td><?php echo ($local->getVersion() === '') ? '' : ('<code>' . h($local->getVersion()) . '</code>') ?></code></td>
        </tr>
        <tr>
            <th><?php echo t('Updated on') ?></th>
            <td><?php echo ($local->getUpdatedOn() === null) ? '' : $dateHelper->formatPrettyDateTime($local->getUpdatedOn(), true, true) ?></td>
        </tr>
    </tbody>
</table>
<table class="table">
    <caption><h4><?php echo t('Remote translations file') ?></h4></caption>
    <tbody>
        <tr>
            <th><?php echo t('Version') ?></th>
            <td><?php echo ($remote->getVersion() === '') ? '' : ('<code>' . h($remote->getVersion()) . '</code>') ?></td>
        </tr>
        <tr>
            <th><?php echo t('Updated on') ?></th>
            <td><?php echo ($remote->getUpdatedOn() === null) ? '' : $dateHelper->formatPrettyDateTime($remote->getUpdatedOn(), true, true) ?></td>
        </tr>
        <tr>
            <th><?php echo t('Total strings') ?></th>
            <td><?php echo $remote->getTotal() ?></td>
        </tr>
        <tr>
            <th><?php echo t('Translated strings') ?></th>
            <td><?php echo $remote->getTranslated() ?></td>
        </tr>
        <tr>
            <th><?php echo t('Untranslated strings') ?></th>
            <td><?php echo $remote->getTotal() - $remote->getTranslated() ?></td>
        </tr>
        <tr>
            <th><?php echo t('Translation progress') ?></th>
            <td><?php echo $remote->getProgress() ?>%</td>
        </tr>
    </tbody>
</table>
