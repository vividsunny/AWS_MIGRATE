<?php
/**
 * Email for user when the user is the winner of the auction
 *
 * @author  Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

    <h2><?php _e( 'You are not the winner!!', 'yith-auctions-for-woocommerce' ); ?></h2>
    <p><?php printf( esc_html__( "Sorry  %s, you are not the winner of the auction product:", 'yith-auctions-for-woocommerce' ),
            $email->object['user_name']);

        ?></p>

<?php
        $args = array(
            'product' => $email->object['product'],
            'url'           => $email->object['url_product'],
            'product_name'  => $email->object['product_name'],
        );
        wc_get_template('product-email.php', $args, '', YITH_WCACT_PATH .'templates/emails/product-emails/');
 ?>

    <div>
        <p><?php _e( 'Thank you for your participation', 'yith-auctions-for-woocommerce' ); ?></p>
    </div>



<?php do_action( 'woocommerce_email_footer', $email );