<?php
/**
 * This template displays Withdrawal transactions
 * 
 * This template can be overridden by copying it to yourtheme/wallet/premium/dashboard/wallet-withdrawal-transactions.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

$withdrawal_columns = array(
    'number'            => get_option( 'hrw_advanced_sno_type' , '1' ) == '1' ? esc_html__( 'S.NO' , HRW_LOCALE ) : esc_html__( 'IDs' , HRW_LOCALE ) ,
    'withdrawal-amount' => esc_html__( 'Withdrawal  Amount' , HRW_LOCALE ) ,
    'reason'            => esc_html__( 'Reason' , HRW_LOCALE ) ,
    'req-date'          => esc_html__( 'Requested Date' , HRW_LOCALE ) ,
    'processed-date'    => esc_html__( 'Processed Date' , HRW_LOCALE ) ,
    'status'            => esc_html__( 'Status' , HRW_LOCALE ) ,
    'actions'           => esc_html__( 'Actions' , HRW_LOCALE )
        ) ;
?>
<div class="hrw_wallet_withdrawal_wrapper">
    <h2><?php esc_html_e( 'Wallet Withdrawal' , HRW_LOCALE ) ; ?></h2>
    <table class="hrw_frontend_table hrw_wallet_table">
        <thead>
            <tr>
                <?php foreach ( $withdrawal_columns as $column_id => $column_name ) : ?>
                    <th><?php echo esc_html( $column_name ) ; ?></th>
                <?php endforeach ; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            if ( ! hrw_check_is_array( $withdrawal_ids ) ) :
                ?>
                <tr>
                    <td colspan="<?php echo esc_attr( count( $withdrawal_columns ) ) ; ?>"><?php echo esc_html( 'No Wallet Withdrawals' , HRW_LOCALE ) ; ?></td>
                </tr>
                <?php
            else :
                $get_permalink = HRW_PROTOCOL . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ] ;
                foreach ( $withdrawal_ids as $id ) {
                    $withdrawal_obj = hrw_get_wallet_withdrawal( $id ) ;
                    $cancel_url     = esc_url_raw( add_query_arg( array( 'hrw_section' => 'wallet_withdrawal' , 'hrw_withdrawal_id' => $id ) , $get_permalink ) ) ;
                    ?>
                    <tr>
                        <td><?php echo get_option( 'hrw_advanced_sno_type' , '1' ) == '1' ? esc_html( $serial_number ) : '#' . absint( $id ) ; ?></td>
                        <td><?php echo hrw_price( $withdrawal_obj->get_amount() ) ; ?></td>
                        <td><?php echo esc_html( $withdrawal_obj->get_reason() ) ; ?></td>
                        <td><?php echo $withdrawal_obj->get_formatted_requested_date() ; ?></td>
                        <td><?php echo $withdrawal_obj->get_formatted_processed_date() ; ?></td>
                        <td><?php echo hrw_display_status( $withdrawal_obj->get_status() ) ; ?></td>
                        <?php if ( $withdrawal_obj->get_status() == 'hrw_unpaid' ) { ?>
                            <td><?php echo '<button class ="hrw_cancel_withdrawal" value="' . esc_attr( $withdrawal_obj->get_id() ) . '">' . esc_html__( 'Cancel' , HRW_LOCALE ) . '</a>' ; ?></td>
                        <?php } else { ?>
                            <td><?php echo esc_html( '-' ) ?></td>
                        <?php } ?>
                    </tr>
                    <?php
                    $serial_number ++ ;
                }
            endif ;
            ?>
        </tbody>
        <?php if ( $pagination[ 'page_count' ] > 1 ) : ?>
            <tfoot>
                <tr>
                    <td colspan="<?php echo esc_attr( count( $withdrawal_columns ) ) ; ?>" class="footable-visible">
                        <?php do_action( 'hrw_frontend_pagination' , $pagination ) ; ?>
                    </td>
                </tr>
            </tfoot>
        <?php endif ; ?>
    </table>
</div>

<?php

