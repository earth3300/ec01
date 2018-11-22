<?php
/**
 * @type int $review A number between 0 and 5
 * @type bool $starsOnly Should editing be disabled
 */
if (!isset($starsOnly) || !$starsOnly) {
    ?>
    <label for="review" class="pull-left">
        <?php echo t('Rating') ?>&nbsp;
    </label>
    <?php
}
?>
<div class="star-rating <?php echo $selector = uniqid('rating') ?>" data-name="review" data-score="<?php echo intval($review) ?>""></div>

<script>
    (function() {
        var stars = $('.<?php echo $selector ?>').awesomeStarRating();
        <?php
        if (isset($starsOnly) && $starsOnly) {
            ?>
            $('.<?php echo $selector ?>').children().unbind();
            <?php
        }
        ?>
    }());
</script>
