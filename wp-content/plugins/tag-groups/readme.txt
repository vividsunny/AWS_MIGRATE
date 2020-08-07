=== Tag Groups ===
Contributors: camthor
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=FD5ZU4EEBGSC8
Tags: alphabet, tags, tag cloud, tabs, accordion, taxonomy, tag, woocommerce, cloud
Requires at least: 4.4
Tested up to: 5.3.1
Stable tag: 1.22.4
Requires PHP: 5.6
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

== Description ==

Organize your WordPress tags in groups. Use highly customizable tag clouds (tabs, accordion or alphabet) in a post, page or widget.

== Finally, the end of messy tags ==

Organize your tags in groups

* Display tag clouds where tags are sorted into **smaller, manageable groups**.
* **Filter posts and tags** by tag group in your admin.

Sort tags alphabetically

* Automatically sort your tags in a tag cloud by their **first letters**.

== Why you need tag groups ==

Most websites mix up tags that should actually be separated by topics: places, people, features, activities and more.

Your tags, however, will become much more useful if you organize them in groups. Imagine a tag cloud where all town names appear in a "Towns" group or where you keep all tags for product sizes under "Sizes" and all tags for colors under "Colors". The Tag Groups plugin lets you do exactly that.

== How to use tag groups ==

After activating the plugin, you will be guided by an optional **setup wizard**.

First you create groups that will contain your tags. Then you sort your tags into these groups. You can later always come back and modify the groups or change the tags.

The plugin comes with two tag clouds where tags are sorted by groups and one alphabetical tag cloud where tags are sorted by first letters. These tag clouds can be customized with numerous parameters, and you can insert them as shortcodes into post, pages and widgets or as Gutenberg blocks.

Filters and bulk actions make your work with tags much easier, and you can even filter the list of posts in your backend by the groups that their tags belong to.

== Not only tags ==

Tag Groups works also great with most other (flat) taxonomies, like WooCommerce product tags.

The plugin does not support hierarchical taxonomies like categories.


== Possible applications ==

* Display your tags grouped by language or by topic.
* Create an alphabetical index of your tags - think of a phone book for your tags.
* Display only specific tags in your tag cloud.
* Choose which tags to display in different sections of your blog.
* Change the sorting order in your tag cloud.
* Customize the links, the text, the font size or the separator of your tag cloud items.
* Prepend or append to each tag a custom character or the post count.
* Insert into each post a tag cloud using only the tags of this post.
* Easily manage huge amounts of tags or posts in the backend by dividing them into groups.
* Add a parent level to tags and make them hierarchical.


== Multilingual websites ==

This plugin is officially compatible with **WPML** (WordPress Multilingual). That means you can use a different tag group name for each language.

Please find more information on the [plugin website](https://chattymango.com/tag-groups/?pk_campaign=tg&pk_kwd=readme "plugin website").

**If you find this plugin useful, please give it a [5-star rating](https://wordpress.org/support/plugin/tag-groups/reviews/?filter=5 "reviews"). Thank you!**


> ### Extra Features
>
> If you want to get more out of your tag groups, check out [Tag Groups Premium](https://chattymango.com/tag-groups-premium/?pk_campaign=tg&pk_kwd=readme "plugin website"). The premium plugin comes with
>
> * an animated and searchable tag cloud: Click a group or type a tag name and see only matching tags
> * a Dynamic Post Filter for the frontend: Your visitors can pick tags from groups and see all matching posts in a list;
> * a meta box for the post edit screen so that you can enter and edit post tags segmented by groups;
> * the option to bulk-add all tags of a group to a post with one click;
> * add the same tag to multiple groups;
> * custom permissions who can edit tag groups;
> * show tags in groups under your posts;
> * a new tag cloud where you can combine the tags of specific groups into one cloud;
> * and more.

https://www.youtube.com/watch?v=xonGSR9VswQ

> See the difference between the free and the premium plugin [in the feature comparison table](https://chattymango.com/tag-groups-base-premium-comparison/?pk_campaign=tg&pk_kwd=readme "feature comparison table").
>
> You can test the plugin in a 7-day free trial.

Follow us on [Facebook](https://www.facebook.com/chattymango/) or [Twitter](https://twitter.com/ChattyMango).

= Other Notes =

Find the licenses of 3rd party software in the About section.

== Installation ==

1. Find the plugin in the list at the backend and click to install it. Or, upload the ZIP file through the admin backend. Or, upload the unzipped tag-groups folder to the /wp-content/plugins/ directory.

2. Activate the plugin through the ‘Plugins’ menu in WordPress.

The plugin will create a new menu "Tag Groups" and a submenu "Tag Group Admin" in the Post section (depending on the chosen taxonomy) where you find the tag groups. After you have created some groups, you can edit your tags (or other terms) and assign them to one of these groups. A filter and a bulk action menu are available on the Tags page and you also find a filter on the Posts pages.

The tabbed tag cloud (or an accordion containing the tags) can be inserted with a shortcode or a Gutenberg block. Options are listed under the "Tag Groups" main menu.

Extensive information, examples and help for troubleshooting are listed [here](https://chattymango.com/tag-groups/?pk_campaign=tg&pk_kwd=readme "plugin website").


== Frequently Asked Questions ==

= How can I show my tags sorted into groups on a page? =

Please use one of the shortcodes or Gutenberg blocks that come with the Tag Groups plugin. Since we cannot change the code of templates or other plugins, there is no possibility to make them aware of the new structure where tags are organized in groups.

= When I use the shortcode I can see the content but the tags are not displayed in tabs. =

Make sure you have "Use jQuery" checked on the settings page. If you use a plugin for caching pages, purge the cache and see if that helps. If you use plugins for minifying scripts or style sheets, turn them off and purge their caches.

= How can I use one of these tag clouds in a widget? =

Please use a text widget and insert the shortcode.

= Does this plugin support tags for pages? =

No. Although it might work (with additional 3rd-party plugins), it is not an officially supported feature.

= Does this plugin work with categories? =

No. Hierarchical taxonomies such as categories are not officially supported.

= Does this plugin work with my special custom taxonomy XY? =

We cannot tell unless you try.

= I need the plugin for a very special purpose - can you help? =

Please check first if the [premium plugin](https://chattymango.com/tag-groups-premium/?pk_campaign=tg&pk_kwd=readme "Tag Groups Premium") can help. If not, you can ask for [support here](https://wordpress.org/support/plugin/tag-groups).

= I am desperately missing my language. But, wait.. is this actually a "frequently asked question"? =

No, unfortunately it isn't. But, nevertheless, I'm glad you asked! You are warmly invited to [help us translate](https://translate.wordpress.org/projects/wp-plugins/tag-groups).

== Screenshots ==

1. Tag cloud ("Blitzer" theme)
2. Accordion ("Blitzer" theme)
3. Alphabetical tag cloud with first letters of tags as tabs
4. Group administration
5. Assigning tags to groups
6. Settings
7. Configuring a tag cloud in Gutenberg

== Privacy ==

This plugin does not collect or process any personal user data unless you expressively opt-in.

== Changelog ==


= 1.22.4 =

OTHER

* Improved resilience against Ajax errors

= 1.22.1 =

BUG FIXES

* Fixed wrong reference of styling for Gutenberg

OTHER

* Improved filter menus on posts page (@ebabinet)
* Updated Freemius' SDK

= 1.22.0 =

OTHER

* Updated Freemius SDK to version 2.3.0

= 1.21.4 =

OTHER

* Improved compatibility with the Polylang plugin.

= 1.21.3 =

BUG FIXES

* Fixed: Post counts of tags in tag clouds were not respecting the particular group.

= 1.21.0 =

FEATURES

* Added two more themes to the defaults (for the tabbed and the accordion tag clouds).

= 1.20.3 =

BUG FIXES

* Fixed wrong positions of new groups after their creation.
* Preventing occasional error messages when creating terms.

OTHER

* Added an additional consistency check of labels.
* Show correct greeting message during onboarding.

= 1.20.1 =

BUG FIXES

* Fixed issue when assigning tag to group.

= 1.20.0 =

FEATURES

* New Setup Wizard (launches automatically, or search for "wizard" in the settings)

BUG FIXES

* Fixed issues in uninstall routine.

OTHER

* Alphabetical Tag Cloud: Fix for uppercasing of ß in Unicode canceling out all letters S


= Older Versions =

The complete changelog is available [here](https://chattymango.com/tag-groups/tag-groups-changelog/?pk_campaign=tg&pk_kwd=readme).



== Upgrade Notice ==

none
