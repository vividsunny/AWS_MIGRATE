<?php
$product = wc_get_product($post_id);

$to_auction   = ( $datetime = $product->get_end_date() ) ? absint( $datetime ) : '';
$to_auction   = $to_auction ? get_date_from_gmt( date( 'Y-m-d H:i:s', $to_auction ) ) : '';
$instance = YITH_Auctions()->bids;
$max_bidder = $instance->get_max_bid($product->get_id());
if($max_bidder) {
    $user = get_user_by('id', $max_bidder->user_id);
    $username = $user->data->user_nicename;
}
?>

<div class="yith-wcact-admin-auction-status">
    <div>
        <?php esc_html_e( 'Status:','yith-auctions-for-woocommerce' ) ?> <span><?php echo $product->get_auction_status(); ?></span>
    </div>
    <div>
        <?php esc_html_e('End time:','yith-auctions-for-woocommerce') ?> <span><?php echo $to_auction ?></span>
    </div>
    <?php if(!$product->is_closed()) { ?>

        <?php if($max_bidder) { ?>

                <div>
                    <?php esc_html_e( 'Max bidder:','yith-auctions-for-woocommerce' ) ?> <span><a href="user-edit.php?user_id=<?php echo absint( $max_bidder->user_id )  ?>"><?php echo $username ?></a></span>
                </div>
        <?php
            } else {
                esc_html_e( 'Max bidder:' ) ?> <span id=""> <?php esc_html_e('There is no bid for this product','yith-auctions-for-woocommerce'); ?> </span>
        <?php
        }
        ?>
    <?php } else {

        $winner_email = $product->get_send_winner_email();
        $check_email_is_send = yit_get_prop($product,'yith_wcact_winner_email_is_send',true);
        $user_email_information = yit_get_prop($product,'yith_wcact_winner_email_send_custoner',true);

        if( $winner_email  ) {
            if( apply_filters('yith_wcact_check_email_is_send',$check_email_is_send,$product )) {
            ?>
                <?php esc_html_e('Email is send to:','yith-auctions-for-woocommerce') ?> <span><a href="user-edit.php?user_id=<?php echo absint( $user_email_information->data->ID )  ?>"><?php echo $user_email_information->user_login ?></a>( <?php echo $user_email_information->data->user_email ?> )</span>
                <?php echo '<p class="form-field"><input type="button" class="button" id="yith-wcact-send-winner-email" value="' . esc_html__('Send Winner Email', 'yith-auctions-for-woocommerce') . '"></p>';

                } elseif( yit_get_prop($product, 'yith_wcact_winner_email_is_not_send', true )) {

                ?>
                <?php esc_html_e('Email is send to:', 'yith-auctions-for-woocommerce') ?>
                <span><?php esc_html_e('Error send the email', 'yith-auctions-for-woocommerce') ?></span>
                <?php echo '<p class="form-field"><input type="button" class="button" id="yith-wcact-send-winner-email" value="' . esc_html__('Send Winner Email', 'yith-auctions-for-woocommerce') . '"></p>'; ?>

                <?php
            }else {

            }
        } else {

            echo esc_html_e('Error send the email', 'yith-auctions-for-woocommerce');
            echo '<p class="form-field"><input type="button" class="button" id="yith-wcact-send-winner-email" value="' . esc_html__('Send Winner Email', 'yith-auctions-for-woocommerce') . '"></p>';
        } //Todo create an else in order to allow resend winner email if it's fail
    ?>
    <?php } ?>
</div>
