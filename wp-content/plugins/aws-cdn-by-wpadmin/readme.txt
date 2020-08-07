=== Amazon AWS CDN ===
Contributors: luckychingi
Tags: Amazon, AWS, CDN, Free, Cloudfront, Multisite
Donate link: https://wpadmin.ca/donation/
Requires at least: 4.4.2
Tested up to: 5.3.2
Requires PHP: 7.0
Stable tag: 1.4.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Setting up Amazon CloudFront Distribution can’t get any simple. Use Amazon Cloudfront as a <acronym title='Content Delivery Network'>CDN</acronym> for your WordPress Site. Create per site distribution for Multi-site setup. Let us know what features would you like to have in this plugin.


== Description ==
This plugin helps you setup your AWS CloudFront Distribution and serve static contents (Now supports WordPress Multisite setup). You can also use other CDNs which provides a custom CDN URL (E.G: cdn.YourAwesomeSite.com)

Special thanks to:
@techboomie 
@seocosenza

== Installation ==
= Using the WordPress Plugin Search =



1. Navigate to the `Add New` sub-page under the Plugins admin page.

2. Search for `AWS CDN By WPAdmin`.

3. The plugin should be listed first in the search results.

4. Click the `Install Now` link.

5. Lastly click the `Activate Plugin` link to activate the plugin.



= Uploading in WordPress Admin =



1. [Download the plugin zip file](https://downloads.wordpress.org/plugin/aws-cdn-by-wpadmin.1.4.9.zip) and save it to your computer.

2. Navigate to the `Add New` sub-page under the Plugins admin page.

3. Click the `Upload` link.

4. Select `aws-cdn-by-wpadmin` zip file from where you saved the zip file on your computer.

5. Click the `Install Now` button.

6. Lastly click the `Activate Plugin` link to activate the plugin.



= Using FTP =



1. [Download the plugin zip file](https://downloads.wordpress.org/plugin/aws-cdn-by-wpadmin.1.4.9.zip) and save it to your computer.

2. Extract the `aws-cdn-by-wpadmin` zip file.

3. Create a new directory named `aws-cdn-by-wpadmin` directory in the `../wp-content/plugins/` directory.

4. Upload the files from the folder extracted in Step 2.

4. Activate the plugin on the Plugins admin page.

== Frequently Asked Questions ==
 = CORS Error: No Access-Control-Allow-Origin header is present on the requested resource =
<h3>Apache</h3>
Add the following in your .htaccess file, immediately under '# END WordPress'
<code>
<FilesMatch "\.(ttf|ttc|otf|eot|woff|woff2|font.css)$">
<IfModule mod_headers.c>
Header add Access-Control-Allow-Origin "*"
Header set Access-Control-Allow-Origin "*"
</IfModule>
</FilesMatch>
</code>
<h3>Nginx</h3>
Add something like this to your vhost config
<code>
location ~* \.(eot|otf|ttf|woff|woff2)$ {
    add_header Access-Control-Allow-Origin *;
}
</code>
Refer to this article for more info: https://github.com/fontello/fontello/wiki/How-to-setup-server-to-serve-fonts
= How To Create An AWS User =
[Follow the steps in this article](https://wpadmin.ca/how-to-create-an-aws-user-with-limited-permissions-to-access-cloudfront-only/)


= Got a Question? =
[Send me an email](http://wpadmin.ca/contact-us/)

== Screenshots ==
1. screenshot-1.jpg
2. screenshot-2.jpg

== Changelog ==
V.1.4.9
Subtle donation Request.

V.1.4.8
Added option to exclude all Script & Stylesheet files.

V.1.4.7
Multi-site Domain list fixed on super-admin page.

V.1.4.6
Admin notice was being displayed on pages other than the intended pages, this has been fixed.

V.1.4.5
Added the filter to convert image srcset URLs to CDN URLs.

V.1.4.4
Tweaked and code cleaned for better performance..

V.1.4.3
The plugin now checks if the site is hosted in a sub-folder and populates the domain field.

V.1.4.2
Fixed the "error while validating the input provided for the CreateDistribution operation: [DistributionConfig][ViewerCertificate][ACMCertificateArn]" bug.

V.1.4.1
Added the option to specify location for sites hosted in a sub-folder.

V.1.4.0
The plugin can now requests a public SSL certificate from Amazon Certificate Manager, if you intend to use cdn.YourAwesome.Site. You can also exclude particular files to avoid CORS issue.

V.1.3.9
Disabled cdn domain names temporarily, to avoid the 'InvalidViewerCertificate' error.

V.1.3.8
Switched from SERVER_NAME to HTTP_HOST.

V.1.3.7
Existing users can now use the 'Modify' button to add CORS headers to AWS Cloudfront.

V.1.3.6
Minor tweaks and updates.

V.1.3.5
CORS Issue on HTTPS Site Fixed.

V.1.3.4
Minor tweaks and updates.

V.1.3.3
Minor tweaks and updates.

V.1.3.2
As reported on https://wordpress.org/support/topic/its-catching-all-scripts-but-skipping-images/
Added support to include both domain.name and www.domain.name

V.1.3.1
Tested with WordPress 5.0 added `wp-includes` to CDN.

V.1.3.0
Fixed the `Error loading stylesheet: An unknown error has occurred (805303f4)` error when loading stylesheets in sitemaps.

V.1.2.9
minor fixes to lock down access.

V.1.2.8
Added feature to modify the cloudfront distribution & create per site distribution for Multi-site setup

V.1.2.7
Added 'Send Debug Log to Developer' button in case the plugin fails.

V.1.2.6
Minor tweaks and updates.

V.1.2.5

Fixed the `POST https://cloudfront.amazonaws.com/2016-11-25/distribution resulted in a 404 Not Found` issue




V.1.2.4

Tested with WordPress Multisite


V.1.2.3

Fixed the duplicate \'cdn.\' in script and style tags when using custom cdn domain name



V.1.2.1
Tested with the latest version of WordPress

V.1.2
Tested with the latest version of WordPress



V.1.1


Updated AWS Phar and also added min/max Cache time fields

V.0.9

Fixed Object not found issue.


V.0.8

Fixed issue with GuzzleHttp\\Psr7\\ conflict.



V.0.7

Updated version for WordPress 4.5.1.



V.0.6

Invalidation  not required after updating Stylesheet.



V.0.5

Now rewrites all media (except logo)

== Upgrade Notice ==
Bugs & Improvements