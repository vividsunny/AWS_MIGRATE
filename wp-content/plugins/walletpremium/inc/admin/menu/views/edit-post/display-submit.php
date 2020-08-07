<?php
/* Wallet Submit Display Settings */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<form method="post">
    <div class="hrw_wallet_details_status_wrapper">
        <div class="hrw_wallet_status_wrapper">
            <div>
                <label><?php esc_html_e( 'Current Status' , HRW_LOCALE ) ; ?></label>
                <span><?php echo hrw_display_status( $wallet->get_status() ) ; ?></span>
                <a class="hrw_display_edit_status"><span aria-hidden="true"><?php esc_html_e( 'Edit' , HRW_LOCALE ) ; ?></span></a>
                <a class="hrw_cancel_edit_status hrw_wallet_status_row"><span aria-hidden="true"><?php esc_html_e( 'Cancel' , HRW_LOCALE ) ; ?></span></a>
            </div>

            <?php
            if ( $wallet->get_schedule_block_status() == 'yes' ) {
                ?>
                <div>
                    <label>
                        <?php echo sprintf( 'Schedule Lock Assigned from %1s to %2s' , esc_html( $wallet->get_formatted_block_from_date() ) , esc_html( $wallet->get_formatted_block_to_date() ) ) ; ?>
                    </label>
                    <p><input type="checkbox" name="hrw_clear_schedule"> <?php esc_html_e( 'Clear Schedule' , HRW_LOCALE ) ; ?> </p>
                </div>

            <?php } ?>

            <div class="hrw_wallet_status_row hrw_wallet_status_content">
                <label><?php esc_html_e( 'Select the Status' , HRW_LOCALE ) ; ?></label>
                <?php
                $status = array( 'hrw_active'  => esc_html__( 'Active' , HRW_LOCALE ) ,
                    'hrw_blocked' => esc_html__( 'Lock' , HRW_LOCALE ) ,
                    'hrw_expired' => esc_html__( 'Expired' , HRW_LOCALE ) ) ;
                ?>
                <select id="hrw_wallet_status" name="hrw_wallet_status">
                    <?php
                    foreach ( $status as $status_key => $status_name ) {
                        ?>
                        <option value="<?php echo esc_attr( $status_key ) ; ?>" <?php selected( $status_key , $wallet->get_status() ) ; ?>  ><?php echo esc_html( $status_name ) ; ?></option>
                    <?php } ; ?>
                </select> 
            </div>

            <div class="hrw_wallet_status_row hrw_wallet_lock_status_row hrw_wallet_lock_type_row">
                <p> <label><?php esc_html_e( 'Lock Type' , HRW_LOCALE ) ; ?></label>
                    <?php
                    $lock_types = array( '1' => esc_html__( 'Immediate Lock' , HRW_LOCALE ) ,
                        '2' => esc_html__( 'Schedule Lock' , HRW_LOCALE ) ) ;
                    ?>
                </p> 
                <select  id="hrw_wallet_lock_type" name="hrw_schedule_block_type"> 
                    <?php
                    foreach ( $lock_types as $lock_id => $lock_label ) {
                        ?>
                        <option value="<?php echo esc_attr( $lock_id ) ; ?>" <?php selected( $lock_id , $wallet->get_schedule_block_type() ) ; ?>  ><?php echo esc_html( $lock_label ) ; ?></option>
                    <?php } ; ?>
                </select>
            </div>

            <div class="hrw_wallet_status_row hrw_wallet_lock_status_row hrw_schedule_lock">
                <p>
                    <label><?php esc_html_e( 'Schedule Lock From' , HRW_LOCALE ) ; ?></label>
                    <span> <?php
                        $args = array(
                            'name'        => 'hrw_schedule_block_from_date' ,
                            'value'       => $wallet->get_schedule_block_from_date() ,
                            'wp_zone'     => false ,
                            'placeholder' => HRW_Date_Time::get_wp_date_format() ,
                                ) ;
                        hrw_get_datepicker_html( $args ) ;
                        ?>
                    </span>  
                </p>
                <p>
                    <label><?php esc_html_e( 'Schedule Lock To' , HRW_LOCALE ) ; ?></label>
                    <span> <?php
                        $args = array(
                            'name'        => 'hrw_schedule_block_to_date' ,
                            'value'       => $wallet->get_schedule_block_to_date() ,
                            'wp_zone'     => false ,
                            'placeholder' => HRW_Date_Time::get_wp_date_format() ,
                                ) ;
                        hrw_get_datepicker_html( $args ) ;
                        ?>
                    </span>
                </p>
            </div>

            <div class="hrw_wallet_status_row hrw_wallet_lock_status_row hrw_schedule_lock">
                <p><label><?php esc_html_e( 'Lock Reason' , HRW_LOCALE ) ; ?></label> </p>
                <span><textarea name="hrw_schedule_block_reason"><?php echo esc_textarea( $wallet->get_schedule_block_reason() ) ?></textarea></span>
            </div> 
        </div>
        <?php wp_nonce_field( 'hrw_save_wallet_data' , 'hrw_save_nonce' ) ; ?>
        <input  type = "submit" class = "button button-primary button-large" id = "hrw_wallet_update" value = "<?php echo esc_attr( 'Update' ) ; ?>" />
    </div>
</form>
<?php
