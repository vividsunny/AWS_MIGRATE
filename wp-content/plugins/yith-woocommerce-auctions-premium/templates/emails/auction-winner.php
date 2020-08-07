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

    <h2><?php esc_html_e( 'You are the winner!', 'yith-auctions-for-woocommerce' ); ?></h2>
    <p><?php printf( esc_html__( "Congratulations  %s, you are the winner of the auction product:", 'yith-auctions-for-woocommerce' ),
            $email->object['user_name']);

        ?></p>

    <?php
        $args = array(
            'product'      => $email->object['product'],
            'url'          => $email->object['url_product'],
            'product_name' => $email->object['product_name'],
        );
        wc_get_template('product-email.php', $args, '', YITH_WCACT_PATH .'templates/emails/product-emails/');

        $url = add_query_arg( array( 'yith-wcact-pay-won-auction' => $email->object['product_id'] ), home_url() );
    ?>

    <div>
        <p><?php esc_html_e( 'Please, proceed to checkout', 'yith-auctions-for-woocommerce' ); ?></p>

        <a style="padding:6px 28px !important;font-size: 12px !important; background: #ccc !important; color: #333 !important; text-decoration: none!important; text-transform: uppercase!important; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif !important;font-weight: 800 !important; border-radius: 3px !important; display: inline-block !important;" href="<?php echo apply_filters( 'yith_wcact_winner_email_pay_now_url', $url,$email )  ?>"><?php echo esc_html__('Pay now', 'yith-auctions-for-woocommerce'); ?></a>
    </div>



<?php do_action( 'woocommerce_email_footer', $email );



//wc_get_template