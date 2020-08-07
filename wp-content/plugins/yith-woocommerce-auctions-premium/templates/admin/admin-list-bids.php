<?php
$product = wc_get_product($post_id);

if ( $product ) {

    $instance = YITH_Auctions()->bids;
    $auction_list = $instance->get_bids_auction($product->get_id());
    ?>
    <input type="hidden" id="yith-wcact-product-id" name="yith-wcact-product"
           value="<?php echo esc_attr($product->get_id()); ?>">
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
                <td class="toptable"><?php echo esc_html__('Datetime', 'yith-auctions-for-woocommerce'); ?></td>
                <td class="toptable"><?php echo esc_html__('Actions', 'yith-auctions-for-woocommerce'); ?></td>

            </tr>
            <?php
            foreach ($auction_list as $object => $id) {
                $user = get_user_by('id', $id->user_id);
                $user = get_user_by('id', $id->user_id);
                $username = ($user) ? $user->data->user_nicename : 'anonymous';
                $bid = $id->bid;
                ?>
                <tr class="yith-wcact-row">
                    <td><a href="user-edit.php?user_id=<?php echo absint($id->user_id) ?>"><?php echo $username ?></a>
                    </td>
                    <td><?php echo wc_price($bid) ?></td>
                    <td class="yith_auction_datetime"><?php echo get_date_from_gmt($id->date); ?></td>
                    <td><input type="button" class="button button-default yith-wcact-delete-bid"
                               data-user-id="<?php echo absint(($id->user_id)) ?>"
                               data-date-time="<?php echo $id->date ?>" data-product-id="<?php echo $post_id ?>"
                               data-bid="<?php echo $id->bid ?>"
                               value="<?php esc_html_e('Delete bid', 'yith-auctions-for-woocommerce') ?>"></td>

                </tr>
                <?php
            }
            if ($product->is_start() && $auction_list) {
                ?>
                <tr class="yith-wcact-row">
                    <td><?php esc_html_e('Start auction', 'yith-auctions-for-woocommerce') ?></td>
                    <td><?php echo wc_price($product->get_start_price()) ?></td>
                    <td></td>
                </tr>
                <?php
            }
            ?>

        </table>
        <?php
        if (count($auction_list) == 0) {
            ?>
            <p id="single-product-no-bid"><?php esc_html_e('There is no bid for this product', 'yith-auctions-for-woocommerce'); ?></p>

            <?php
        }
    }
}
