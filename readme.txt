=== wp-login-logging ===
Contributors:  tarppa
Donate link: -
Tags: logging, authentication, log
Requires at least: 4.1
Tested up to: 4.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Write all login attempts to a logfile by replacing default wp_authenticate_username_password functionality

== Description ==

Write all login attempts where both username and password are supplied to a log file. The logfile is relative
to error.log and is called 'login.log', the format : "TIMESTAMP USERNAME SUCCESS/FAILURE".

In practice this plugin is a rewritten version of the wp_authenticate_username_password function from user.php and replaces
it in the login process.

== Installation ==

1. Upload `wp-login-logging.php` to the `/wp-content/plugins/wp-login-logging` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enjoy!

== Frequently Asked Questions ==

= Where is the logfile located? =

The plugin looks for error.log and places the logfile in the same directory

= What about other wp_authenticate_username_password functionality?  =

The other functionality stays the same.

== Screenshots ==

No screenshots

== Changelog ==

= 0.5 =
* First version 

== Upgrade Notice ==
-
