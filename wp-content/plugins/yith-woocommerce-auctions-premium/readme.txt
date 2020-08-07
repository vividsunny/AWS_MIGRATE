=== YITH WooCommerce Auctions ===

== Changelog ==

= 1.4.3 - Released on 28 May 2020 =

* New: Support to WooCommerce 4.2
* Update plugin-fw
* Fix error when try to duplicate a product
* Fix Prevent to launch an auction product if it's close by buy now
* Update .pot file

= 1.4.2 - Released on 05 May 2020 =

* Fix: Send winner email multiple times

= 1.4.1 - Released on 04 May 2020 =

* New: Support to WooCommerce 4.1.0
* Tweak remove code for remove price when switch from simple to auction
* Update plugin-fw
* Update Greek file
* Update .pot file

= 1.4.0 - Released on 14 April 2020 =

* New: Add some class to manage auction product follow CRUD
* Tweak: Change email class to a dedicated folder
* Tweak: Improvements on templates for show better ui on Proteo theme
* Tweak: Add more information when the winner email is not send to a customer
* Update: Plugin-fw
* Update: .pot file
* Dev: New hook yith_wcact_actual_bid_value
* Dev: New parameter on yith_wcact_winner_email_pay_now_url hook
* Dev: New filter yith_wcact_get_checkout_url
* Dev: New parameter on yith_wcact_better_bid hook
* Dev: New parameter on yith_wcact_query_loop hook
* Dev: New hook yith_wcact_get_price_for_customers_buy_now

= 1.3.4 - Released on 05 March 2020 =

* Update: Italian language
* Update: Spanish language
* Update: Plugin-fw
* Fix: Query loop
* Fix Prevent error if $product doesn't exists
* Tweak: Prevent to send auction email if auction is not closed
* Tweak: Allow format date g and G for auction times
* Tweak: All strings scaped
* Dev: Add new parameter to yith_wcact_check_bid_increment filter
* Dev filter yith_wcact_get_product_permalink_redirect_to_my_account

= 1.3.3 - Released on 23 December 2019 =

* New: Support to WooCommerce 3.9.0
* Update: plugin-fw
* tweak: Prevent error when product doesn't exists
* Dev filter yith_wcact_message_successfully
* Dev filter yith_wcact_message_successfully_notice_type

= 1.3.2 - Released on 04 November 2019 =

* Update: plugin-fw

= 1.3.1 - Released on 24 October 2019 =

* New: Support to WooCommerce 3.8.0 RC1
* New: plugin panel style
* Tweak: Prevent fatal error when product doesn't exists
* Update: plugin-fw
* Dev: filter 'yith_wcact_show_buttons_auction_end'

= 1.3.0 - Released on 05 August 2019 =

* New: Support to WooCommerce 3.7.0 RC2

* Tweak: Show auction start and end time on backend in WordPress timezone
* Tweak: Add new parameter to the filter yith_wcact_get_price_for_customers
* Tweak: Show auction data on backend using the same dateformat as frontend
* Tweak: Check if the product is auction before send the email
* Tweak: Add new parameters to the yith_wcact_shortcode_catalog_orderby filter
* Update: Italian language
* Update: plugin-fw
* Update: Pot file
* Dev: filter yith_wcact_winner_email_pay_now_url

= 1.2.9 - Released on 20 May 2019 =

* Fix: enqueue correct script for shortcodes
* Dev: filter yith_wcact_actual_bid_add_value
* Dev: filter yith_wcact_load_auction_price_html
* Dev: filter yith_wcact_check_bid_increment

= 1.2.8 - Released on 11 Abr 2019 =

* New: Support to WooCommerce 3.6.0 RC2
* Update: Plugin-Fw
* Update: Dutch language
* Tweak: add woocommerce_before_add_to_cart_button on auction template
* Dev: action yith_wcact_auction_end_start
* Dev: filter yith_wcact_check_if_add_bid

= 1.2.7 - Released on 21 March 2019 =

* New: Customer email when a bid is deleted
* New: Admin email when a bid is deleted
* New: Ban customer for make bids
* Update: Plugin-Fw
* Fix: Check if the current user can manage WooCommerce
* Tweak: Add current user id on args variable
* Tweak: Save data-time for prevent problems with YITH WooCommerce Pre Order
* Dev: Filter yith_wcact_interval_minutes
* Dev: Filter yith_wcact_new_date_finish
* Dev: Filter yith_wcact_change_button_auction_shop_text
* Dev: Filter yith_wcact_auction_not_available_message

= 1.2.6 - Released on 15 Feb 2019 =

* New: Integration with YITH WooCommerce Quick View
* Update: Spanish translation
* Update: Dutch translation
* Tweak: Prevent some warnings related to product ID
* Tweak: Prevent warning on non-auction products when trying to call bid list template
* Update: Plugin-fw

= 1.2.5 - Released on 05 Dic 2018 =

* New: Support to WordPress 5.0
* New: Gutenberg block for auction shortcodes
* Update: plugin framework
* Update: Italian language

= 1.2.4 - Released on 23 October 2018 =

* Update : Plugin framework
* Tweak: Prevent send emails when auction trash
* Dev: yith_wcact_render_product_columns_dateinic
* Dev: yith_wcact_render_product_columns_dateclose


= 1.2.3 - Released on 02 October 2018 =

* New : Send notification to customer who lost auction
* New : Daily cronjob to resend failed emails to winners
* Tweak : Improve slow queries
* Update : Dutch language
* Fix : Time format on related product section
* Dev : Filter yith_wcact_check_email_is_send
* Dev : Filter yith_wcact_congratulation_message
* Dev : Filter yith_wcact_my_account_congratulation_message
* Dev : Filter yith_wcact_product_exceeded_reserve_price_message
* Dev : Filter yith_wcact_product_has_reserve_price_message
* Dev : Filter yith_wcact_product_does_not_have_a_reserve_price_message
* Dev : Action yith_wcact_auction_status_my_account_closed
* Dev : Action yith_wcact_auction_status_my_account_started

= 1.2.2 - Released on 27 June 2018 =

* New: Admin option to resend failed emails to winners
* New: Daily cronjob to resend failed emails to winners
* Update: Italian language
* Update: Spanish language
* Tweak: Possibility to change recipient email
* Dev : Filter yith_wcact_check_email_is_send


= 1.2.1 - Released on 21 May 2018 =

* New: Support to WordPress 4.9.6 RC2
* New: Support to WooCommerce 3.4.0 RC1
* New: Metabox auction status
* New: Possibility to resend auction email
* Update: Plugin Framework
* Dev: Filter yith_wcact_show_time_in_customer_time
* Dev: Filter yith_wcact_tab_auction_show_name
* Dev: Filter yith_wcact_display_user_anonymous_name

= 1.2.0 - Released on 03 April 2018 =

* New: Shortcode [yith_auction_non_stated] to show no started auctions
* New: Support WPML Currency Switcher
* Tweak: WPML Currency Language
* Update: Plugin Framework
* Fix: Problem WPML products in cart and checkout page
* Fix: Problem with buy now button

= 1.1.14 - Released on 09 Feb 2018 =

* New: support to WordPress 4.9.4
* New: support to WooCommerce 3.3.1
* New: shortcode [yith_auction_current] to show current live auctions
* Tweak: pagination in auction shortcodes
* Tweak: select number of columns and product per page in auction shortcodes

= 1.1.13 - Released on 30 January 2018 =

* New: support to WordPress 4.9.2
* New: support to WooCommerce 3.3.0-RC2
* Update: plugin framework 3.0.11

= 1.1.12 - Released on 10 January 2018 =

* New: Product parameter in end-auction email
* Fix: notice in wp-query
* Fix: problem check stock in auction.php template
* Update: Plugin core
* Update: Spanish translation
* Dev: filter yith_wcact_max_bid_manual
* Dev: filter yith_wcact_auction_product_id
* Dev: filter yith_wcact_show_buy_now_button
* Dev: action yith_wcact_auction_auction_reserve_price
* Dev: action yith_wcact_after_auction_end

= 1.1.11 - Released on 24 October 2017 =

* New: added new successfully bid email
* Update: Plugin core

= 1.1.10 - Released on 20 October 2017 =

* Fix: error get image and bids on auction product page
* Update: Plugin core

= 1.1.9 - Released on 17 October 2017 =

* Fix: error load bid table
* Dev: added filter yith_wcact_show_list_bids

* New: Support to WooCommerce 3.2.0 RC2
* Update: Plugin core

= 1.1.8 - Released on 10 October 2017 =

* New: Support to WooCommerce 3.2.0 RC2
* Update: Plugin core

= 1.1.7 - Released on 02 October 2017 =

* Fix:  Issue with timeleft
* Fix : Issue send admin winner email
* Fix : Get right url using WPML
* Dev : Added action yith_wcact_before_add_to_cart_form

= 1.1.6 - Released on 28 August 2017 =

* Fix: Show products on shop page when out of stock general option is enabled
* Fix : Style issue on my auctions chart

= 1.1.5 - Released on 16 August 2017 =
* New: Dutch translation
* Fix: Send multiple emails when the auction is in overtime.
* Dev: Added filter yith_wcact_display_watchlist

= 1.1.4 - Released on 14 August 2017 =

* New: add more than one recipient to the winner email sent to the admin
* New: added tax class and tax status
* New: added new label when the auction is closed and no customer won the auction
* New: shortcode [yith_auction_out_of_date] to show out of date auctions
* Fix: URL encode to prevent redirect error.
* Fix: check if the auction has ended when a customer bids
* Fix: count auction product in shop loop.
* Fix: show pay-now button when an auction is rescheduled.
* Dev: added filter yith_wcact_datetime_table
* Dev: added filter yith_wcact_bid_tab_title
* Dev: added filter yith_wcact_priority_bid_tab
* Dev: added filter yith_wcact_bid_tab

= 1.1.3 - Released on 07 July 2017 =

* New: Compatibility with  YITH Infinite Scrooling Premium
* Fix: remove auction product on shop loop when option is disabled
* Fix: remove Pay now button when the bid doesn't exceed the reserve price on my account page
* Dev: added action yith_wcact_render_product_columns
* Dev: added filter yith_wcact_product_columns

= 1.1.2 - Released on 04 May 2017 =

* New: Admin can delete customer's bid
* New: Customers register to a watchlist for each auction product and be notified by email when auction is about to end
* New: Minimum amount to increase manual bids
* New: added wc_notice in product page
* Fix: Auction product price not changing when clicking on buy now
* Fix: show a NaN number in timeleft when auction has not started
* Dev: added action yith_wcact_before_form_auction_product
* Dev: added filter yith_wcact_load_script_widget_everywhere

= 1.1.1 - Released on 04 April 2017 =

* New: support to WooCommerce 3.0.0-RC2
* New: possibility to add auction product and other products to cart in the same order
* New: reschedule auction without bids automatically

= 1.1.0 - Released on 10 March 2017 =
* New: support to WooCommerce 2.7.0-RC1
* New: live auctions on My account page
* New: live auctions on product page
* New: compatibility with WPML
* New: bid list on admin product page
* Update: YITH Plugin Framework

= 1.0.14 - Released on 07 Feb 2017 =

* New: show upbid and overtime in product page
* New: tooltip info in product page
* New: message info when auction is in overtime
* New: shortcode named [yith_auction_products] that allows you to show the auctions on any page.
* Dev: added action yith_wcact_before_add_button_bid

= 1.0.13 - Released on 23 December 2016 =

* Fixed: Issue with date time in bid tab

= 1.0.12 - Released on 16 December 2016 =

* Added: Overtime option in general settings
* Fixed: Issue with bid button
* Fixed: Product issues

= 1.0.11 - Released on 13 December 2016 =

* Added: Admin option to regenerate auction prices.
* Added: Pay now option from My account.
* Added: Possibility to add overtime to an auction.
* Updated: name and text domain.
* Updated: language file.
* Fixed: Issues with admin emails.
* Fixed: Reschedule auction when product has buy now status.
* Dev: added yith_wcact_auction_price_html filter.

= 1.0.10 - Released on 17 October 2016 =

* Fixed: "Buy Now" issue

= 1.0.9 - Released on 04 October 2016 =

* Fixed: Sending email issue.

= 1.0.8 - Released on 28 September 2016 =

* Fixed: Datetime format in product page.
* Fixed: Missing arguments in order page.
* Fixed: Username in product page problem.

= 1.0.7 - Released on 20 September 2016 =

* Added: Notification email to admin when an auction ends and has a winner.
* Added: Possibility to filter by auction status.
* Fixed: Enable/Disable email notifications.
* Fixed: Show Datetime in local time

= 1.0.6 - Released on 13 September 2016 =

* Added: Option in product settings to show buttons in product page to increase or Decemberrease the bid.
* Fixed: Problems with the translation in emails.
* Fixed: Problems with tab bid.
* Fixed: Prevent issues with manage stock.
* Fixed: Problems with order by price in shop loop.
* Added: Admin setting that show or not the pay now button in product page when the auction is ends.

= 1.0.5 - Released on 01 September 2016 =

* Fixed: username in winner email
* Fixed: timeleft in shop

= 1.0.4 - Released on 30 August 2016 =

* Fixed: enqueue script issues
* Fixed: Pay now button in winner email
* Fixed: translation issues

= 1.0.2 - Released on 22 August 2016 =

* Fixed: updated textdomain for untranslatable strings
* Fixed: Problems with pay-now button in winner email when users are not logged in.
* Fixed: Problems with product text link in winner email.
* Updated: yith-auctions-for-woocommerce.pot


= 1.0.1 - Released on 18 August 2016 =

* Added: Marchgin button in auction widget
* Fixed: Problems when not exist reserve price in auctions with automatic bids.
* Fixed: Problems with the translation.

= 1.0.0 - Released on 10 August 2016 =

* First release

== Suggestions ==

If you have suggestions about how to improve YITH WooCommerce Auctions, you can [write us](mailto:plugins@yithemes.com "Your Inspiration Themes") so we can bundle them into the next release of the plugin.

== Translators ==

If you have created your own language pack, or have an update for an existing one, you can send [gettext PO and MO file](http://codex.wordpress.org/Translating_WordPress "Translating WordPress")
[use](http://yithemes.com/contact/ "Your Inspiration Themes") so we can bundle it into YITH WooCommerce Auctions languages.

 = Available Languages =
 * English
 * Spanish
