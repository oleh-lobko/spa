<?php

namespace theme;

class RestEndpoints
{
    public function __construct()
    {
        add_action('rest_api_init', function () {
            // GET /wp-json/fwp/page
            register_rest_route(
                'fwp',
                '/page(?:/(?P<id>\d+))?',
                [
                    'methods' => 'GET',
                    'callback' => [__CLASS__, 'getPage'],
                ]
            );
        });
    }

    public static function getPage(\WP_REST_Request $request)
    {
        try {
            $response = [];
            $params = $request->get_url_params();
            $post_type = 'page';
            if (!empty($params['id'])) {
                if ('publish' !== get_post_status($params['id']) || 'page' !== get_post_type($params['id'])) {
                    throw new \Exception("Not found! Tried to get #{$params['id']}", 404);
                }

                $response = Util::getPageData($params['id']);
            } else {
                $items = get_posts([
                    'post_type' => $post_type,
                    'order' => $request['order'] ?: 'ASC',
                    'orderby' => $request['orderby'] ?: 'name',
                    'numberposts' => $request['per_page'] ?: get_option('posts_per_page'),
                    'paged' => $request['current_page'] ?: 1,
                    'post_status' => 'publish',
                    'fields' => 'ids',
                ]);

                if (empty($items)) {
                    throw new \Exception('Nothing to show!', 404);
                }

                $response['data'] = array_map(function ($id) {
                    return Util::getPageData($id);
                }, $items);

                $response['total_items'] = count(get_posts([
                    'post_type' => $post_type,
                    'numberposts' => -1,
                    'post_status' => 'publish',
                    'fields' => 'ids',
                ]));
            }

            return new \WP_REST_Response($response, 200);
        } catch (\Exception $e) {
            return new \WP_REST_Response(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}
