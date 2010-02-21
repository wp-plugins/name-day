=== Plugin Name ===
Contributors: thomaz
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3625309
Tags: nameday, namnsdag, posts
Requires at least: 2.7.0
Tested up to: 2.9.2
Stable tag: 1.0.5


Print the current name day.

== Description ==

Print the current name day.

Currenly supports Namedays for Sweden (Namnsdagar), Norway (Navnedager) & Denmark (Navnedager) 



== Missing country? =

* If you have a commasepareted file for namedays for your country, please
* contact me and I will add it.


== Installation ==

1. Upload plugin to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the plugin throuh the settings page. (Language & Pre/Post text)
4. Edit your theme's php files and insert the following code where you want the name to appear.
   

<pre><code>    &lt;? if (function_exists('print_nameday')) { print_nameday(); } ?&gt; </code></pre>


== Frequently Asked Questions ==

= Can you add support for more calendars =

Yes, just send me a datafile 



== Screenshots ==

1. Settings page
2. Appearance in blog

== Changelog ==

= 1.0.5 = 
Redesign settings page

= 1.0.3 =
Cosmetic changes in settings page

= 1.0.2 =
1.0.2 Added support for Danish and Norwegian namedays

= 1.0.1 =
Removed dependencies to pluggable.php, which casued compat. problems.

