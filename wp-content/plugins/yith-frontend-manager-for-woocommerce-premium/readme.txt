== YITH Frontend Manager for WooCommerce Premium ===

Contributors: yithemes
Requires at least: 4.0
Tested up to: 5.4
Stable tag: 1.6.11
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Multi vendor e-commerce is a new idea of e-commerce platform that is becoming more and more popular in the web.

== Changelog ==

= 1.6.11 - Released on 27 May 2020 =

* New: Support for WooCommerce 4.1
* Update: plugin framework

= 1.6.10 - Released on 05 May 2020 =

* Fix: Media scripts load over all website
* Fix: Variation pagination doesn't work

= 1.6.9 - Released on 30 April 2020 =

* New: Support for WooCommerce 4.1
* Update: plugin framework
* Fix: Unable to use navigation in Stock Reports
* Fix: Wrong product URL in commissions table

= 1.6.8 - Released on 24 March 2020 =

* New: Ability to search orders
* Update: Plugin Framework
* Fix: Unable to activate license

= 1.6.7  - Released on 03 March 2020 =

* New: Support for WordPress 5.4
* New: Support for WooCommerce 4.0
* Update: plugin framework
* Fix: The Polygon style issue
* Fix: Vendor can't edit their profile with The Retailer theme
* Fix: Products table style in Twenty16 theme
* Fix: Unable to save the frontend manager main shortcode inside the shortcode Gutenberg block
* Dev: yith_wcfm_products_action_uri to filter action uri for products section
* Dev: yith_wcfm_live_chat_macro_action_uri to filter action uri for Live chat macro section

= 1.6.6  - Released on 27 January 2020 =

* New: Added WooCommerce order actions
* New: Manage product custom fields
* Update: Plugin framework
* Update: Spanish language
* Tweak: Code refactoring for Auctions module class
* Fix: Unable to search products of an order with Shop Manager capabilities
* Fix: Unable to save product items of an order with Shop Manager capabilities
* Fix: Unable to remove discount of an order with Shop Manager capabilities
* Fix: Unable to calculate line taxes of an order with Shop Manager capabilities
* Fix: Unable to add taxes to an order with Shop Manager capabilities
* Fix: Unable to recalculate the order total with Shop Manager capabilities
* Fix: Unable to add products to an order with Shop Manager capabilities
* Fix: Unable to add a FEE to an order  with Shop Manager capabilities
* Fix: Unable to add Coupon Discount to an order with Shop Manager capabilities
* Fix: Unable to save tax status and tax classes when editing a product
* Dev: Add hook yith_wcfm_module_files to filter module files

= 1.6.5 – Released on 23 December 2019 =

* New: Support for WooCommerce 3.9
* Update: Plugin framework
* Update: Italian language
* Fix: Layout issue for orders table on tablet and smartphone
* Fix: Issue with deprecated function update_woocommerce_term_meta

= 1.6.4 - Released on 05 December 2019 =

* Fix: Vendor can't access their panel

= 1.6.3 - Released on 04 December 2019 =

* New: Manage order custom fields
* Fix: Unable to save stock status
* Update: Plugin Framework

= 1.6.2 - Released on 07 November 2019 =

* Fix: Unable to flush rewrite rules in admin area

= 1.6.1 - Released on 07 November 2019 =

* Fix: Checkout doesn't work with WooCommerce 3.8
* Dev: yith_wcfm_general_editor_settings hook to add args to product editor
* Dev: yith_wcfm_post_excerpt_editor_settings hook to add args to product short description editor
* Dev: yith_wcfm_post_content_editor_settings hook to add args to product description editor

= 1.6.0 - Released on 04 November 2019 =

* New: Support for WooCommerce 3.8
* New: Support for WordPress 5.3
* Update: Plugin framework
* Fix: 404 not found error after switching theme
* Fix: 404 not found error after changing permalink structure
* Fix: Button style in product taxonomy pages

= 1.5.6 - Released on 08th October, 2019 =

* New: Integration with YITH WooCommerce Order Tracking Premium
* New: Search form for products section
* New: Filter order by status
* Update: Spanish language
* Fix: Show user information in Ship To column for guest users
* Fix: Replaced deprecated function get_woocommerce_term_meta() with get_term_meta()
* Fix: Replace the order ID with Order Number to fix issues with YITH WooCommerce Sequential Order Number
* Fix: Style issues with YITH Nielsen theme

= 1.5.5 - Released on 07th August, 2019 =

* New: Support for WooCommerce 3.7
* New: Add Unpaid commissions amount in Dashboard for vendors
* Tweak: Add "woocommerce" body class in frontend manager dashboard page
* Fix: Some Style bugs
* Fix: Pagination style
* Fix: Vendor can't add product on admin area
* Fix: Wrong style in frontend manager page login page
* Fix: Placeholder disappears if an user remove the product featured image
* Fix: Placeholder disappears if an user remove all products gallery images

= 1.5.4 - Released on 07th June, 2019 =

* Fix: Vendor can't add new products

= 1.5.3 - Released on 29th May, 2019 =

* Tweak: New dashboard section template
* Fix: Unable to save products in new order
* Fix: Remove import products button for vendors
* Fix: Wrong name for Commissions report section
* Fix: Fatal error for wrong function name "is numeric"
* Fix: No preview for product featured image
* Fix: AUTO-DRAFT product title when try to add a new product
* Fix: Prevent double pending review email for vendor
* Dev: yith_wcfm_get_main_page_url hook for filter main page url
* Dev: yith_wcfm_dashboard_info action to add more info box in dashboard

= 1.5.2 - Released on 29th April, 2019 =

* Fix: Undefined hook_suffix variable in reports section

= 1.5.1 - Released on 23rd April, 2019 =

* Fix: Unable to delete coupons with all roles in WooCommerce 3.6.x

= 1.5.0 - Released on 15th April, 2019 =

* New: Support for WooCommerce 3.6
* Update: Italian language
* Update: Spanish language
* Tweak: Code refactoring for products section
* Tweak: Code refactoring for coupons section
* Tweak: Add unique id for product taxonomies wrapper in add/edit product template
* Tweak: Replace update_post_meta with WooCommerce setters method
* Fix: Save issue with YITH WooCommerce Featured Audio and Video Content and WooCommerce 3.6
* Fix: Invoice button style
* Fix: Unable to disabled SMS Notification section
* Fix: No fired action for new product created
* Fix: Error on update billing and shippng information if vendor haven't access to this information
* Fix: Show empty taxonomy box for vendors
* Dev: yith_wcfm_skip_taxonomy hook to skip show taxonomy in add/edit product

= 1.4.14 - Released on 15th February, 2019 =

* New: Support for YITH Auctions for WooCommerce
* Fix: Actions url on stock report
* Fix: No titles for action buttons on Stock Report
* Fix: Prevent to print empty menu item is no label set
* Fix: Wrong link for user on commission details page

= 1.4.13 - Released on 24th January, 2019 =

* Fix: Unable to select product in Reports
* Fix: Cancel schedule link is visibile if no schedule date is set
* Fix: Product from a vendor will assign to another vendor if manage stock option is enabled
* Fix: Some string with wrong textdomain
* Update: All language files
* Update: Plugin FW

= 1.4.12 - Released on 05th December, 2018  =

* New: Support for WordPress 5.0
* New: Support for Gutenberg
* New: Support for YITH WooCommerce SMS Notification
* Fix: unable to edit attributes because of a conflict with Bootstrap stylesheets (YITH themes)

= 1.4.11 - Released on 30th October, 2018  =

* Update: Plugin core to version 3.0.32
* Update: Language files
* Fix: Wrong style for live chat console
* Fix: Missing Text Domain for Save button in edit product
* Fix: Unable to set attribute on products

= 1.4.10 - Released on 23rd October, 2018  =

* Update: Plugin core framework
* Tweak: Plugin performance
* Dev: yith_wcfm_flush_rewrite_rules_send_die_if_ajax hook to skip wp_send_json in action call

= 1.4.9 - Released on 18th October, 2018  =

* New: Skins template overriding
* Tweak: Load alla scripts in footer
* Update: Italian language
* Fix: Wrong or missing text domain in coupon section
* Fix: Issue on add new attribute, save wrong name and slug.
* Fix: Undefined index report in stock report page
* Fix: Unable to create attribute taxonomy if user don't write the taxonomy slug
* Fix: Slug length limited to 28 chars

= 1.4.8 - Released on 26th July, 2018  =

* Fix: Unable to saving variations for new product

= 1.4.7 - Released on 17th July, 2018  =

* Fix: Unable to saving variations for new product
* Fix: Unable to disable sections for vendors
* Fix: Remove error_log on coupon template for admin and vendors

= 1.4.6 - Released on 14rd June, 2018  =

* Fix: Wrong url in Stripe Connect message

= 1.4.5 - Released on 13rd June, 2018  =

* Update: Spanish language
* Fix: Missing product_id in product table
* Fix: Orders list doesn't show orders with no commissions
* Fix: Connect with Stripe button don't show up on frontend manager dashboard

= 1.4.4 – Released on 14th May, 2018 =

* Update: Italian language
* Fix: Wrong logout link on dashboard
* Fix: Live chat integration for chat operators
* Fix: Setting product quantity to zero doesn't turn the stock status into "Out of stock"
* Fix: Variations navigation shown in edit product variations panel
* Fix: Set status for variable products
* Fix: Vendors list table doesn't updated after new vendor created action

= 1.4.3 - Released on 23rd April, 2018 =

* New: Italian language
* New: Dutch language
* Fix: Unable to change order status with vendor or shop manager user
* Fix: Unable to delete orders with vendor profile
* Fix: Unable to add attribute if shop manager  can't access in admin area
* Dev: yith_wcfm_logout_redirect_url hook to change the logout redirect url

= 1.4.2 - Released on 14th March, 2018 =

* New: Support for YITH WooCommerce Name Your Price Premium
* Fix: Unable to deactivate Vendor Profile Section

= 1.4.1 - Released on 21th February, 2018 =

* Tweak: 404 permalink issue after theme switching on WordPress 4.9 or greather
* Tweak: 404 permalink issue after YITH WooCommerce Multi Vendor plugin activation on WordPress 4.9 or greather
* Fix: Delete product don't set product to trash status
* Fix: 404 permalink issue after login or logout
* Fix: 404 permalink issue after new user created
* Fix: 404 permalink issue after new vendor created
* Fix: 404 permalink issue when the admin use User Switching plugin
* Dev: Add yith_wcfm_skin_1_header_blog_title hook to change the blog_name in skin-1 header
* Dev: yith_wcfm_force_delete_product hook

= 1.4.0 - Released on 14th February, 2018 =

* New: Add support for WooCommerce 3.3
* New: Support for YITH WooCommerce Tab Manager Premium
* New: Support for YITH WooCommerce Featured Radio and Video Content Premium
* New: Support for YITH Live Chat Premium
* Fix: Plugin create tab manager section without plugin enabled
* Fix: Vendor can create coupon for all products
* Fix: The endpoint is already registered by WooCommerce for vendor settings page in admin mode
* Fix: Unable to create coupon if user use a custom endpoint
* Fix: Vendors can't set product to DRAFT
* Fix: Stock report action icon style missing
* Fix: No message after add/edit product
* Fix: Vendors can't create coupon on frontend
* Fix: Wrong style in order refund table
* Fix: Conflict between Frontend Manager and Divi Builder color picker
* Fix: Unable to see purchased col on front
* Fix: Product Shipping table in edit product variation
* Fix: Wrong path in register style and scripts method
* Fix: Product Categories aren't show in hierarchical mode
* Fix: Unable to show featured products column for vendors
* Fix: Header and Footer sidebars doesn't works with Skin-1
* Fix: Edit order uri redirect to admin area in commission detail page
* Fix: Edit product uri redirect to in admin area in commission detail page

= 1.3.2 - Released on 22nd December, 2017 =

* Fix: Style issue with YITH theme with FW 2.0 or greather
* Fix: Tags and Categories list in add/edit product page
* Fix: Unable to set Enable Reviews in add/edit product page

= 1.3.1 - Released on 15th December, 2017 =

* New: Tested up WooCommerce 3.2.6
* Update: Plugin Framework 3.0.1

= 1.3.0 - Released on 13rd December, 2017 =

* New: Translate frontend manager menu with WPML
* New: Support for YITH WooCommerce Colors and Labels Variations Premium (min. version 1.5.1)
* Tweak: Remove dynamics.css file from YITH 2.0 theme options from Skins
* Tweak: Update the YITH Plugin Framework 3.0
* Update: Spanish language files
* Fix: Fatal error if try to change the status of one commission in bulk action
* Fix: Style issue with long Net sales this month value in dashboard
* Fix: Unable to remove all images from product gallery
* Fix: Lost is_active class in navigation menu if custom slug are set
* Fix: Unable to scroll the commissions table on flatsome theme
* Fix: Column post.ID doesn't exists in Dashboard if vendor haven't orders
* Fix: Responsive style on Reports and Product Edit page
* Fix: Vendor with pending account can access to frontend manager dashboard
* Fix: Style issue with Globe theme by YITH in Product Edit Page

= 1.2.1 =

* New: Support to YITH WooCommerce Featured Audio and Video Content Premium (vers. 1.1.16 or greather)
* Tweak: 2.0 accessibility. Text meant only for screen readers

= 1.2.0 =

* New: Support for WooCommerce TM Extra Product Options plugin
* New: Support fo Woocommerce 3.2.1
* Tweak: change the string "Net sales on this month" to "Net commissions on this month" in vendor's dashboard
* Tweak: Change the old wc-tooltip with jquery-tiptip
* Tweak: Add minify js file for coupons script
* Fix: Coupons script loaded two times if the current user is a valid vendor
* Fix: Unable to add variable products with WooCommerce 3.2
* Fix: Unable to add related products with WooCommerce 3.2
* Fix: Net sales commissions on dashboard showing only 0,00 $ with version 1.1.0
* Fix: Vendor can't add order notes
* Fix: WooCommerce admin style.css enqueued in all pages
* Fix: Unable to set percentage coupon for vendors

= 1.1.0 =

* Tweak. Add message if the user enable the plugin without WooCommerce
* Tweak: Prevent Fatal Error if the wc_create_page function doesn't exists
* Tweak: Skin system refactoring
* Fix: Unable to save product shipping class
* Fix: Style in skin-1 sidebar
* Fix: New vendor can see all orders
* Fix: Unable to save shipping for vendors with a long list of shipping classes
* Fix: Wrong net sales in dashboard for vendor users

= 1.0.15 =

* Tweak: Prevent "Nested level too deep" error
* Tweak: Prevent to have different store with the same name
* Fix: Wrong style with Firefox browser
* Fix: Vendor avatar doesn't show in skin-1
* Fix: Vendor name doesn't shown in edit product
* Fix: Wrong net sales value for vendors with no orders in dashboard section
* Fix: Style issue in product variation box
* Fix: Plugin loads admin scripts on frontend on all website pages

= 1.0.14 =

* Tweak: Improved style for shipping zones popup on YITH WooCommerce Multi Vendor panels inside Frontend Manager

= 1.0.13 =

* Fix: Removed notice on templates/skins/skin-1/header.php
* Fix: Datepicker on coupons

= 1.0.12 =

* Tweak: Change vendor admin link with frontend manager page
* Fix: Vendor restrict backend access option fails all admin ajax calls on frontend
* Fix: Administrator can't see WordPress admin bar
* Fix: Products section and Reports section ABSPATH
* Fix ABSPATH in dashboard sections

= 1.0.11 =

* Tweak: Flush permalinks after user login
* Fix: Empty billing and shipping address if admin/vendor click on pencil icon to edit it
* Fix: Vendor can't set shipping zone and shipping method on frontend

= 1.0.10 =

* Fix: Vendor can't see media library if user have no access to admin area
* Fix: Vendor can't upload image in media library if user have no access to admin area
* Fix: Shop Manager can't see media library if user have no access to admin area
* Fix: Shop Manager can't upload image in media library if user have no access to admin area

= 1.0.9 =

* Fix: Unable to save vendor settings if vendor can't access to backend
* Fix: Prevent wrong edit post type url if no default page is set
* Tweak: Enanched style support for Nielsen theme
* Tweak: Enanched style support for Twenty theme
* Tweak: Enanched style support for Flatsome theme
* Tweak: Enanched style support for Sydney theme
* Tweak: Enanched style support for Business Center theme
* Tweak: Enanched style support for R�my theme
* Tweak: Enanched style support for Mindig theme
* Tweak: Enanched style support for Desire Sexy Shop theme

= 1.0.8 =

* Fix: Unable to set price for simple products with Flatsome and WooCommerce 3.1
* Fix: Undefined index tab in panel page

= 1.0.7 =

* New: Flush permalinks option
* New Support for WooCommerce 3.1-RC2
* Fix: get_current_screen() not defined in frontend class
* Fix: Support for Flatsome theme
* Fix: Support for bb-theme
* Fix: 404 on each sections after plugin activation

= 1.0.6 =

* New: Support to YITH Nielsen theme
* Tweak: Version on skin style.css
* Fix: Some strings have a wrong text-domain
* Fix: Missing icon if change sections slug

= 1.0.5 =

* Fix: Some strings have a wrong text-domain
* Fix: missing CSS Rules on default skin and skin-1
* Fix: All sections return a 404 not found error after theme switching or multi vendor plugin activation
* Tweak: Hide live chat popup in frontend manager section

= 1.0.4 =

* Fix: Super admin can't access to backend in WordPress MultiSite if the vendor restrict admin area access are set to "YES"

= 1.0.3 =

* New: Add language catalog file
* Fix: Translation issue in endpoint sections panel

= 1.0.2 =

* Fix: Failed opening required with multi vendor free

= 1.0.1 =

* Fix: The function YITH_Vendor_Shipping doesn't exists

= 1.0.0 =

* Initial release
