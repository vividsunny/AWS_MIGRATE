<?php
/**
 * This template displays Transaction Logs
 * 
 * This template can be overridden by copying it to yourtheme/wallet/transaction-log-details.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
$columns = array (
    'serial_number' => get_option( 'hrw_localizations_transaction_log_sno_label' , 'S.No' ) ,
    'event'         => get_option( 'hrw_localizations_transaction_log_event_label' , 'Event' ) ,
    'amount'        => get_option( 'hrw_localizations_transaction_log_amount_label' , 'Amount' ) ,
    'status'        => get_option( 'hrw_localizations_transaction_log_status_label' , 'Status' ) ,
    'total'         => get_option( 'hrw_localizations_transaction_log_total_label' , 'Total' ) ,
    'date'          => get_option( 'hrw_localizations_transaction_log_date_label' , 'Date' ) ,
        ) ;
?>
<div class="hrw_transaction_log">
    <h2>
        <?php esc_html_e( 'Wallet Transactions' , HRW_LOCALE ) ; ?>
    </h2>
    <table class="hrw_transaction_log_table hrw_frontend_table">

        <thead>
            <tr>
                <?php foreach ( $columns as $column_id => $column_name ) : ?>
                    <th><?php echo esc_html( $column_name ) ; ?></th>
                <?php endforeach ; ?>
            </tr>
        </thead>

        <tbody>
            <?php
            
            if ( ! hrw_check_is_array( $transaction_logs )) :
                ?>
                <tr>
                    <td colspan="<?php echo esc_attr( count( $columns ) ) ; ?>"><?php echo esc_html( 'No Transaction Log' , HRW_LOCALE ) ; ?></td>
                </tr>
                <?php
            else :
                foreach ($transaction_logs as $id ) :
                    $transaction_obj = hrw_get_transaction_log( $id ) ;
                    ?>
                    <tr>
                        <td><?php echo get_option( 'hrw_advanced_sno_type' , '1' ) == '1' ? absint( $serial_number ) : '#' . absint( $id ) ; ?></td>
                        <td><?php echo wp_kses_post( $transaction_obj->get_event() ) ; ?></td>
                        <td><?php echo hrw_price( $transaction_obj->get_amount() ) ; ?></td>
                        <td><?php echo hrw_display_status( $transaction_obj->get_status() ) ; ?></td>
                        <td><?php echo hrw_price( $transaction_obj->get_total() ) ; ?></td>
                        <td><?php echo $transaction_obj->get_formatted_date() ; ?></td>
                    </tr>               
                    <?php
                    $serial_number ++ ;
                endforeach ;
                ?>
            </tbody>
            <?php if ( $pagination[ 'page_count' ] > 1 ) : ?>
                <tfoot>
                    <tr>
                        <td colspan="<?php echo esc_attr( count( $columns ) ) ; ?>" class="footable-visible">
                            <?php do_action( 'hrw_frontend_pagination' , $pagination ) ; ?>
                        </td>
                    </tr>
                </tfoot>
                <?php
            endif ;
        endif ;
        ?>
    </table>
</div>
<?php
