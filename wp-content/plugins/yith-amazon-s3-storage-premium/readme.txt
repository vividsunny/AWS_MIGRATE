== YITH WooCommerce Amazon S3 Storage ==

Contributors: yithemes
Tags: amazon s3 storage, woocommerce, products, themes, yit, e-commerce, shop, plugins
Requires at least: 4.0.0
Tested up to: 5.3.x
Stable tag: 1.1.12
Licence: GPLv2 or later
Licence URI: http://www.gnu.org/licences/gpl-2.0.html
Documentation: https://docs.yithemes.com/yith-amazon-s3-storage/

= 1.1.12 - Released on 28 May 2020 =

* New: Support to WooCommerce 4.2
* Update: sdk version with composer
* Update: .pot file

= 1.1.11 - Released on 30 April 2020 =

* New: Support to WooCommerce 4.1
* New: Greek language
* Update: Plugin-Fw
* Update: .pot file
* Fix: Permalink on attachment images if the url is served directly from s3

= 1.1.10 - Released on 27 February 2020 =

* New: Support for Wordpress 5.4
* New: Support to WooCommerce 4.0
* Update: Plugin-Fw
* Fix: Allow to upload images directly from media for product image section also on new products
* Fix: problem with WPML on product images

= 1.1.9 - Released on 23 December 2019 =

* New: Support to WooCommerce 3.9
* Tweak: Remove button amazon s3 when close the media library
* Update: Plugin-Fw

= 1.1.8 - Released on 18 November 2019 =

* Fix: Process to add files to amazon s3
* Update: Plugin-Fw

= 1.1.7 - Released on 07 November 2019 =

* New: Support to WooCommerce 3.8
* Tweak: Prevent fatal error when connect to a private bucket
* Tweak: Refactoring code
* Update: Plugin-Fw

= 1.1.6 - Released on 09 September 2019 =

* Tweak: Prevent blank page when wp_get_referer function return false
* Tweak: Prevent error if array doesn't exists
* Fix: Upload private files on product page
* Update: Plugin-Fw

= 1.1.5 - Released on 08 August 2019 =

* New: Support to WooCommerce 3.7
* Tweak: show progress bar message on the progress bar
* Tweak: allow to upload multiple items in amazon s3 grid
* Tweak: add images on downloadable section
* Tweak: remove s3 amazon tab for product image and product gallery
* Tweak: allow to download files that have in s3 amazon as private in download section for product page
* Tweak: show new folders on Amazon s3 tab
* Update: Italian translation
* Update: Plugin-Fw
* Dev: added new filter yith_wcamz_download_amazon_s3_file_private

= 1.1.4 - Released on 17 June 2019 =

* New: Support to WooCommerce 3.6.4
* Update: Plugin-fw
* Dev: New filter "yith_wc_as3s_attachment_url_filter"
* Dev: New filter "yith_wc_as3s_upload_dir_filter"

= 1.1.3 - Released on 30 May 2019 =

* New: Support to WordPress 5.2.1
* Update: Plugin-fw

= 1.1.2 - Released on 29 May 2019 =

* Fix: Prevent warning session destroy
* Update: Plugin-fw
* Dev: yith_wcamz_init_s3_client

= 1.1.1 - Released on 12 April 2019 =

* New: Support to WooCommerce 3.6.0 RC1
* Update: Plugin-Fw
* Update: Dutch language
* Fix: Load images on product image section and gallery image section when they are served by S3

= 1.1.0 - Released on 21 March 2019 =

* Update: updated plugin framework

= 1.0.2 - Released on 21 February 2019 =

* Tweak: hide upload options on admin product image
* Tweak: improve how to get the file when the folder is an url
* Update: plugin framework
* Update: Spanish translation
* Update: Italian translation
* Update: Dutch translation
* Update: language file .pot


= 1.0.11 - Released on 24 October 2018 =

* Update: plugin framework
* Update: plugin description
* Update: plugin links

= 1.0.10 - Released on 17 October 2018 =

 * New: Support to WooCommerce 3.5.0
 * Tweak: new action links and plugin row meta in admin manage plugins page
 * Tweak: Adding filter to set the timeout of the downloading by js
 * Update: Italian language
 * Update: Spanish translation
 * Update: Dutch translation
 * Fix: Allowing to download big files weight

 * Tweak: filter to choose whether to download the file from PHP or JS

 * Dev: loading admin and frontend classes without PHP sessions

= 1.0.9 - Released on 2 April 2018 =

 * Tweak: improvement of displaying the settings
 * Tweak: filter to choose whether to download the file from PHP or JS
 * Fix: load plugin textdomain
 * Fix: display correctly amazon urls of srcset
 * Dev: loading admin and frontend classes without PHP sessions

= 1.0.8 - Released on 27 February 2018 =

 * Tweak: setting the Amazon base url for all kind of credentials
 * Fix: changing string temporarlly to temporary

= 1.0.7 - Released on 09 February 2018 =

 * New: Support to WooCommerce 3.3.1
 * Tweak: Download links without javascript
 * Tweak: Customize the link of the order under the user account

= 1.0.6 - Released on 05 February 2018 =

 * Tweak: Showing download links and download counting without using the WooCommerce templates
 * Fix: download link compatible for mozilla and safari

= 1.0.5: Released on 31 January 2018 =

 * New: Checking the images in the content
 * New: Spanish translation .po and .mo
 * Tweak: Adding a filter to modify target blank
 * Tweak: Adding an additional link to the email to go to the order under account
 * Tweak: adding the counting of the downloads
 * Fix: Javascript error .select2 not found
 * Fix: Showing download urls on admin orders
 * Fix: Showing correct download url on emails
 * Fix: Configuring the valid time for downloads
 * Fix: Checking several images in content
 * Remove: Cleaning unnecessary code

= 1.0.4 - Released on 29 Dec 2017 =

 * New: Italian translation
 * New: Dutch translation
 * Update: plugin_fw

= 1.0.3 - Released on 24 November 2017 =

 * Fix: Compatibility of url for downloadable products in downloads of my account with WooCommerce 3.2.1

= 1.0.2 - Released on 23 November 2017 =

 * Fix: Compatibility of url for downloadable products with WooCommerce 3.2.1 for emails sent

= 1.0.1 - Released on 16 November 2017 =

 * Fix: Compatibility of url for downloadable products with WooCommerce 3.2.1

= 1.0.0 - Released on 10 July 2017 =

* First release

== Suggestions ==

If you have suggestions about how to improve YITH Amazon S3 for WooCommerce Premium, you can [write us](mailto:plugins@yithemes.com "Your Inspiration Themes") so we can bundle them into the next release of the plugin.

== Translators ==

If you have created your own language pack, or have an update for an existing one, you can send [gettext PO and MO file](http://codex.wordpress.org/Translating_WordPress "Translating WordPress")
[use](http://yithemes.com/contact/ "Your Inspiration Themes") so we can bundle it into YITH Quick Order Forms for WooCommerce Premium.
 = Available Languages =
 * English