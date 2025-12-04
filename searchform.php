<?php
/**
 * Searchform.
 *
 * Custom template for search form
 */
?>
<!-- BEGIN of search form -->
<form method="get" class="search" action="<?php echo esc_url(home_url('/')); ?>">
    <input type="search" name="s" class="search__input" placeholder="<?php _e('Search', 'fwp'); ?>"
           value="<?php echo get_search_query(); ?>" aria-label="<?php _e('Search input', 'fwp'); ?>" />
    <button type="submit" name="submit" class="search__submit"
            aria-label="<?php _e('Submit search', 'fwp'); ?>"><?php _e('Search', 'fwp'); ?></button>
</form>
<!-- END of search form -->
