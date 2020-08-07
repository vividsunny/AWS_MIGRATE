<?php

/**
 * Class BK_Helper_Person_Types.
 *
 * This helper class should ONLY be used for unit tests!.
 */
class BK_Helper_Person_Types {
    /**
     * Create person type.
     *
     * @param string $title
     *
     * @return WP_Post
     */
    public static function create_person_type( $title = 'Dummy Person Type' ) {
        // Create the product
        $id = wp_insert_post( array(
                                  'post_title'  => $title,
                                  'post_type'   => YITH_WCBK_Post_Types::$person_type,
                                  'post_status' => 'publish',
                              ) );

        return get_post( $id );
    }

    /**
     * delete a person type
     *
     * @param int|WP_Post $post
     */
    public static function delete_person_type( $post ) {
        $post_id = $post instanceof WP_Post ? $post->ID : $post;
        wp_delete_post( $post_id );
    }

    /**
     * create Adult, Teenager and Child person types
     *
     * @return WP_Post[]
     */
    public static function create_three_person_types() {
        $titles       = array( 'Adult', 'Teenager', 'Child' );
        $person_types = array();

        foreach ( $titles as $title ) {
            $person_types[] = self::create_person_type( $title );
        }

        return $person_types;
    }
}
