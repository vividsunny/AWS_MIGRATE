<?php
/**
 * Email for user when end auction
 *
 * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

    <p><?php esc_html_e( 'The Auction is about to end', 'yith-auctions-for-woocommerce' ); ?></p>
    <p><?php printf( esc_html__( "Hi!  %s, The auction for the product: %s is about to end in: %d %s", 'yith-auctions-for-woocommerce' ),
            $email->object['user_name'],
            $email->object['product_name'],
            $email->object['number'],
            $email->object['time']);
        ?></p>
    <div>
        <p><?php esc_html_e( 'If you want to bid a new amount, click this', 'yith-auctions-for-woocommerce' ); ?> <a href="<?php echo $email->object['url_product'];?>"><?php esc_html_e( 'link', 'yith-auctions-for-woocommerce' ); ?></a> </p>
    </div>

<?php do_action( 'woocommerce_email_footer', $email );