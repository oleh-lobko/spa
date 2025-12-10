<!-- Stories Section -->
<?php
$stories_query = new WP_Query([
    'post_type' => 'post',
    'posts_per_page' => get_option('posts_per_page'),
    'post_status' => 'publish',
]);

if ($stories_query->have_posts()) { ?>
    <section class="stories-section">


        <div class="grid-container">
            <div class="grid-x grid-margin-x">

                    <!-- Simple colored block for stories -->
                <div class="cell large-12">
                    <div class="stories-color-block"></div>
                </div>
                <div class="cell">
                    <div class="stories-header">
                        <h2 class="stories-title">Stories</h2>

                        <?php
                        $story_link = get_field('story_link');
                        if ($story_link) { ?>
                            <a href="<?php echo esc_url($story_link['url']); ?>" class="stories-see-all">
                                <?php echo esc_html($story_link['title']); ?>
                            </a>
                        <?php } ?>
                    </div>
                </div>

                <div class="cell">
                    <div class="stories-grid">
                        <?php while ($stories_query->have_posts()) {
                            $stories_query->the_post(); ?>
                            <div class="story-item">
                                <?php if (has_post_thumbnail()) { ?>
                                    <div class="story-image">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium_large'); ?>
                                        </a>
                                    </div>
                                <?php } ?>

                                <div class="story-content">
                                    <div class="story-meta">
                                        <?php
                                        $post_date = get_the_date('Y-m-d');
                                        $timestamp = strtotime($post_date);
                                        $story_date = strtoupper(date('M', $timestamp)) . ' ' . date('j', $timestamp);

                                        $categories = get_the_category();
                                        $story_category = !empty($categories) ? strtoupper($categories[0]->name) : '';

                                        $story_author = get_the_author();

                                        $tags = get_the_tags();
                                        $story_news = '';
                                        if ($tags) {
                                            $story_news = strtoupper($tags[0]->name);
                                        }
                                        ?>

                                        <div class="story-meta-line">
                                            <?php if ($story_date) { ?>
                                                <span class="story-date"><?php echo esc_html($story_date); ?></span>
                                            <?php } ?>

                                            <?php if ($story_news) { ?>
                                                <?php if ($story_date) { ?>
                                                    <span class="story-separator">|</span>
                                                <?php } ?>
                                                <span
                                                    class="story-category"><?php echo esc_html($story_news); ?></span>
                                            <?php } ?>

                                            <?php if ($story_category) { ?>
                                                <?php if ($story_date || $story_news) { ?>
                                                    <span class="story-separator">|</span>
                                                <?php } ?>
                                                <span
                                                    class="story-category"><?php echo esc_html($story_category); ?></span>
                                            <?php } ?>
                                        </div>

                                        <div class="story-author"><?php echo esc_html($story_author); ?></div>
                                    </div>

                                    <h3 class="story-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>

                                    <div class="story-excerpt">
                                        <?php
                                        $excerpt = get_the_excerpt();
                                        if (strlen($excerpt) > 120) {
                                            $excerpt = substr($excerpt, 0, 120) . '<span style="color: #f75097;">...</span>';
                                        }
                                        echo $excerpt;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } wp_reset_postdata(); ?>
