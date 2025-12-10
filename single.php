<?php
/**
 * Single Post Template - SPA Style
 */
get_header(); ?>

    <main class="main-content single-post-page">
        <div class="grid-container">
            <div class="grid-x grid-margin-x">
                <?php if (have_posts()) { ?>
                    <?php while (have_posts()) {
                        the_post(); ?>

                        <!-- Main Content -->
                        <div class="cell large-8">
                            <article id="post-<?php the_ID(); ?>" <?php post_class('single-post-article'); ?>>

                                <!-- Post Header -->
                                <header class="single-post-header">
                                    <div class="single-post-meta">
                                        <div class="meta-line">
                                            <?php
                                            $story_date = get_field('story_date');
                                            $story_category = get_field('story_category');
                                            $story_author = get_field('story_author');

                                            // Fallback to WordPress data if ACF fields are empty
                                            if (!$story_date) {
                                                $story_date = get_the_date('M j');
                                            }

                                            if (!$story_author) {
                                                $story_author = get_the_author();
                                            }

                                            if (!$story_category) {
                                                $categories = get_the_category();
                                                if (!empty($categories)) {
                                                    $story_category = strtoupper($categories[0]->name);
                                                }
                                            }
                                            ?>

                                            <?php if ($story_date): ?>
                                                <span class="meta-date"><?php echo esc_html($story_date); ?></span>
                                            <?php endif; ?>

                                            <?php if ($story_category): ?>
                                                <?php if ($story_date): ?>
                                                    <span class="meta-separator">|</span>
                                                <?php endif; ?>
                                                <span class="meta-category"><?php echo esc_html($story_category); ?></span>
                                            <?php endif; ?>

                                            <?php if ($story_author): ?>
                                                <?php if ($story_date || $story_category): ?>
                                                    <span class="meta-separator">|</span>
                                                <?php endif; ?>
                                                <span class="meta-author"><?php echo esc_html($story_author); ?></span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="reading-time">
                                            <?php

                                            $word_count = str_word_count(strip_tags(get_the_content()));
                                            $reading_time = ceil($word_count / 200);
                                            ?>
                                            <?php echo $reading_time; ?> min read
                                        </div>
                                    </div>

                                    <h1 class="single-post-title"><?php the_title(); ?></h1>

                                    <?php if (has_post_thumbnail()): ?>
                                        <div class="single-post-thumbnail">
                                            <?php the_post_thumbnail('large', array('loading' => 'eager')); ?>
                                        </div>
                                    <?php endif; ?>
                                </header>

                                <!-- Post Content -->
                                <div class="single-post-content">
                                    <?php the_content(); ?>
                                </div>

                                <!-- Post Footer -->
                                <footer class="single-post-footer">
                                    <!-- Categories -->
                                    <?php $categories = get_the_category(); ?>
                                    <?php if (!empty($categories)): ?>
                                        <div class="post-categories">
                                            <span class="categories-label">Categories:</span>
                                            <div class="categories-list">
                                                <?php foreach ($categories as $category): ?>
                                                    <a href="<?php echo get_category_link($category->term_id); ?>"
                                                       class="category-tag">
                                                        <?php echo esc_html($category->name); ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Tags -->
                                    <?php $tags = get_the_tags(); ?>
                                    <?php if (!empty($tags)): ?>
                                        <div class="post-tags">
                                            <span class="tags-label">Tags:</span>
                                            <div class="tags-list">
                                                <?php foreach ($tags as $tag): ?>
                                                    <a href="<?php echo get_tag_link($tag->term_id); ?>"
                                                       class="tag-item">
                                                        <?php echo esc_html($tag->name); ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Post Navigation -->
                                    <nav class="single-post-navigation">
                                        <?php
                                        $prev_post = get_previous_post();
                                        $next_post = get_next_post();
                                        ?>

                                        <?php if ($prev_post): ?>
                                            <div class="nav-previous">
                                                <a href="<?php echo get_permalink($prev_post->ID); ?>" class="nav-link">
                                                    <span class="nav-direction">← Previous Post</span>
                                                    <span class="nav-title"><?php echo esc_html($prev_post->post_title); ?></span>
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($next_post): ?>
                                            <div class="nav-next">
                                                <a href="<?php echo get_permalink($next_post->ID); ?>" class="nav-link">
                                                    <span class="nav-direction">Next Post →</span>
                                                    <span class="nav-title"><?php echo esc_html($next_post->post_title); ?></span>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </nav>
                                </footer>

                                <!-- Comments -->
                                <?php if (comments_open() || get_comments_number()): ?>
                                    <div class="single-post-comments">
                                        <?php comments_template(); ?>
                                    </div>
                                <?php endif; ?>
                            </article>
                        </div>

                        <!-- Sidebar -->
                        <div class="cell large-4 sidebar-wrapper">
                            <?php get_sidebar(); ?>
                        </div>

                    <?php } ?>
                <?php } else { ?>
                    <div class="cell">
                        <div class="no-post-found">
                            <h2>Post not found</h2>
                            <p>Sorry, the requested post does not exist.</p>
                            <a href="<?php echo home_url(); ?>" class="btn-back-home">Return to home page</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>

<?php get_footer(); ?>
