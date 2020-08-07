<?php
/**
 * This template displays fund transfer transactions
 * 
 * This template can be overridden by copying it to yourtheme/wallet/premium/dashboard/fund-transfer-transactions.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

$transactions_columns = array(
    'number'             => get_option( 'hrw_advanced_sno_type' , '1' ) == '1' ? esc_html__( 'S.NO' , HRW_LOCALE ) : esc_html__( 'IDs' , HRW_LOCALE ) ,
    'user'               => esc_html__( 'Username' , HRW_LOCALE ) ,
    'transfered-total'   => esc_html__( 'Total Amount Transferred' , HRW_LOCALE ) ,
    'received-total'     => esc_html__( 'Total Amount Received' , HRW_LOCALE ) ,
    'last-activity-date' => esc_html__( 'Last Activity Date' , HRW_LOCALE ) ,
    'status'             => esc_html__( 'Status' , HRW_LOCALE ) ,
    'actions'            => esc_html__( 'Actions' , HRW_LOCALE )
        ) ;
?>
<div class="hrw_fund_transfer_transactions_wrapper">
    <h2>
        <?php esc_html_e( 'Transactions ' , HRW_LOCALE ) ; ?>
    </h2>
    <table class="hrw_frontend_table hrw_fund_transfer_transactions_table">
        <thead>
            <tr>
                <?php foreach ( $transactions_columns as $column_id => $column_name ) : ?>
                    <th><?php echo esc_html( $column_name ) ; ?></th>
                <?php endforeach ; ?>
            </tr>
        </thead>
        <tbody>

            <?php
            if ( ! hrw_check_is_array( $transactions ) ) :
                ?>
                <tr>
                    <td colspan="<?php echo esc_attr( count( $transactions_columns ) ) ; ?>"><?php echo esc_html( 'No Transactions' , HRW_LOCALE ) ; ?></td>
                </tr>

                <?php
            else:
                $current_page_url = HRW_Dashboard::get_current_page_url() ;
                foreach ( $transactions as $id ) :
                    $transaction_obj = hrw_get_fund_transfer( $id ) ;
                    ?>
                    <tr>
                        <td><?php echo get_option( 'hrw_advanced_sno_type' , '1' ) == '1' ? esc_html( $serial_number ) : '#' . esc_html( $id ) ; ?></td>
                        <td><?php echo esc_html( $transaction_obj->get_receiver()->display_name ) ; ?></td>
                        <td><?php echo hrw_price( $transaction_obj->get_total_transfered() ) ; ?></td>
                        <td><?php echo hrw_price( $transaction_obj->get_total_received() ) ; ?></td>
                        <td><?php echo $transaction_obj->get_formatted_last_activity() ; ?></td>
                        <td><?php echo hrw_display_status( $transaction_obj->get_status() ) ; ?></td>
                        <td><a href="<?php echo esc_url( add_query_arg( array( 'fund_transfer_view' => $id ) , $current_page_url ) ) ; ?>" class="hrw_view_details_btn"><i class="fa fa-eye"></i><?php esc_html_e( ' View Details' , HRW_LOCALE ) ?></a></td>
                    </tr>

                    <?php
                    $serial_number ++ ;
                endforeach ;
            endif ;
            ?>
        </tbody>
        <?php if ( $pagination[ 'page_count' ] > 1 ) : ?>
            <tfoot>
                <tr>
                    <td colspan="<?php echo esc_attr( count( $transactions_columns ) ) ; ?>" class="footable-visible">
                        <?php do_action( 'hrw_frontend_pagination' , $pagination ) ; ?>
                    </td>
                </tr>
            </tfoot>
        <?php endif ; ?>
    </table>
</div>
<?php
