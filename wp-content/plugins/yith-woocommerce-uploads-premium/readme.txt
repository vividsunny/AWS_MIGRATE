=== YITH WooCommerce Uploads Premium ===

Contributors: yithemes
Tags: woocommerce, e-commerce, ecommerce, shop, file upload, attach file, append file, customize order.
Requires at least: 4.0
Tested up to: 5.4
Stable tag: 1.2.16
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Documentation: https://docs.yithemes.com/yith-woocommerce-uploads/

It lets your customers attach and upload a file to their order. You can also set limits to file size and extension to avoid wrong uploads.

== Description ==

A concrete way to customize your orders: upload a file with your image and complete your order, or attach the PDF copy of your ID card which is required to purchase that specific item. YITH WooCommerce Uploads allows both your customers to  add something personal to their purchase and you to help them not forget essential documents needed to complete their order. Attach a file is an essential feature that can make you save a lot of time when dealing with customers via email about details of the order.

= 1.2.16 - Released on 29 May 2020 =

* New: Support for WooCommerce 4.2
* Update: plugin framework
* Update: .pot file
* Dev: added the class et_smooth_scroll_disabled to the upload link, to avoid problems with Divi

= 1.2.15 - Released on 04 May 2020 =

* New: Support for WooCommerce 4.1
* Tweak: do not show the product upload section in the order edit page if the uploads are disabled for products
* Update: plugin framework
* Update: plugin settings updated
* Update: Spanish language files
* Update: Italian language files
* Update: Dutch language files
* Dev: new filter 'ywar_show_uploaded_thumbnails_on_order'
* Remove: wrong .pot file removed

= 1.2.14 - Released on 05 March 2020 =

* New: Support for WordPress 5.4
* New: Support for WooCommerce 4.0
* Fix: fixed an issue with the variation rules selector
* Dev: all strings escaped
* Dev: deleted Emogrifier class load

= 1.2.13 - Released on 26 December 2019 =

* New: support WooCommerce 3.9
* Update: plugin framework
* Fix: option panel changes

= 1.2.13 - Released on 26 December 2019 =

* New: support WooCommerce 3.9
* Update: plugin framework
* Fix: option panel changes

= 1.2.12 - Released on 07 November 2019 =

* New: support WooCommerce 3.8
* Update: Spanish language
* Update: Italian language
* Update: updated plugin core
* Fix: added empty value to avoid warning when do stripslashes callback

= 1.2.11 - Released on 05 August 2019 =

* New: support WooCommerce 3.7
* Tweak: prevent problem when plugins or theme remove rel=prettyPhoto
* Update: language files
* Update: updated plugin core
* Fix: note not showed inside email
* Fix: fixed minor issue with the remove file process
* Dev: updating minified JS
* Dev: changed outdated methods by getters

= 1.2.10 - Released on 29 May 2019 =

* New: Show link email for upload on orders
* Tweak: Added a load animation when the file is uploaded
* Tweak: Deleted all commented code
* Update: Plugin-fw
* Dev: Action ywau_after_order_upload_link

= 1.2.9 - Released on 11 April 2019 =

* Fix: fixed an issue with missed files on the orders

= 1.2.8 - Released on 09 April 2019 =

* New: support to WooCommerce 3.6.0 RC 1
* Update: updated plugin FW
* Update: Spanish translation
* Update: Dutch translation
* Dev: check if files exist to rename the files

= 1.2.7 - Released on 19 February 2019 =

* Update: updated plugin FW
* Update: updated Dutch language
* Update: updated Italian language

= 1.2.6 - Released on 13 December 2018 =

* Update: updating plugin FW
* Fix: fixing a possible issue with a new filter

= 1.2.5 - Released on 07 December 2018 =

* New: support to WordPress 5.0
* Update: plugin core to version 3.1.6
* Update: Italian language
* Dev: new filter 'ywau_src_pretty_photo_script'

= 1.2.4 - Released on 23 October 2018 =

* Update: plugin framework
* Update: plugin description
* Update: plugin links

= 1.2.3 - Released on 17 October 2018 =

* New: Support to WooCommerce 3.5.0
* New: added a new option to enable the variation uploads by default
* New: Two options to automatically accept the the upload file or the order or upload file of the product on the order created
* Tweak: new action links and plugin row meta in admin manage plugins page
* Update: Dutch language
* Update: Spanish language
* Update: updated the official documentation url of the plugin
* Update: updating the main rules settings name
* Update: Updating Plugin FrameWork
* Fix: Add uploaded file to the order when the option "Allow on cart" is not activated
* Fix: Upload buttons for cart items after updating the cart by ajax
* Fix: multiple uploads for variations
* Dev: checking YITH_Privacy_Plugin_Abstract for old plugin-fw versions
* Dev: commented code to allow the thank you message
* Dev: added filter to the email rejected and accepted message
* Dev: improve function get_instance()
* Dev: added a class when file is accepted or rejected by admin


= 1.2.2 - Released on 29 May 2018 =

* New: Support to WooCommerce 3.4.0
* GDPR:
   - New: exporting user additional uploads data info
   - New: erasing user additional uploads data info
   - New: privacy policy content
* Tweak: filter to customize email admin after upload a file
* Update: dutch language
* Update: documentation link of the plugin
* Fix: Wrong string domains
* Dev: added an argument to a filter

= 1.2.1 - Released on 31 January 2018 =

* Update: plugin framweork 3.0.11
* New: support to WooCommerce 3.3.x


= 1.2.0 - Released on 04 January 2018 =

* New: dutch translation
* Update: plugin framework to the version 3.0.5
* Dev: new filter 'yith_ywau_notes_frontend_label'


= 1.1.30 - Released on 01 August 2017 =

* Dev: added filter 'ywau_link_class_message' to change the class of upload link
* Fix: prevent error for corrupted files
* Fix: order upload text option not used


= 1.1.29 - Released on 19 July 2017 =

* Fix: accept/reject uploads issue


= 1.1.28 - Released on 18 July 2017 =

* Fix: subject and email heading fields not always getting the selected value
* Update: plugin core framework


= 1.1.27 - Released on 06 July 2017 =

* New: support for WooCommerce 3.1.
* New: tested up to WordPress 4.8.
* Update: YITH Plugin Framework.

= 1.1.26 - Released on 19 May 2017 =

* New: set the message to be shown in cart/checkout page when using the order upload.
* Update: language files.

= 1.1.25 - Released on 19 May 2017 =

* Fix: Illegal string offset error when uploading a file to the cart.
* Fix: conflict with YITH Event Tickets.
* Tweak: prevent multiple emails notification for one order.

= 1.1.24 - Released on 10 May 2017 =

* Fix: missing button for uploading files to the order.
* Fix: on cart page, upload status not updated after a successful upload.

= 1.1.23 - Released on 04 May 2017 =

* Fix: uploaded images not flushed after a valid checkout.

= 1.1.22 - Released on 26 April 2017 =

* Update: plugin-fw.
* Fix: unable to save variation rules.

= 1.1.21 - Released on 04 April 2017 =

* New: Support WooCommerce 3.0
* Fix: YITH Plugin Framework initialization.
* Fix: Unable to translate "Choose one of the following formats" string

= 1.1.20 - Released on 02 January 2017 =

* Fixed: removed the upload link in emails

= 1.1.19 - Released on 07 December 2016 =

* Added: ready for WordPress 4.7
* Added: two filters that let third party plugins or themes to choose if the upload is enabled for specific pages

= 1.1.18 - Released on 23 November 2016 =

* Tweaked: scripts enqueued only in pages where the upload could be done, considering the plugin options and the product status
* Fixed: upload button not visible in checkout page if cart visibility was set to false on plugin's options

= 1.1.17 - Released on 24 August 2016 =

* Fixed: pop up in wrong position on checkout page

= 1.1.16 - Released on 23 August 2016 =

* Fixed: duplicated uploaded files on orders now work fine.
* Fixed: action button for accepting or rejecting the uploaded file on orders now triggers the expected action

= 1.1.15 - Released on 04 July 2016 =

* Fixed: do not shown empty div if the order do not have file uploaded
* Fixed: do not show the old order as all file uploaded were accepted, if there isn't any file uploaded

= 1.1.14 - Released on 15 June 2016 =

* Fixed: accept and reject button did not trigger the event

= 1.1.13 - Released on 13 June 2016 =

* Added: WooCommerce 2.6 100% compatible
* Added: spanish localization

= 1.1.12 - Released on 09 May 2016 =

* Added: WPML compatibility for "disabled upload" for translated products
* Fixed: the upload action based on the order status do not work for uploads associated to the order

= 1.1.11 - Released on 29 April 2016 =

* Fixed: the uploaded files were not associated to the order is both order and product uploads option was set
* Fixed: the upload button for orders was not displayed on thankyou page

= 1.1.10 - Released on 27 April 2016 =

* Added: admin can choose if the upload rules have to be used only for products, only for orders or for both of them
* Fixed: the upload rule for orders are shown on cart event if the related option is disabled

= 1.1.9 - Released on 26 April 2016 =

* Fixed: the upload fails for some file extensions
* Updated: yith-woocommerce-additional-uploads.pot file

= 1.1.8 - Released on 20 April 2016 =

* Added: let your customer to upload files attached to the whole order
* Updated: support to WP 4.5
* Updated: YITH Plugin FW

= 1.1.7 - Released on 14 March 2016 =

* Fixed: script syntax issues on long rule description

= 1.1.6 - Released on 10 March 2016 =

* Added: option that let the customer upload a file from my-account page

= 1.1.5 - Released on 08 March 2016 =

* Updated: sent email on file uploaded by the customer
* Updated: yith-woocommerce-additional-uploads.pot file
* Added: action yith_ywau_email_file_uploaded_order_item on email that notify file uploaded

= 1.1.4 - Released on 08 February 2016 =

* Fixed: jQuery script that shows the upload rules on cart page

= 1.1.3 - Released on 25 January 2016 =

* Fixed: unable to modify a file if it was rejected

= 1.1.2 - Released on 21 January 2016 =

* Fixed: upload fails when the option Storing mode is set to "order number"

= 1.1.1 - Released on 20 January 2016 =

* Fixed: some layout issue

= 1.1.0 - Released on 18 January 2016 =

* Updated: plugin ready for WooCommerce 2.5
* Fixed: some method call fails with PHP prior than 5.6

= 1.0.8 - Released on 18 December 2015 =

* Fixed: deleting uploaded file fails on simple products

= 1.0.7 - Released on 23 November 2015 =

* Updated: script enqueue priority changed to 199 to ensure PrettyPhoto will be registered
* Updated: changed action used for YITH Plugin FW loading from after_setup_theme to plugins_loaded

= 1.0.6 - Released on 03 November 2015 =

* Fixed: totals on checkout page doesn't update changing shipping methods

= 1.0.5 - Released on 29 October 2015 =

* Added: Separated lines in cart for multiple items of same product
* Updated: YITH plugin framework

= 1.0.4 - Released on 26 October 2015 =

* Fixed: wrong file path used while including emogrifier.php file

= 1.0.3 - Released on 06 October 2015 =

* Fixed: files attached to variations not downloadable.

= 1.0.2 - Released on 17 Sep 2015 =

* Added: new option to allow file upload from checkout page or thank you page.
* Added: you can add different upload rules for each variation instead of using the same rules for any product variations.
* Added: users can edit uploaded files even from cart page.

= 1.0.1 - Released on 01 Sep 2015 =

* Fixed: removed deprecated woocommerce_update_option_X hook.

= 1.0.0  - Released on 14 August 2015 =

* Initial release
