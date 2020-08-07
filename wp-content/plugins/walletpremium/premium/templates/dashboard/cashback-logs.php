<?php
/**
 * This template displays cashback logs
 * 
 * This template can be overridden by copying it to yourtheme/wallet/premium/dashboard/cashback-logs.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

$cashback_columns = array(
    'number' => get_option( 'hrw_advanced_sno_type' , '1' ) == '1' ? esc_html__( 'S.NO' , HRW_LOCALE ) : esc_html__( 'IDs' , HRW_LOCALE ) ,
    'event'  => esc_html__( 'Event' , HRW_LOCALE ) ,
    'credit' => esc_html__( 'Credit' , HRW_LOCALE ) ,
    'debit'  => esc_html__( 'Debit' , HRW_LOCALE ) ,
    'date'   => esc_html__( 'Date' , HRW_LOCALE )
        ) ;
?>
<div class="hrw_cashback_log_wrapper">
    <h2>
        <?php esc_html_e( "Total Cashback Received" , HRW_LOCALE ) ; ?>
        <span class="hrw_cashback_price"><?php echo hrw_price( $total_cashback ) ; ?></span>
    </h2>
    <table class="hrw_frontend_table hrw_cashback_log_table">
        <thead>
            <tr>
                <?php foreach ( $cashback_columns as $column_id => $column_name ) : ?>
                    <th><?php echo esc_html( $column_name ) ; ?></th>
                <?php endforeach ; ?>
            </tr>
        </thead>
        <tbody>

            <?php
            if ( ! hrw_check_is_array( $cashback ) ) :
                ?>
                <tr>
                    <td colspan="<?php echo esc_attr( count( $cashback_columns ) ) ; ?>"><?php echo esc_html( 'No Cashback Log' , HRW_LOCALE ) ; ?></td>
                </tr>

                <?php
            else:
                foreach ( $cashback as $id ) :
                    $cashback = hrw_get_cashback_log( $id ) ;
                    ?>
                    <tr>
                        <td><?php echo get_option( 'hrw_advanced_sno_type' , '1' ) ? esc_html( $serial_number ) : '#' . esc_html( $id ) ; ?></td>
                        <td><?php echo esc_html( $cashback->get_event() ) ; ?></td>
                        <td><?php echo hrw_price( $cashback->get_credit_amount() ) ; ?></td>
                        <td><?php echo hrw_price( $cashback->get_debit_amount() ) ; ?></td>
                        <td><?php echo $cashback->get_formatted_date() ; ?></td>
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
                    <td colspan="<?php echo esc_attr( count( $cashback_columns ) ) ; ?>" class="footable-visible">
                        <?php do_action( 'hrw_frontend_pagination' , $pagination ) ; ?>
                    </td>
                </tr>
            </tfoot>
        <?php endif ; ?>
    </table>
</div>
<?php
