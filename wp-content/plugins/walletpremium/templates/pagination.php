<?php
/**
 * This template displays Pagination
 * 
 * This template can be overridden by copying it to yourtheme/wallet/pagination.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="pagination pagination-centered">
    <span class="hrw_pagination">
        <a href="<?php echo esc_url( HRW_Dashboard::prepare_menu_url( false , array( 'page_no' => 1 ) ) ) ; ?>"><i class="fa fa-angle-double-left" ></i></a>
    </span>

    <span class="hrw_pagination">
        <a href="<?php echo esc_url( HRW_Dashboard::prepare_menu_url( false , array( 'page_no' => 1 ) ) ) ; ?>"><i class="fa fa-angle-left" ></i></a>
    </span>

    <?php
    for ( $i = 1 ; $i <= $page_count ; $i ++ ) {
        $display = false ;
        $classes = array( 'hrw_pagination' ) ;
        if ( $current_page <= 5 && $i <= 5 ) {
            $page_no = $i ;
            $display = true ;
        } else if ( $current_page > 5 ) {

            $overall_count = $current_page - 5 + $i ;

            if ( $overall_count <= $current_page ) {
                $page_no = $overall_count ;
                $display = true ;
            }
        }

        if ( $current_page == $i ) {
            $classes[] = 'hrw_current' ;
        }

        if ( $display ) {
            ?>
            <span class="<?php echo esc_attr( implode( ' ' , $classes ) ) ; ?>">
                <a href="<?php echo esc_url( HRW_Dashboard::prepare_menu_url( false , array( 'page_no' => $page_no ) ) ) ; ?>"><?php echo esc_html( $page_no ) ; ?></a>
            </span>
            <?php
        }
    }
    ?>
    <span class="hrw_pagination">
        <a href="<?php echo esc_url( HRW_Dashboard::prepare_menu_url( false , array( 'page_no' => $next_page_count ) ) ) ; ?>"><i class="fa fa-angle-right" ></i></a>
    </span>

    <span class="hrw_pagination">
        <a href="<?php echo esc_url( HRW_Dashboard::prepare_menu_url( false , array( 'page_no' => $page_count ) ) ) ; ?>"><i class="fa fa-angle-double-right" ></i></a>
    </span>

</div>
<?php
