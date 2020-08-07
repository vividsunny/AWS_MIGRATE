<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( $product instanceof WC_Product && 'auction' == $product->get_type() ) {

    $instance = YITH_Auctions()->bids;
    $auction_list = $instance->get_bids_auction(apply_filters( 'yith_wcact_auction_product_id',$product->get_id()));
    ?>
    <div class="yith-wcact-table-bids">

          <input type="hidden" id="yith-wcact-product-id" name="yith-wcact-product" value="<?php echo esc_attr( $product->get_id() );?>">
        <?php
        if( apply_filters('yith_wcact_show_list_bids',true)) {
            ?>
            <?php
            if (count($auction_list) == 0) {
                ?>

                <p id="single-product-no-bid"><?php esc_html_e('There is no bid for this product', 'yith-auctions-for-woocommerce'); ?></p>

                <?php
            } else {
                ?>
                <table id="datatable">
                    <tr>
                        <td class="toptable"><?php echo esc_html__('Username', 'yith-auctions-for-woocommerce'); ?></td>
                        <td class="toptable"><?php echo esc_html__('Bid Amount', 'yith-auctions-for-woocommerce'); ?></td>
                        <td class="toptable"><?php echo apply_filters('yith_wcact_datetime_table', esc_html__('Datetime', 'yith-auctions-for-woocommerce')); ?></td>
                    </tr>
                    <?php
                    $option = get_option('yith_wcact_settings_tab_auction_show_name');
                    foreach ($auction_list as $object => $id) {
                        $user = get_user_by('id', $id->user_id);
                        $username = ($user) ? $user->data->user_nicename : apply_filters('yith_wcact_display_user_anonymous_name','anonymous',$user);

                        if ( 'no' == $option || apply_filters('yith_wcact_tab_auction_show_name',false,$id->user_id) ) {

                            $len = strlen($username);
                            $start = 1;
                            $end = 1;
                            $username = substr($username, 0, $start) . str_repeat('*', $len - ($start + $end)) . substr($username, $len - $end, $end);
                        }

                        if ($object == 0) {
                            $bid = $product->get_price();
                            ?>
                            <tr>
                                <td><?php echo $username ?></td>
                                <td><?php echo wc_price($bid,array('currency' => $currency)); ?></td>
                                <td class="yith_auction_datetime"><?php echo $id->date ?></td>
                            </tr>
                            <?php
                        } elseif ($id->bid <= apply_filters('yith_wcact_auction_bid',$product->get_price(),$currency)) {
                            $bid = $id->bid;
                            ?>
                            <tr>
                                <td><?php echo $username ?></td>
                                <td><?php echo apply_filters('yith_wcact_auction_product_price',wc_price($bid),$bid,$currency); ?></td>
                                <td class="yith_auction_datetime"><?php echo $id->date ?></td>
                            </tr>
                            <?php
                        }
                    }
                    if ( $product->is_start() && $auction_list ) {
                        ?>
                        <tr>
                            <td><?php esc_html_e('Start auction', 'yith-auctions-for-woocommerce') ?></td>
                            <td><?php echo apply_filters('yith_wcact_auction_product_price',wc_price($product->get_start_price(),$currency),$product->get_start_price(),$currency); ?></td>
                            <td></td>
                        </tr>
                        <?php
                    }
                    ?>

                </table>
                <?php
                if ( count($auction_list) == 0 ) {
                    ?>

                    <p id="single-product-no-bid"><?php esc_html_e('There is no bid for this product', 'yith-auctions-for-woocommerce'); ?></p>

                    <?php
                }
            }
        }
        ?>
    </div>

<?php }  ?>