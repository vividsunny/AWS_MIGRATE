<?php

/**
 * Class BK_Helper_Extra_Costs.
 * This helper class should ONLY be used for unit tests!.
 */
class BK_Helper_Extra_Costs {
    /**
     * Create extra cost.
     *
     * @param string $title
     * @return WP_Post
     */
    public static function create_extra_cost( $title = 'Dummy Extra Cost' ) {
        // Create the extra cost
        $id = wp_insert_post( array(
                                  'post_title'  => $title,
                                  'post_type'   => YITH_WCBK_Post_Types::$extra_cost,
                                  'post_status' => 'publish',
                              ) );

        return get_post( $id );
    }

    /**
     * delete an extra cost
     *
     * @param int|WP_Post $post
     */
    public static function delete_extra_cost( $post ) {
        $post_id = $post instanceof WP_Post ? $post->ID : $post;
        wp_delete_post( $post_id );
    }
}
