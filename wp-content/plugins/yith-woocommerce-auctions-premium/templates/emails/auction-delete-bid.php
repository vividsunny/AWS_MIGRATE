<?php
/**
 * Email for user when the admin delete the bid for the customer
 *
 * @author  Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

    <p><?php printf( esc_html__( "Hi %s, your bid %s was removed for the following product:", 'yith-auctions-for-woocommerce' ),
            $email->object['user_name'], wc_price($email->object['args']['bid']));

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