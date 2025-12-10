<section class='home-twitter'>
    <div class="grid-container">
        <div class="grid-x grid-margin-x">
            <div class="cell">
                <?php
                $twitter_shortcode = get_field('twitter');
                if ( $twitter_shortcode ) {
                    echo do_shortcode( $twitter_shortcode );
                }
                ?>
            </div>
        </div>
    </div>
</section>
</div>
<!-- END of main content -->
