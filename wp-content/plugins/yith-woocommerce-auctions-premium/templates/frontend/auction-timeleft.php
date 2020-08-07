<?php
$auction_finish = ( $datetime = $product->get_end_date() ) ? $datetime : NULL;
$date = strtotime('now');
$total = $auction_finish - $date;
$product_id = $product->get_id();
?>
<div class="timer" id="timer_auction" data-remaining-time=" <?php echo $total ?>" data-finish-time="<?php echo $auction_finish ?>" data-finish="<?php echo $auction_finish?>">
    <span id="days"
          class="days_product_<?php echo $product->get_id() ?>"></span><?php esc_html_e('days', 'yith-auctions-for-woocommerce'); ?>
    <span id="hours"
          class="hours_product_<?php echo $product->get_id() ?>"></span><?php esc_html_e('hours', 'yith-auctions-for-woocommerce'); ?>
    <span id="minutes"
          class="minutes_product_<?php echo $product->get_id() ?>"></span><?php esc_html_e('minutes', 'yith-auctions-for-woocommerce'); ?>
    <span id="seconds"
          class="seconds_product_<?php echo $product->get_id() ?>"></span><?php esc_html_e('seconds', 'yith-auctions-for-woocommerce'); ?>
</div>
<div id="auction_end">
    <label
        for="_yith_auction_end" class="ywcact-auction-end"><?php esc_html_e('Auction ends: ', 'yith-auctions-for-woocommerce') ?></label>
    <?php
    $auction_end_formatted = date(wc_date_format() . ' ' . wc_time_format(), $auction_finish);
    ?>
    <p id="dateend" class="yith_auction_datetime_shop" data-finnish-shop="<?php echo $auction_finish ?>" data-yith-product="<?php echo $product_id?>"><?php echo $auction_end_formatted ?></p>
</div>