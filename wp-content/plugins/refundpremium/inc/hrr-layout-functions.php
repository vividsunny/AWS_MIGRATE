<?php
/*
 * Layout functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! function_exists( 'hrr_select2_html' ) ) {

	/**
	 * Return or display Select2 HTML
	 * 
	 * @return string
	 */
	function hrr_select2_html( $args, $echo = true ) {
		$args = wp_parse_args( $args , array(
			'class'             => '' ,
			'id'                => '' ,
			'name'              => '' ,
			'list_type'         => '' ,
			'action'            => '' ,
			'placeholder'       => '' ,
			'custom_attributes' => array() ,
			'multiple'          => true ,
			'allow_clear'       => true ,
			'selected'          => true ,
			'options'           => array() ,
				)
				) ;

		$multiple = $args[ 'multiple' ] ? 'multiple="multiple"' : '' ;
		$name     = esc_attr( '' !== $args[ 'name' ] ? $args[ 'name' ] : $args[ 'id' ] ) . '[]' ;
		$options  = array_filter( hrr_check_is_array( $args[ 'options' ] ) ? $args[ 'options' ] : array() ) ;

		// Custom attribute handling.
		$custom_attributes = hrr_format_custom_attributes( $args ) ;

		ob_start() ;
		?><select <?php echo esc_attr( $multiple ) ; ?> 
			name="<?php echo esc_attr( $name ) ; ?>" 
			id="<?php echo esc_attr( $args[ 'id' ] ) ; ?>" 
			data-action="<?php echo esc_attr( $args[ 'action' ] ) ; ?>" 
			class="hrr_select2_search <?php echo esc_attr( $args[ 'class' ] ) ; ?>" 
			data-placeholder="<?php echo esc_attr( $args[ 'placeholder' ] ) ; ?>" 
			<?php echo implode( ' ' , $custom_attributes ) ; // WPCS: XSS ok. ?>
			<?php echo $args[ 'allow_clear' ] ? 'data-allow_clear="true"' : ''; // WPCS: XSS ok. ?> >
				<?php
				if ( is_array( $args[ 'options' ] ) ) {
					foreach ( $args[ 'options' ] as $option_id ) {
						$option_value = '' ;
						switch ( $args[ 'list_type' ] ) {
							case 'post':
								$option_value = get_the_title( $option_id ) ;
								break ;
							case 'products':
								$option_value = get_the_title( $option_id ) . ' (#' . absint( $option_id ) . ')' ;
								break ;
							case 'customers':
																$user = get_user_by( 'id' , $option_id ) ;
								if ( $user ) {
									$option_value = $user->display_name . '(#' . absint( $user->ID ) . ' &ndash; ' . $user->user_email . ')' ;
								}
								break ;
						}

						if ( $option_value ) {
							?>
						<option value="<?php echo esc_attr( $option_id ) ; ?>" <?php echo $args[ 'selected' ] ? 'selected="selected"' : ''; // WPCS: XSS ok. ?>><?php echo esc_html( $option_value ) ; ?></option>
							<?php
						}
					}
				}
				?>
			</select>
			<?php
			$html = ob_get_clean() ;

			if ( $echo ) {
				echo $html ;
			}

			return $html ;
	}

}

if ( ! function_exists( 'hrr_format_custom_attributes' ) ) {

	/**
	 * Format Custom Attributes
	 * 
	 * @return array
	 */
	function hrr_format_custom_attributes( $value ) {
		$custom_attributes = array() ;

		if ( ! empty( $value[ 'custom_attributes' ] ) && is_array( $value[ 'custom_attributes' ] ) ) {
			foreach ( $value[ 'custom_attributes' ] as $attribute => $attribute_value ) {
				$custom_attributes[] = esc_attr( $attribute ) . '=' . esc_attr( $attribute_value ) . '' ;
			}
		}

		return $custom_attributes ;
	}

}

if ( ! function_exists( 'hrr_get_datepicker_html' ) ) {

	/**
	 * Return or display Datepicker HTML
	 * 
	 * @return string
	 */
	function hrr_get_datepicker_html( $args, $echo = true ) {
		$args = wp_parse_args( $args , array(
			'class'             => '' ,
			'id'                => '' ,
			'name'              => '' ,
			'placeholder'       => '' ,
			'custom_attributes' => array() ,
			'value'             => '' ,
			'wp_zone'           => true ,
				) ) ;

		$name = ( '' !== $args[ 'name' ] ) ? $args[ 'name' ] : $args[ 'id' ] ;

		// Custom attribute handling.
		$custom_attributes = hrr_format_custom_attributes( $args ) ;
		$value             = ! empty( $args[ 'value' ] ) ? HRR_Date_Time::get_date_object_format_datetime( $args[ 'value' ] , 'date' , $args[ 'wp_zone' ] ) : '' ;
		ob_start() ;
		?>
		<input type = "text" 
			   id="<?php echo esc_attr( $args[ 'id' ] ) ; ?>"
			   value = "<?php echo esc_attr( $value ) ; ?>"
			   class="hrr_datepicker <?php echo esc_attr( $args[ 'class' ] ) ; ?>" 
			   placeholder="<?php echo esc_attr( $args[ 'placeholder' ] ) ; ?>" 
			   <?php echo implode( ' ' , $custom_attributes ) ; // WPCS: XSS ok. ?>
			   />

		<input type = "hidden" 
			   class="hrr_alter_datepicker_value" 
			   name="<?php echo esc_attr( $name ) ; ?>"
			   value = "<?php echo esc_attr( $args[ 'value' ] ) ; ?>"
			   /> 
		<?php
		$html              = ob_get_clean() ;

		if ( $echo ) {
			echo $html ;
		}

		return $html ;
	}

}

if ( ! function_exists( 'hrr_display_status' ) ) {

	/**
	 * Display formatted status
	 * 
	 * @return string
	 */
	function hrr_display_status( $status, $html = true ) {

		$status_object = get_post_status_object( $status ) ;

		if ( ! isset( $status_object ) ) {
			return '' ;
		}

		return $html ? '<mark class="hrr-status-label ' . esc_attr( $status ) . '-status"><span >' . esc_html( $status_object->label ) . '</span></mark>' : esc_html( $status_object->label ) ;
	}

}

if ( ! function_exists( 'hrr_get_template' ) ) {

	/**
	 *  Get other templates from themes
	 */
	function hrr_get_template( $template_name, $args = array() ) {

		wc_get_template( $template_name , $args , 'hrr/' , HRR()->templates() ) ;
	}

}

if ( ! function_exists( 'hrr_get_template_html' ) ) {

	/**
	 *  Like hrr_get_template, but returns the HTML instead of outputting.
	 * 
	 *  @return string
	 */
	function hrr_get_template_html( $template_name, $args = array() ) {

		ob_start() ;
		hrr_get_template( $template_name , $args ) ;
		return ob_get_clean() ;
	}

}
