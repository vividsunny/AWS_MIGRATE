<?php
/**
 * Email for admin when the admin delete the bid for the customer
 *
 * @author  Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

    <p><?php printf( esc_html__( "The bid %s made by %s was successfully removed for the following product:", 'yith-auctions-for-woocommerce' ),
            wc_price($email->object['args']['bid']), $email->object['user_name']);

        ?></p>

<?php
$args = array(
    'product' => $email->object['product'],
    'url'           => $email->object['url_product'],
    'product_name'  => $email->object['product_name'],
);
wc_get_template('product-email.php', $args, '', YITH_WCACT_PATH .'templates/emails/product-emails/');

?>

<?php do_action( 'woocommerce_email_footer', $email );