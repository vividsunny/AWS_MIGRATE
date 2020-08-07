<?php
/**
 *  Admin HTML Settings 
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
?>
<div class = "wrap <?php echo esc_attr( self::$plugin_slug ) ; ?>-wrapper-cover woocommerce">
	<h2></h2>
	<div class="<?php echo esc_attr( self::$plugin_slug ) ; ?>-header">
		<div class="<?php echo esc_attr( self::$plugin_slug ) ; ?>-header-title">
			<h2><?php esc_html_e( 'Refund Premium' , 'refund' ) ; ?></h2>
		</div>
		<div class="<?php echo esc_attr( self::$plugin_slug ) ; ?>-header-logo">
			<img src="<?php echo esc_url( HRR_PLUGIN_URL . '/assets/images/header-logo.png' ) ; ?>">
		</div>
	</div>
	<form method = "post" enctype = "multipart/form-data">
		<div class = "<?php echo esc_attr( self::$plugin_slug ) ; ?>-wrapper">
			<ul class = "nav-tab-wrapper woo-nav-tab-wrapper <?php echo esc_attr( self::$plugin_slug ) ; ?>-tab-ul">
				<?php foreach ( $tabs as $name => $label ) : ?>
					<li class="<?php echo esc_attr( self::$plugin_slug ) ; ?>-tab-li <?php echo esc_attr( $name ) . '-li' ; ?>">
						<a href="<?php echo esc_url( hrr_get_settings_page_url( array ( 'tab' => $name ) ) ) ; ?>" class="nav-tab <?php echo esc_html( self::$plugin_slug ) ; ?>-tab-a <?php echo esc_attr( $name ) . '-a ' . ( $current_tab == $name ? 'nav-tab-active' : '' ) ; ?>">
							<i class="fa <?php echo esc_attr( $label[ 'code' ] ) ; ?>"></i>
							<span><?php echo esc_html( $label[ 'label' ] ) ; ?></span>
						</a>
					<?php endforeach ; ?>
			</ul>
			<div class="<?php echo esc_attr( self::$plugin_slug ) ; ?>-tab-content hrr-<?php echo esc_attr( $current_tab ) ; ?>-tab-content-wrapper">
				<?php
				//Display Sections.
				do_action( sanitize_key( self::$plugin_slug . '_sections_' . $current_tab ) ) ;
				?>
				<div class="<?php echo esc_attr( self::$plugin_slug ) ; ?>-tab-inner-content hrr-<?php echo esc_attr( $current_tab ) ; ?>-tab-inner-content">
					
					<?php do_action( sanitize_key( self::$plugin_slug . '_before_tab_sections' ) ) ; ?>
					
					<h3><?php esc_html_e( 'Refund Premium - ' , 'refund' ) ; ?> <span><?php echo esc_attr( $tabs[ $current_tab ][ 'label' ] ) ; ?> </span></h3>
					<?php

					//Display Error or Warning Messages.
					self::show_messages() ;

					//Display Tab Content.
					do_action( sanitize_key( self::$plugin_slug . '_settings_' . $current_tab ) ) ;

					//Display Reset and Save Button.
					do_action( sanitize_key( self::$plugin_slug . '_settings_buttons_' . $current_tab ) ) ;

					//Extra fields after setting button.
					do_action( sanitize_key( self::$plugin_slug . '_after_setting_buttons_' . $current_tab ) ) ;
					?>
				</div>
			</div>
		</div>
	</form>
	<?php
	do_action( sanitize_key( self::$plugin_slug . '_' . $current_tab . '_' . $current_section . '_setting_end' ) ) ;
	do_action( sanitize_key( self::$plugin_slug . '_settings_end' ) ) ;
	?>
</div>
<?php
