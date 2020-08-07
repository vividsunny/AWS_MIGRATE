<?php
/*
 * This template displays Rules Configuration for Cashback.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="hrw_cashback_rule">
    <div class="hrw_cashback_rule_title">
        <h2><i class="fa fa-chevron-circle-down hrw_toggle_rule"> </i> <?php echo esc_attr( $cashback->get_name() ) ; ?></h2>
        <span class="hrw_delete_cashback_rule" data-postid="<?php echo esc_attr( $postid ) ; ?>"><i class="fa fa-trash"></i></span>
    </div>
    <div class="hrw_cashback_rule_content_wrapper">
        <p>
            <label><?php esc_html_e( 'Rule Name' , HRW_LOCALE ) ; ?></label>
            <input type="text" name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][rule_name]" id="hrw_rule_name" value="<?php echo esc_attr( $cashback->get_name() ) ; ?>"/>
        </p>
        <p>
            <label><?php esc_html_e( 'User Filter' , HRW_LOCALE ) ; ?></label>
            <select class="hrw_user_filter_type" name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][user_filter_type]">
                <option value="1" <?php selected( $cashback->get_user_filter_type() , 1 , true ) ; ?>><?php esc_html_e( 'All User(s)' , HRW_LOCALE ) ; ?></option>
                <option value="2" <?php selected( $cashback->get_user_filter_type() , 2 , true ) ; ?>><?php esc_html_e( 'Include User(s)' , HRW_LOCALE ) ; ?></option>
                <option value="3" <?php selected( $cashback->get_user_filter_type() , 3 , true ) ; ?>><?php esc_html_e( 'Exclude User(s)' , HRW_LOCALE ) ; ?></option>
                <option value="4" <?php selected( $cashback->get_user_filter_type() , 4 , true ) ; ?>><?php esc_html_e( 'Include User Role(s)' , HRW_LOCALE ) ; ?></option>
                <option value="5" <?php selected( $cashback->get_user_filter_type() , 5 , true ) ; ?>><?php esc_html_e( 'Exclude User Role(s)' , HRW_LOCALE ) ; ?></option>
            </select>
        </p>
        <p class="hrw_inc_user_selection">
            <label><?php esc_html_e( 'Include User' , HRW_LOCALE ) ; ?></label>
            <?php
            $inc_user_args = array(
                'name'        => 'hrw_cashback_rules[' . $postid . '][included_user]' ,
                'list_type'   => 'customers' ,
                'action'      => 'hrw_customers_search' ,
                'placeholder' => esc_html__( 'Search a User' , HRW_LOCALE ) ,
                'allow_clear' => false ,
                'options'     => $cashback->get_included_user() ,
                    ) ;
            hrw_select2_html( $inc_user_args ) ;
            ?>
        </p>
        <p class="hrw_exc_user_selection">
            <label><?php esc_html_e( 'Exclude User' , HRW_LOCALE ) ; ?></label>
            <?php
            $exc_user_args = array(
                'name'        => 'hrw_cashback_rules[' . $postid . '][excluded_user]' ,
                'list_type'   => 'customers' ,
                'action'      => 'hrw_customers_search' ,
                'placeholder' => esc_html__( 'Search a User' , HRW_LOCALE ) ,
                'allow_clear' => false ,
                'options'     => $cashback->get_excluded_user() ,
                    ) ;
            hrw_select2_html( $exc_user_args ) ;
            ?>
        </p>
        <p class="hrw_inc_user_role">
            <label><?php esc_html_e( 'Include User Role(s)' , HRW_LOCALE ) ; ?></label>
            <select class="hrw_select2" multiple="multiple" name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][included_user_role][]">
                <?php foreach ( hrw_get_user_roles() as $slug => $role ) { ?>
                    <option value="<?php echo esc_attr( $slug ) ; ?>" <?php echo in_array( $slug , $cashback->get_included_user_roles() ) ? 'selected="selected"' : '' ; ?>><?php echo esc_html( $role ) ; ?></option>
                <?php } ?>
            </select>
        </p>
        <p class="hrw_exc_user_role">
            <label><?php esc_html_e( 'Exclude User Role(s)' , HRW_LOCALE ) ; ?></label>
            <select class="hrw_select2" multiple="multiple" name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][excluded_user_role][]">
                <?php foreach ( hrw_get_user_roles() as $slug => $role ) { ?>
                    <option value="<?php echo esc_attr( $slug ) ; ?>" <?php echo in_array( $slug , $cashback->get_excluded_user_roles() ) ? 'selected="selected"' : '' ; ?>><?php echo esc_html( $role ) ; ?></option>
                <?php } ?>
            </select>
        </p>
        <p class="hrw_hide_for_topup">
            <label><?php esc_html_e( 'Product Filter' , HRW_LOCALE ) ; ?></label>
            <select class="hrw_product_filter_type" name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][product_filter_type]">
                <option value="1" <?php selected( $cashback->get_product_filter_type() , 1 , true ) ; ?>><?php esc_html_e( 'All Product(s)' , HRW_LOCALE ) ; ?></option>
                <option value="2" <?php selected( $cashback->get_product_filter_type() , 2 , true ) ; ?>><?php esc_html_e( 'Include Product(s)' , HRW_LOCALE ) ; ?></option>
                <option value="3" <?php selected( $cashback->get_product_filter_type() , 3 , true ) ; ?>><?php esc_html_e( 'Exclude Product(s)' , HRW_LOCALE ) ; ?></option>
                <option value="4" <?php selected( $cashback->get_product_filter_type() , 4 , true ) ; ?>><?php esc_html_e( 'All Categories(s)' , HRW_LOCALE ) ; ?></option>
                <option value="5" <?php selected( $cashback->get_product_filter_type() , 5 , true ) ; ?>><?php esc_html_e( 'Include Categories' , HRW_LOCALE ) ; ?></option>
                <option value="6" <?php selected( $cashback->get_product_filter_type() , 6 , true ) ; ?>><?php esc_html_e( 'Exclude Categories' , HRW_LOCALE ) ; ?></option>
                <option value="7" <?php selected( $cashback->get_product_filter_type() , 7 , true ) ; ?>><?php esc_html_e( 'All Tag(s)' , HRW_LOCALE ) ; ?></option>
                <option value="8" <?php selected( $cashback->get_product_filter_type() , 8 , true ) ; ?>><?php esc_html_e( 'Include Tag(s)' , HRW_LOCALE ) ; ?></option>
                <option value="9" <?php selected( $cashback->get_product_filter_type() , 9 , true ) ; ?>><?php esc_html_e( 'Exclude Tag(s)' , HRW_LOCALE ) ; ?></option>
            </select>
        </p>
        <p class="hrw_inc_product_selection hrw_hide_for_topup">
            <label><?php esc_html_e( 'Include Product(s)' , HRW_LOCALE ) ; ?></label>
            <?php
            $inc_product_args = array(
                'name'        => 'hrw_cashback_rules[' . $postid . '][included_product]' ,
                'list_type'   => 'products' ,
                'action'      => 'hrw_product_search' ,
                'placeholder' => esc_html__( 'Search a Product' , HRW_LOCALE ) ,
                'allow_clear' => false ,
                'options'     => $cashback->get_included_products() ,
                    ) ;
            hrw_select2_html( $inc_product_args ) ;
            ?>
        </p>
        <p class="hrw_exc_product_selection hrw_hide_for_topup">
            <label><?php esc_html_e( 'Exclude Product(s)' , HRW_LOCALE ) ; ?></label>
            <?php
            $exc_product_args = array(
                'name'        => 'hrw_cashback_rules[' . $postid . '][excluded_product]' ,
                'list_type'   => 'products' ,
                'action'      => 'hrw_product_search' ,
                'placeholder' => esc_html__( 'Search a Product' , HRW_LOCALE ) ,
                'allow_clear' => false ,
                'options'     => $cashback->get_excluded_products() ,
                    ) ;
            hrw_select2_html( $exc_product_args ) ;
            ?>
        </p>
        <p class="hrw_inc_cat_selection hrw_hide_for_topup">
            <label><?php esc_html_e( 'Include Categories' , HRW_LOCALE ) ; ?></label>
            <select class="hrw_select2" multiple="multiple" name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][included_category][]">
                <?php foreach ( hrw_get_wc_categories() as $termid => $cat_name ) { ?>
                    <option value="<?php echo esc_attr( $termid ) ; ?>" <?php echo in_array( $termid , $cashback->get_included_category() ) ? 'selected="selected"' : '' ; ?>><?php echo esc_html( $cat_name ) ; ?></option>
                <?php } ?>
            </select>
        </p>
        <p class="hrw_exc_cat_selection hrw_hide_for_topup">
            <label><?php esc_html_e( 'Exclude Categories' , HRW_LOCALE ) ; ?></label>
            <select class="hrw_select2" multiple="multiple" name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][excluded_category][]">
                <?php foreach ( hrw_get_wc_categories() as $termid => $cat_name ) { ?>
                    <option value="<?php echo esc_attr( $termid ) ; ?>" <?php echo in_array( $termid , $cashback->get_excluded_category() ) ? 'selected="selected"' : '' ; ?>><?php echo esc_html( $cat_name ) ; ?></option>
                <?php } ?>
            </select>
        </p>
        <p class="hrw_inc_tag_selection hrw_hide_for_topup">
            <label><?php esc_html_e( 'Include Tag(s)' , HRW_LOCALE ) ; ?></label>
            <select class="hrw_select2" multiple="multiple" name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][included_tag][]">
                <?php foreach ( hrw_get_wc_tags() as $termid => $tag_name ) { ?>
                    <option value="<?php echo esc_attr( $termid ) ; ?>" <?php echo in_array( $termid , $cashback->get_included_tag() ) ? 'selected="selected"' : '' ; ?>><?php echo esc_html( $tag_name ) ; ?></option>
                <?php } ?>
            </select>
        </p>
        <p class="hrw_exc_tag_selection hrw_hide_for_topup">
            <label><?php esc_html_e( 'Exclude Tag(s)' , HRW_LOCALE ) ; ?></label>
            <select class="hrw_select2" multiple="multiple" name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][excluded_tag][]">
                <?php foreach ( hrw_get_wc_tags() as $termid => $tag_name ) { ?>
                    <option value="<?php echo esc_attr( $termid ) ; ?>" <?php echo in_array( $termid , $cashback->get_excluded_tag() ) ? 'selected="selected"' : '' ; ?>><?php echo esc_html( $tag_name ) ; ?></option>
                <?php } ?>
            </select>
        </p>
        <p>
            <label><?php esc_html_e( 'Purchase History' , HRW_LOCALE ) ; ?></label>
            <select class="hrw_purchase_history_type" name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][purchase_history]">
                <option value="1" <?php selected( $cashback->get_purchase_history_type() , 1 , true ) ; ?>><?php esc_html_e( 'Minimum Number of Successful Orders' , HRW_LOCALE ) ; ?></option>
                <option value="2" <?php selected( $cashback->get_purchase_history_type() , 2 , true ) ; ?>><?php esc_html_e( 'Minimum Amount Spent on site' , HRW_LOCALE ) ; ?></option>
            </select>
        </p>
        <p class="hrw_no_of_order">
            <label><?php esc_html_e( 'Number of Order(s)' , HRW_LOCALE ) ; ?></label>
            <input type="number" name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][no_of_order]" value="<?php echo esc_attr( $cashback->get_no_of_order() ) ; ?>"/>
        </p>
        <p class="hrw_total_amount">
            <label><?php esc_html_e( 'Total Amount' , HRW_LOCALE ) ; ?></label>
            <input type="number" name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][total_amount]" value="<?php echo esc_attr( $cashback->get_total_amount() ) ; ?>"/>
        </p>
        <p class="hrw_date_picker_row">
            <label><?php esc_html_e( 'From' , HRW_LOCALE ) ; ?></label>
            <?php
            $args = array(
                'name'        => 'hrw_cashback_rules[' . $postid . '][from_date]' ,
                'value'       => $cashback->get_from_date() ,
                'wp_zone'     => false ,
                'placeholder' => HRW_Date_Time::get_wp_date_format() ,
                    ) ;
            hrw_get_datepicker_html( $args ) ;
            ?>
            <label class="hrw_to_date"><?php esc_html_e( 'To' , HRW_LOCALE ) ; ?></label>
            <?php
            $args = array(
                'name'        => 'hrw_cashback_rules[' . $postid . '][to_date]' ,
                'value'       => $cashback->get_to_date() ,
                'wp_zone'     => false ,
                'placeholder' => HRW_Date_Time::get_wp_date_format() ,
                    ) ;
            hrw_get_datepicker_html( $args ) ;
            ?>
        </p>
        <p>
            <label><?php esc_html_e( 'Cashback Valid on following Days' , HRW_LOCALE ) ; ?></label>
            <?php
            foreach ( hrw_get_week_days() as $key => $value ) {
                $checked = in_array( $key , $cashback->get_valid_days() ) ? 'checked="checked"' : '' ;
                ?>
                <input type="checkbox" value="<?php echo esc_attr( $key ) ; ?>" <?php echo esc_html( $checked ) ; ?> name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][valid_days][]" /><?php echo esc_html( $value ) ; ?>
            <?php } ?>
        </p>
        <p class="hrw_local_rule_priority">
            <label><?php esc_html_e( 'Rule Priority' , HRW_LOCALE ) ; ?></label>
            <select name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][rule_priority]">
                <option value="1" <?php selected( $cashback->get_rule_priority() , 1 , true ) ; ?>><?php esc_html_e( 'First Matched Rule' , HRW_LOCALE ) ; ?></option>
                <option value="2" <?php selected( $cashback->get_rule_priority() , 2 , true ) ; ?>><?php esc_html_e( 'Last Matched Rule' , HRW_LOCALE ) ; ?></option>
                <option value="3" <?php selected( $cashback->get_rule_priority() , 3 , true ) ; ?>><?php esc_html_e( 'Minimum Cashback Value' , HRW_LOCALE ) ; ?></option>
                <option value="4" <?php selected( $cashback->get_rule_priority() , 4 , true ) ; ?>><?php esc_html_e( 'Maximum Cashback Value' , HRW_LOCALE ) ; ?></option>
            </select>
        </p>
        <p>
            <label><?php esc_html_e( 'Cashback for' , HRW_LOCALE ) ; ?></label>
            <select class="hrw_cashback_rule_type" name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][rule_type]">
                <option value="1" <?php selected( $cashback->get_rule_type() , 1 , true ) ; ?>><?php esc_html_e( 'Order Total' , HRW_LOCALE ) ; ?></option>
                <option value="2" <?php selected( $cashback->get_rule_type() , 2 , true ) ; ?>><?php esc_html_e( 'Wallet Top-up' , HRW_LOCALE ) ; ?></option>
            </select>
        </p>
        <p class="hrw_cashback_order_total_type">
            <label><?php esc_html_e( 'Cashback based on' , HRW_LOCALE ) ; ?></label>
            <select name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][order_total_type]">
                <option value="1" <?php selected( $cashback->get_order_total_type() , 1 , true ) ; ?>><?php esc_html_e( 'Order Subtotal' , HRW_LOCALE ) ; ?></option>
                <option value="2" <?php selected( $cashback->get_order_total_type() , 2 , true ) ; ?>><?php esc_html_e( 'Order Total' , HRW_LOCALE ) ; ?></option>
            </select>
        </p>
        <table class="hrw_cashback_order_table">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Min Order Total' , HRW_LOCALE ) ; ?></th>
                    <th><?php esc_html_e( 'Max Order Total' , HRW_LOCALE ) ; ?></th>
                    <th><?php esc_html_e( 'Cashback Type' , HRW_LOCALE ) ; ?></th>
                    <th><?php esc_html_e( 'Value' , HRW_LOCALE ) ; ?></th>
                    <th><?php esc_html_e( 'Remove' , HRW_LOCALE ) ; ?></th>
                </tr>
            </thead>
            <tbody class="hrw_cashback_order_table_wrapper">
                <?php
                if ( hrw_check_is_array( $cashback->get_order_rule() ) ) {
                    foreach ( $cashback->get_order_rule() as $uniqueid => $values ) {
                        ?>
                        <tr>
                            <td>
                                <input type="number" name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][order_rule][<?php echo esc_attr( $uniqueid ) ; ?>][min]" value="<?php echo esc_attr( $values[ 'min' ] ) ; ?>"/>
                            </td>
                            <td>
                                <input type="number" name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][order_rule][<?php echo esc_attr( $uniqueid ) ; ?>][max]" value="<?php echo esc_attr( $values[ 'max' ] ) ; ?>"/>
                            </td>
                            <td>
                                <select name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][order_rule][<?php echo esc_attr( $uniqueid ) ; ?>][type]" >
                                    <option value="1" <?php selected( $values[ 'type' ] , 1 , true ) ; ?>><?php esc_html_e( 'Fixed' , HRW_LOCALE ) ; ?></option>
                                    <option value="2" <?php selected( $values[ 'type' ] , 2 , true ) ; ?>><?php esc_html_e( 'Percentage' , HRW_LOCALE ) ; ?></option>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][order_rule][<?php echo esc_attr( $uniqueid ) ; ?>][value]" value="<?php echo esc_attr( $values[ 'value' ] ) ; ?>"/>
                            </td>
                            <td>
                                <button class="hrw_remove_cashback_rule"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" >
                        <button class="hrw_add_cashback_order_rule" data-postid="<?php echo esc_attr( $postid ) ; ?>" > <i class="fa fa-plus"></i> <?php esc_attr_e( 'Add Rule' , HRW_LOCALE ) ; ?></button>
                    </td>
                </tr>
            </tfoot>
        </table>
        <table class="hrw_cashback_wallet_table">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Min Wallet Amount' , HRW_LOCALE ) ; ?></th>
                    <th><?php esc_html_e( 'Max Wallet Amount' , HRW_LOCALE ) ; ?></th>
                    <th><?php esc_html_e( 'Cashback Type' , HRW_LOCALE ) ; ?></th>
                    <th><?php esc_html_e( 'Value' , HRW_LOCALE ) ; ?></th>
                    <th><?php esc_html_e( 'Remove' , HRW_LOCALE ) ; ?></th>
                </tr>
            </thead>
            <tbody class="hrw_cashback_wallet_table_wrapper">
                <?php
                if ( hrw_check_is_array( $cashback->get_wallet_rule() ) ) {
                    foreach ( $cashback->get_wallet_rule() as $uniqueid => $values ) {
                        ?>
                        <tr>
                            <td>
                                <input type="number" name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][wallet_rule][<?php echo esc_attr( $uniqueid ) ; ?>][min]" value="<?php echo esc_attr( $values[ 'min' ] ) ; ?>"/>
                            </td>
                            <td>
                                <input type="number" name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][wallet_rule][<?php echo esc_attr( $uniqueid ) ; ?>][max]" value="<?php echo esc_attr( $values[ 'max' ] ) ; ?>"/>
                            </td>
                            <td>
                                <select name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][wallet_rule][<?php echo esc_attr( $uniqueid ) ; ?>][type]" >
                                    <option value="1" <?php selected( $values[ 'type' ] , 1 , true ) ; ?>><?php esc_html_e( 'Fixed' , HRW_LOCALE ) ; ?></option>
                                    <option value="2" <?php selected( $values[ 'type' ] , 2 , true ) ; ?>><?php esc_html_e( 'Percentage' , HRW_LOCALE ) ; ?></option>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][wallet_rule][<?php echo esc_attr( $uniqueid ) ; ?>][value]" value="<?php echo esc_attr( $values[ 'value' ] ) ; ?>"/>
                            </td>
                            <td>
                                <button class="hrw_remove_cashback_rule"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">
                        <button class="hrw_add_cashback_wallet_rule" data-postid="<?php echo esc_attr( $postid ) ; ?>" ><i class="fa fa-plus"></i> <?php esc_attr_e( 'Add Rule' , HRW_LOCALE ) ; ?></button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?php
