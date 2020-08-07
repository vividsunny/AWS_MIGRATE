<?php
/**
 * This template displays received gift card table
 * 
 * This template can be overridden by copying it to yourtheme/wallet/premium/dashboard/gift-card-listing-received.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

do_action ( 'hrw_before_received_gift_card_listing' ) ;

if ( hrw_check_is_array ( $table_datas ) ) {

    $columns = array(
        get_option( 'hrw_advanced_sno_type' , '1' ) == '1' ? esc_html__( 'S.No' , HRW_LOCALE ) : esc_html__( 'IDs' , HRW_LOCALE ) ,
        esc_html__( 'Buyer Name' , HRW_LOCALE ) ,
        esc_html__( 'Gift Card' , HRW_LOCALE ) ,
        esc_html__( 'Amount' , HRW_LOCALE ) ,
        esc_html__( 'Reason' , HRW_LOCALE ) ,
        esc_html__( 'Received Date' , HRW_LOCALE ) ,
        esc_html__( 'Status' , HRW_LOCALE ) ,
        esc_html__( 'Redeemed Date' , HRW_LOCALE ) ,
        esc_html__( 'Expiry Date' , HRW_LOCALE ) ,
            ) ;
    ?>
    <div class = "hrw_gift_receiver_listing">
        <h2><?php echo esc_html__ ( 'Received Gift Card(s)' , HRW_LOCALE ) ; ?></h2>
        <table class="hrw_gift_receiver_listing_table hrw_frontend_table">
            <thead>
                <?php foreach ( $columns as $column_name ) { ?>
                <th><?php echo esc_html ( $column_name ) ; ?></th>
            <?php } ?>
            </thead>
            <tbody>
                <?php
                $i = 1 ;
                foreach ( $table_datas as $gift_id ) {
                    $gift_obj = hrw_get_gift ( $gift_id ) ;
                    ?>
                    <tr>
                        <td><?php echo get_option( 'hrw_advanced_sno_type' , '1' ) == '1' ? $i : '#' . esc_html( $gift_id ) ; ?></td>
                        <td><?php echo esc_html( $gift_obj->get_user_display() ) ; ?></td>
                        <td><?php echo esc_html( $gift_obj->get_gift_code() ) ; ?></td>
                        <td><?php echo hrw_price( $gift_obj->get_amount() ) ; ?></td>
                        <td><?php echo esc_html( $gift_obj->get_gift_reason() ) ; ?></td>
                        <td><?php echo esc_html( $gift_obj->get_formatted_created_date() ) ; ?></td>
                        <td><?php echo hrw_display_status( $gift_obj->get_Status() ) ; ?></td>
                        <td><?php echo esc_html( $gift_obj->get_formatted_redeemed_date() ) ; ?></td>
                        <td><?php echo esc_html( $gift_obj->get_formatted_expired_date( 'listing' ) ) ; ?></td>
                    </tr>
                    <?php
                    $i ++ ;
                }
                ?>
            </tbody>
            <?php if ( $pagination[ 'page_count' ] > 1 ) : ?>
                <tfoot>
                    <tr>
                        <td colspan="<?php echo esc_attr ( count ( $columns ) ) ; ?>" class="footable-visible">
                            <?php do_action ( 'hrw_frontend_pagination' , $pagination ) ; ?>
                        </td>
                    </tr>
                </tfoot>
            <?php endif ; ?>
        </table>
    </div>
    <?php
}

do_action ( 'hrw_after_received_gift_card_listing' ) ;
