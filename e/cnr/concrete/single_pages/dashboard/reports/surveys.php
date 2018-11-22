<?php defined('C5_EXECUTE') or die("Access Denied.");

// Content
if ($this->controller->getTask() == 'viewDetail') { ?>
    <?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(
        t('Results for &#34;%s&#34;', $current_survey),
        false,
        false,
        false);
    ?>

    <div class="ccm-dashboard-header-buttons">
        <a href="<?php echo $view->action('view'); ?>" class="btn btn-default"><?php echo t('Go back'); ?></a>
    </div>

    <div>
        <div class="text-center">
            <?php echo $pie_chart; ?>
        </div>
        <?php echo $chart_options; ?>
    </div>

    <div class="table-responsive">
        <table class="ccm-search-results-table compact-results">
            <thead>
                <tr>
                    <th><span><?php echo t('Option'); ?></span></th>
                    <th><span><?php echo t('IP Address'); ?></span></th>
                    <th><span><?php echo t('Date'); ?></span></th>
                    <th><span><?php echo t('User'); ?></span></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($survey_details as $detail) { ?>
                <tr>
                    <td><?php echo $detail['option']; ?></td>
                    <td><?php echo $detail['ipAddress']; ?></td>
                    <td><?php echo $detail['date']; ?></td>
                    <td><?php echo $detail['user']; ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false); ?>
<?php } else { ?>
    <?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Surveys'), false, false); ?>

    <?php if (count($surveys) == 0) { ?>
    <p><?php echo t('You have not created any surveys.'); ?></p>
    <?php } else { ?>
    <div class="ccm-dashboard-content-full">
        <div class="table-responsive">
            <table class="ccm-search-results-table">
                <thead>
                    <tr>
                        <th class="<?php echo $surveyList->getSearchResultsClass('question'); ?>">
                            <a href="<?php echo $surveyList->getSortByURL('question', 'asc'); ?>">
                                <?php echo t('Name'); ?>
                            </a>
                        </th>
                        <th class="<?php echo $surveyList->getSearchResultsClass('cvName'); ?>">
                            <a href="<?php echo $surveyList->getSortByURL('cvName', 'asc'); ?>">
                                <?php echo t('Found on Page'); ?>
                            </a>
                        </th>
                        <th class="<?php echo $surveyList->getSearchResultsClass('lastResponse'); ?>">
                            <a href="<?php echo $surveyList->getSortByURL('lastResponse', 'desc'); ?>">
                                <?php echo t('Last Response'); ?>
                            </a>
                        </th>
                        <th class="<?php echo $surveyList->getSearchResultsClass('numberOfResponses'); ?>">
                            <a href="<?php echo $surveyList->getSortByURL('numberOfResponses', 'desc'); ?>">
                                <?php echo t('Number of Responses'); ?>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($surveys as $survey) { ?>
                    <tr>
                        <td>
                            <strong>
                                <a href="<?php echo $view->action(
                                    'viewDetail',
                                    $survey['bID'],
                                    $survey['cID']); ?>">
                                    <?php echo $survey['question']; ?>
                                </a>
                            </strong>
                        </td>
                        <td>
                            <?php echo $survey['cvName']; ?>
                        </td>
                        <td>
                            <?php echo $this->controller->formatDate($survey['lastResponse']); ?>
                        </td>
                        <td>
                            <?php echo $survey['numberOfResponses']; ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php }
    $surveyList->displayPagingV2();
    ?>

    <?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(); ?>

<?php } ?>
