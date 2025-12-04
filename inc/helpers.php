<?php
/**
 * Custom logo.
 *
 * @param string $size
 */
function show_custom_logo($size = 'medium'): void
{
    if ($custom_logo_id = get_theme_mod('custom_logo')) {
        $attachment_array = wp_get_attachment_image_src($custom_logo_id, $size);
        $logo_url = $attachment_array[0];
    } else {
        $logo_url = asset_path('images/custom-logo.png');
    }
    $logo_image = sprintf(
        '<img src="%s" class="custom-logo" itemprop="siteLogo" alt="%s"/>',
        $logo_url,
        get_bloginfo('name')
    );
    $html = sprintf(
        '<a href="%1$s" class="custom-logo-link" rel="home" title="%2$s" itemscope>%3$s</a>',
        esc_url(home_url('/')),
        get_bloginfo('name'),
        $logo_image
    );
    echo apply_filters('get_custom_logo', $html);
}

/**
 * Create pagination within context.
 *
 * @param string $query
 */
function foundation_pagination($query = ''): void
{
    if (empty($query)) {
        global $wp_query;
        $query = $wp_query;
    }

    $big = 999999999;

    $links = paginate_links([
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'prev_next' => true,
        'prev_text' => '&laquo;',
        'next_text' => '&raquo;',
        'current' => max(1, get_query_var('paged')),
        'total' => $query->max_num_pages,
        'type' => 'list',
    ]);

    $pagination = str_replace('page-numbers', 'pagination', $links);

    echo $pagination;
}

/**
 * Enqueue external fonts with font-display: swap; property.
 *
 * @param string $url fonts link GoogleFonts/Typekit
 * @param bool $echo echo or return the styles
 *
 * @return string|void
 */
function enqueue_fonts($url, $echo = true)
{
    $response = wp_remote_get($url);
    $body = wp_remote_retrieve_body($response);

    if (false === strpos($body, 'font-display')) {
        $body = str_replace('}', 'font-display:swap;}', $body);
    }

    ob_start(); ?>
    <style><?php echo $body; ?></style>
    <?php

    if (!$echo) {
        return ob_get_clean();
    }
    echo ob_get_clean();
}

/**
 * Output HTML markup of template with passed args.
 *
 * @param string $file File name without extension (.php)
 * @param array $args Array with args ($key=>$value)
 * @param string $default_folder Requested file folder
 */
function show_template($file, $args = null, $default_folder = 'parts'): void
{
    echo return_template($file, $args, $default_folder);
}

/**
 * Return HTML markup of template with passed args.
 *
 * @param string $file File name without extension (.php)
 * @param array $args Array with args ($key=>$value)
 * @param string $default_folder Requested file folder
 *
 * @return string template HTML
 */
function return_template($file, $args = null, $default_folder = 'parts')
{
    $file = $default_folder . '/' . $file . '.php';
    if ($args) {
        extract($args);
    }
    if (locate_template($file)) {
        ob_start();

        include locate_template($file); // Theme Check free. Child themes support.
        $template_content = ob_get_clean();

        return $template_content;
    }

    return '';
}

/**
 * Get Post Featured image.
 *
 * @param int $id Post id
 * @param string $size = 'full' featured image size
 *
 * @return string Post featured image url
 */
function get_attached_img_url($id = 0, $size = 'medium_large')
{
    $img = wp_get_attachment_image_src(get_post_thumbnail_id($id), $size);

    return $img[0];
}

/**
 * Dynamic admin function.
 *
 * @param string $column_name Column id
 * @param int $post_id Post id
 */
function template_detail_field_for_page($column_name, $post_id): void
{
    if ('template' == $column_name) {
        $template_name = str_replace(
            '.php',
            '',
            get_post_meta($post_id, '_wp_page_template', true)
        );
        echo '<span style="text-transform: capitalize;">'
            . str_replace(
                ['template-', '/'],
                '',
                substr($template_name, strpos($template_name, '/'), strlen($template_name))
            )
            . ' Page</span>';
    }
}

/**
 * Output background image style.
 *
 * @param array|string $img Image array or url
 * @param string $size Image size to retrieve
 * @param bool $echo Whether to output the the style tag or return it
 *
 * @return string|void String when retrieving
 */
function bg($img = '', $size = '', $echo = true)
{
    if (empty($img)) {
        return false;
    }

    if (is_array($img)) {
        $url = $size && !empty($img['sizes'][$size]) ? $img['sizes'][$size] : $img['url'];
    } else {
        $url = $img;
    }

    $string = 'style="background-image: url(' . $url . ')"';

    if ($echo) {
        echo $string;
    } else {
        return $string;
    }
}

/**
 * Format phone number, trim all unnecessary characters.
 *
 * @param string $string Phone number
 *
 * @return string Formatted phone number
 */
function sanitize_number($string)
{
    return preg_replace('/[^+\d]+/', '', $string);
}

/**
 * Convert file url to path.
 *
 * @param string $url Link to file
 *
 * @return bool|mixed|string
 */
function convert_url_to_path($url)
{
    if (!$url) {
        return false;
    }

    $url = str_replace(['https://', 'http://'], '', $url);
    $home_url = str_replace(['https://', 'http://'], '', site_url());
    $file_part = ABSPATH . str_replace($home_url, '', $url);
    $file_part = str_replace('//', '/', $file_part);

    if (file_exists($file_part)) {
        return $file_part;
    }

    return false;
}

/**
 * Return/Output SVG as html.
 *
 * @param array|string $img Image link or array
 * @param string $class Additional class attribute for img tag
 * @param string $size Image size if $img is array
 */
function display_svg($img, $class = '', $size = 'medium'): void
{
    echo return_svg($img, $class, $size);
}

function return_svg($img, $class = '', $size = 'medium')
{
    if (!$img) {
        return '';
    }

    $file_url = is_array($img) ? $img['url'] : $img;

    $file_info = pathinfo($file_url);
    if ('svg' == $file_info['extension']) {
        $file_path = convert_url_to_path($file_url);

        if (!$file_path) {
            return '';
        }

        $arrContextOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ];
        $image = file_get_contents($file_path, false, stream_context_create($arrContextOptions));
        if ($class) {
            $image = str_replace('<svg ', '<svg class="' . esc_attr($class) . '" ', $image);
        }
        $image = preg_replace('/^.?(<svg.+<\/svg>).?$/is', '$1', $image);
    } elseif (is_array($img)) {
        $image = wp_get_attachment_image($img['id'], $size, false, ['class' => $class]);
    } else {
        $image = sprintf(
            '<img class="%1$s" src="%2$s" alt="%3$s"/>',
            esc_attr($class),
            esc_url($img),
            esc_attr($file_info['filename'])
        );
    }

    return $image;
}

/**
 * Check if URL is YouTube or Vimeo video.
 *
 * @param string $url Link to video
 *
 * @return bool
 */
function is_embed_video($url)
{
    if (!$url) {
        return false;
    }

    $yt_pattern = '#^https?://(?:www\.)?(?:youtube\.com/watch|youtu\.be/)#';
    $vimeo_pattern = '#^https?://(.+\.)?vimeo\.com/.*#';

    $is_vimeo = preg_match($vimeo_pattern, $url);
    $is_youtube = preg_match($yt_pattern, $url);

    return $is_vimeo || $is_youtube;
}

/**
 * Render block template.
 *
 * @param mixed $filepath
 * @param mixed $atts
 */
function render_block_tempalte($filepath, $atts = [])
{
    if (!empty($atts)) {
        extract($atts);
    }

    if ($template = locate_template(str_replace(get_theme_file_path(), '', $filepath))) {
        ob_start();

        include $template; // Theme Check free. Child themes support.
        echo ob_get_clean();
    }

    echo '';
}
