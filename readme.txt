=== Comments tweaks (disable comments, disable XML-RPC, disable self pings)  ===
Tags: disable comments, disable XML-RPC, remove comments, delete comments, no self pings, wp disable, disable pingback comments, comments manager, webcraftic update manager, clearfy, replace external links, remove comment form, comment form, remove comment form fields, bulk comments management, spam comments cleaner, delete comments by status, no page comment, wp disable comments
Contributors: webcraftic
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VDX7JNTQPNPFW
Requires at least: 4.2
Tested up to: 4.9
Requires PHP: 5.2
Stable tag: trunk
License: GPLv2

Allows administrators to disable comments on their website. Comments can be disabled according to Post, Page, Media type.

== Description ==

<strong>Disable comments</strong> is a useful tool for administrators to manage comments.
Our plugin allows administrators to fully disable or hide the comments on the site in all posts, pages, attachments.
It remove all comment related features and options.  All related items in the admin panel will be removed (from the menu, dashboard, widgets).

Completely <strong>disables all XML-RPC</strong> related functions in WordPress including pingbacks and trackbacks, and helps prevent attacks on the xmlrpc.php file. Lastly, it attempts to generate a 403 Denied error for requests to the /xmlrpc.php URL, but does not affect that file or your server in any way.

Superfluous external links from comments, which can be typed from a dozen and more for one article, do not bring anything good for promotion. Plugin Disable comments replaces the links of this kind of a href="http://yourdomain.com" rel="nofollow", on links of this kind span data-uri="http://yourdomain.com"

Plugin <strong>Disabled comments</strong> uses intelligent algorithm to <strong>hide comments</strong> and <strong>delete comments</strong> you just need to do a couple of options.
It’s makes it very simple.


****

If you come across any bugs or have suggestions, please use the plugin support forum. I can't fix it if I don't know it's broken! Please check the [FAQ](https://wordpress.org/plugins/comments-plus/faq/) for common issues.

== Frequently Asked Questions ==

= Nothing happens after I disable comments on all posts - comment forms still appear when I view my posts. =

This is because your theme is not checking the comment status of posts in the correct way.

You may like to point your theme's author to [this explanation](http://www.rayofsolaris.net/blog/2012/how-to-check-if-comments-are-allowed-in-wordpress/) of what they are doing wrong, and how to fix it.

= How can I remove the text that says "comments are closed" at the bottom of articles where comments are disabled? =

The plugin tries its very best to hide this (and any other comment-related) messages.

If you still see the message, then it means your theme is overriding this behaviour, and you will have to edit its files manually to remove it. Two common approaches are to either delete or comment out the relevant lines in `wp-content/your-theme/comments.php`, or to add a declaration to `wp-content/your-theme/style.css` that hides the message from your visitors. In either case, make you you know what you are doing!

= I only want to disable comments on certain posts, not globally. What do I do? =

Don't install this plugin!

Go to the edit page for the post you want to disable comments on. Scroll down to the "Discussion" box, where you will find the comment options for that post. If you don't see a "Discussion" box, then click on "Screen Options" at the top of your screen, and make sure the "Discussion" checkbox is checked.

You can also bulk-edit the comment status of multiple posts from the [posts screen](https://codex.wordpress.org/Posts_Screen).

= I want to delete comments from my database. What do I do? =

If you change the plugin settings, you will be prompted to delete comments from the database.

== Details ==

The plugin provides the option to **completely disable the commenting feature in WordPress**. When this option is selected, the following changes are made:

* <strong>Easy Enable or disable Comments </strong>
* <strong>Disable comments globally</strong>
* <strong>Disable comments on certain Pages</strong>
* <strong>Disable comments on posts Only</strong>
* <strong>Disable comments on pages Only</strong>
* <strong>Disable comments links in the Admin Menu and Admin Bar;</strong>
* <strong>Disable comments related sections ("Recent Comments", "Discussion" etc.) and hidden from the WordPress Dashboard;</strong>
* <strong>Disable comments related widgets (so your theme cannot use them);</strong>
* <strong>Disable comments "Discussion" settings page is hidden;</strong>
* <strong>Disable comments in RSS/Atom feeds (and requests for these will be redirected to the parent post);</strong>
* <strong>Disable X-Pingback HTTP header and removed from all pages;</strong>
* <strong>Disable outgoing pingbacks.</strong>
* <strong>Replace external links</strong>
* <strong>Remove comment form fields</strong>
* <strong>Remove comments, Delete comments</strong>

We recently added new features in the plugin to disable comments. This is a <strong>Disable X-Pingback</strong> function,  <strong>Replace external links</strong>,  <strong>Remove comment form fields</strong>.

Some functions are taken from the following popular plugins <strong>Clearfy – disable unused features</strong>, <strong>Bulk Comments Management</strong>, <strong>Spam Comments Cleaner</strong>, <strong>Delete Comments By Status</strong>, <strong>No Page Comment</strong>, <strong>WP Disable Comments</strong>, <strong>Hide "Comments are closed"</strong>, <strong>Hide Show Comment</strong>

== Advanced Configuration ==

Some of the plugin's behaviour can be modified by site administrators and plugin/theme developers through code:

* Define `DISABLE_COMMENTS_REMOVE_COMMENTS_TEMPLATE` and set it to `false` to prevent the plugin from replacing the theme's comment template with an empty one.

These definitions can be made either in your main `wp-config.php` or in your theme's `functions.php` file.

== Translations ==

* English - default, always included
* French - Thank you very much to user (kingteamdunet)
* Russian

If you want to help with the translation, please contact me through this site or through the contacts inside the plugin.

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. The plugin settings can be accessed via the 'Settings' menu in the administration area (either your site administration for single-site installs).

== Screenshots ==
1. Control panel

== Changelog ==
= 1.0.4 =
* Update plugin core
* Fixed bug reduced plugin weight.
* Fixed JS error with external links option.

= 1.0.3 =
* Update plugin core

= 1.0.2 =
* Fixed a bug where you selected the recommended mode, on some pages you see a white screen. Now you will not encounter this error.

= 1.0.1 =
* Plugin release
