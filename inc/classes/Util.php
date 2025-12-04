<?php

namespace theme;

class Util
{
    public static function registerPostType(string $slug, string $singular, string $plural, array $args = [])
    {
        $labels = array_merge([
            'name' => $plural,
            'singular_name' => $singular,
            'add_new' => _x('Add New', 'backend: post type label', 'fwp'),
            'add_new_item' => sprintf(_x('Add New %s', 'backend: post type label', 'fwp'), $singular),
            'edit_item' => sprintf(_x('Edit %s', 'backend: post type label', 'fwp'), $singular),
            'new_item' => sprintf(_x('New %s', 'backend: post type label', 'fwp'), $singular),
            'view_item' => sprintf(_x('View %s', 'backend: post type label', 'fwp'), $singular),
            'search_items' => sprintf(_x('Search %s', 'backend: post type label', 'fwp'), $plural),
            'not_found' => sprintf(_x('No %s found', 'backend: post type label', 'fwp'), $singular),
            'not_found_in_trash' => sprintf(
                _x('No %s  in Trash', 'backend: post type label', 'fwp'),
                $plural
            ),
            'parent_item_colon' => sprintf(_x('Parent %s:', 'backend: post type label', 'fwp'), $singular),
            'menu_name' => $plural,
        ], isset($args['labels']) ? $args['labels'] : []);

        register_post_type($slug, array_merge([
            'public' => true,
            'has_archive' => false,
            'show_ui' => true,
            'show_in_rest' => true,
            'menu_position' => self::ptPositions($slug),
            'supports' => ['title', 'editor', 'thumbnail', 'revisions', 'excerpt'],
            'rewrite' => ['with_front' => false],
        ], $args, ['labels' => $labels]));
    }

    public static function registerTaxonomy($slug, $posttype, $singular, $plural, array $args = [])
    {
        register_taxonomy($slug, $posttype, array_merge([
            'labels' => [
                'name' => $plural,
                'singular_name' => $singular,
                'search_items' => sprintf(_x('Search %s', 'post type label', 'fwp'), $plural),
                'all_items' => sprintf(_x('All %s', 'post type label', 'fwp'), $plural),
                'parent_item' => sprintf(_x('Parent %s', 'post type label', 'fwp'), $singular),
                'parent_item_colon' => sprintf(_x('Parent %s:', 'post type label', 'fwp'), $singular),
                'edit_item' => sprintf(_x('Edit %s', 'post type label', 'fwp'), $singular),
                'update_item' => sprintf(_x('Update %s', 'post type label', 'fwp'), $singular),
                'add_new_item' => sprintf(_x('Add New %s', 'post type label', 'fwp'), $singular),
                'new_item_name' => sprintf(_x('New %sname', 'post type label', 'fwp'), $singular),
                'menu_name' => $plural,
            ],
            'show_ui' => true,
            'show_in_rest' => true,
            'hierarchical' => true,
        ], $args));
    }

    public static function ptPositions($type)
    {
        $positions = [
            'page' => 11,
            // Seperator         => 20
            // Seperator         => 30
            'client' => 31,
            'project' => 32,
            'base_themes' => 33,
        ];

        return key_exists($type, $positions) ? $positions[$type] : 41;
    }

    /**
     * Prepares associative array for data-props attribute by escaping double quotes.
     *
     * @param array $data
     *
     * @return string
     */
    public static function prepareProps($data)
    {
        return htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Prepares associative array for data-props attribute by escaping double quotes.
     *
     * @param string $name
     * @param array $props
     */
    public static function showComponent($name, $props = [])
    {
        $prepared_props = self::prepareProps($props);
        echo "<div data-component=\"{$name}\" data-props=\"{$prepared_props}\"></div>";
    }

    /**
     * @param int|\WP_Post $post Optional. Post ID or post object. Default is the global `$post`.
     */
    public static function getRelativePermalink($post = 0)
    {
        if (!is_object($post)) {
            $post = get_post($post);
        }

        if (empty($post->ID)) {
            return null;
        }

        return self::makeRelativeUrl(get_permalink($post));
    }

    /**
     * @param string $url
     */
    public static function makeRelativeUrl($url = '')
    {
        if (empty($url)) {
            return null;
        }

        return str_replace(home_url(''), '', $url);
    }

    /**
     * @param string $string
     */
    public static function sanitizeString($string = '')
    {
        $string = preg_replace('/[^A-Za-z0-9 \-]/', '', $string); // Removes special chars.

        return trim(preg_replace('/ +/', ' ', $string)); // Replaces multiple hyphens with single one.
    }

    /**
     * Insert an attachment from a URL address.
     *
     * @param string $url The URL address
     * @param null|int $parent_post_id (optional) The parent post ID
     *
     * @return false|int The attachment ID on success. False on failure.
     */
    public static function wpInsertAttachmentFromUrl($url, $parent_post_id = null)
    {
        if (!class_exists('WP_Http')) {
            require_once ABSPATH . WPINC . '/class-http.php';
        }

        $http = new \WP_Http();
        $response = $http->request($url);
        if (200 !== $response['response']['code']) {
            return false;
        }

        $upload = wp_upload_bits(basename($url), null, $response['body']);
        if (!empty($upload['error'])) {
            return false;
        }

        $file_path = $upload['file'];
        $file_name = basename($file_path);
        $file_type = wp_check_filetype($file_name, null);
        $attachment_title = sanitize_file_name(pathinfo($file_name, PATHINFO_FILENAME));
        $wp_upload_dir = wp_upload_dir();

        $post_info = [
            'guid' => $wp_upload_dir['url'] . '/' . $file_name,
            'post_mime_type' => $file_type['type'],
            'post_title' => $attachment_title,
            'post_content' => '',
            'post_status' => 'inherit',
        ];

        // Create the attachment.
        $attach_id = wp_insert_attachment($post_info, $file_path, $parent_post_id);

        // Include image.php.
        require_once ABSPATH . 'wp-admin/includes/image.php';

        // Generate the attachment metadata.
        $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);

        // Assign metadata to attachment.
        wp_update_attachment_metadata($attach_id, $attach_data);

        return $attach_id;
    }

    public static function getPageData(int $id)
    {
        return [
            'id' => $id,
            'title' => get_the_title($id),
            'permalink' => self::getRelativePermalink($id),
            'example_acf_field' => get_field('example_acf_field', $id),
        ];
    }
}
