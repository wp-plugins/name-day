=== Plugin Name ===
Contributors: thomaz
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3625309
Tags: nameday, namnsdag, posts
Requires at least: 2.7.0
Tested up to: 2.7.1
Stable tag: 0.5

Print the current name day.

Support for the Swedish name Calendar.

== Description ==

Prints the current nameday (namnsdag in swedish)

== Currently only Swedish Name days =

* If you have a commasepareted file for namedays for your country, please
* contact me and I will add it.


== Installation ==

1. Upload plugin to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Edit your theme's php files and insert the following code where you want the name to appear.
   Please note that code isnt shown on the wordpress.org site, but is visible in your plugins readme file.

    <? if (function_exists('print_nameday')) { print_nameday(); } ?>


== Frequently Asked Questions ==

= Can you add support for more calendars =

Yes, just send me a datafile 




