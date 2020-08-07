<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

return array(

    'appearance' => apply_filters( 'yith_wcact_appearance_options', array(

            'appearance_options_start'    => array(
                'type' => 'sectionstart',
                'id'   => 'yith_wcact_appearance_options_start'
            ),

            'appearance_options_title'    => array(
                'title' => esc_html_x( 'General appearance', 'Panel: General appearance', 'yith-auctions-for-woocommerce' ),
                'type'  => 'title',
                'desc'  => '',
                'id'    => 'yith_wcact_appearance_options_title'
            ),

            'appearance_upload_badge' => array(
                'name'   => esc_html_x( 'Badge image', 'Admin option: Upload or Select a badge image', 'yith-auctions-for-woocommerce' ),
                'type'    => 'yith_auctions_upload',
                'desc'    => esc_html_x( 'Select an image to show in auctions products', 'Admin option: Select an image to show in auctions products', 'yith-auctions-for-woocommerce' ),
                'id'      => 'yith_wcact_appearance_button',
            ),

            'appearance_options_end'      => array(
                'type' => 'sectionend',
                'id'   => 'yith_wcact_appearance_options_end',
            ),
        )
    )
);