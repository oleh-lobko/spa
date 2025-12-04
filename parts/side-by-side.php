<?php
$strip_bg = get_field('strip_background');
$main_color = get_field('main_color');
$sub_color = get_field('sub_color');
?>

<?php if (have_rows('sbs_blocks')) { ?>
    <section class="sbs-blocks">
        <?php while (have_rows('sbs_blocks')) {
            the_row(); ?>
            <?php
            $is_even = 0 === get_row_index() % 2;
            $row_color = $is_even ? $main_color : $sub_color;
            ?>
            <div class="sbs-block <?php echo $is_even ? 'sbs-block--reverse' : ''; ?>"
                <?php echo $strip_bg ? "style='background-color:{$row_color}'" : ''; ?>>
                <div class="grid-container">
                    <div
                        class="grid-x grid-margin-x align-middle <?php echo $is_even ? 'flex-dir-row-reverse' : ''; ?>">
                        <div class="cell medium-6 sbs-block__img-wrap">
                            <?php $block_image = get_sub_field('block_image'); ?>
                            <?php echo wp_get_attachment_image($block_image['ID'], 'medium_large', false, ['class' => 'sbs-block__img of-cover']); ?>
                        </div>
                        <div class="cell medium-6 sbs-block__content-wrap">
                            <div class="sbs-block__content">
                                <div class="sbs-block__content-inner">
                                    <?php the_sub_field('block_content'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </section>
<?php } ?>
