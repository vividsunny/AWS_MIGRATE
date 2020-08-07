<?php

/**
 * Class BK_Unit_Test_Case_With_Store.
 * This helper class should ONLY be used for unit tests!.
 */
class BK_Unit_Test_Case_With_Store extends WP_UnitTestCase {

    protected $debug_time = 0;
    /**
     * @var WC_Product[] Array of products to clean up.
     */
    protected $products = array();

    /**
     * @var YITH_WCBK_Booking[] Array of bookings to clean up.
     */
    protected $bookings = array();

    /**
     * @var WP_Post[] Array of posts to clean up.
     */
    protected $posts = array();

    /**
     * Helper function to hold a reference to created product objects so they
     * can be cleaned up properly at the end of each test.
     *
     * @param WC_Product $product The product object to store.
     */
    protected function store_product( $product ) {
        $this->products[] = $product;
    }

    /**
     * Helper function to hold a reference to created booking objects so they
     * can be cleaned up properly at the end of each test.
     *
     * @param YITH_WCBK_Booking $booking The booking object to store.
     */
    protected function store_booking( $booking ) {
        $this->bookings[] = $booking;
    }

    /**
     * Helper function to hold a reference to created post objects so they
     * can be cleaned up properly at the end of each test.
     *
     * @param $post WP_Post|WP_Post[] The person type post(s) to store.
     */
    protected function store_post( $post ) {
        if ( is_array( $post ) ) {
            $this->posts = array_merge( $this->posts, $post );
        } else {
            $this->posts[] = $post;
        }
    }

    /**
     * Helper function to hold a reference to created product objects so they
     * can be cleaned up properly at the end of each test.
     *
     * @return WC_Product_Booking
     */
    protected function create_and_store_booking_product() {
        $product = BK_Helper_Booking_Product::create_booking_product();
        $this->store_product( $product );

        return $product;
    }

    /**
     * Helper function to hold a reference to created booking objects so they
     * can be cleaned up properly at the end of each test.
     *
     * @param array $args
     * @return YITH_WCBK_Booking
     */
    protected function create_and_store_booking( $args = array() ) {
        $booking = BK_Helper_Booking::create_booking( $args );
        $this->store_booking( $booking );

        return $booking;
    }

    /**
     * Helper function to hold a reference to created person type objects so they
     * can be cleaned up properly at the end of each test.
     *
     * @param string|null $title
     * @return WP_Post
     */
    protected function create_and_store_person_type( $title = null ) {
        $person_type = BK_Helper_Person_Types::create_person_type( $title );
        $this->store_post( $person_type );

        return $person_type;
    }

    /**
     * Helper function to hold a reference to created extra cost objects so they
     * can be cleaned up properly at the end of each test.
     *
     * @param string|null $title
     * @return WP_Post
     */
    protected function create_and_store_extra_cost( $title = null ) {
        $extra_cost = BK_Helper_Extra_Costs::create_extra_cost( $title );
        $this->store_post( $extra_cost );

        return $extra_cost;
    }

    public function setUp() {
        $this->debug_time = microtime( true );
        parent::setUp();

        $this->products = array();
    }

    /**
     * Clean up after each test. DB changes are reverted in parent::tearDown().
     */
    public function tearDown() {

        foreach ( $this->products as $product ) {
            $product->delete( true );
        }

        foreach ( $this->posts as $post ) {
            wp_delete_post( $post->ID );
        }

        foreach ( $this->bookings as $booking ) {
            wp_delete_post( $booking->get_id() );
        }

        parent::tearDown();

        $seconds = round( microtime( true ) - $this->debug_time, 5 );

        fwrite( STDOUT, "\n" . $this->getName() . ' (' . $seconds . ' seconds) ' );
    }
}
