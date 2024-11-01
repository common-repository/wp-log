=== WP Log ===
Contributors: vahidd
Donate link: http://vahidd.com
Tags: wp log
Requires at least: 3.5
Tested up to: 3.5.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Log some actions in database or send it via email.

== Description ==

Wp log is simple plugin to save actions in database. Also actions can be sent to mail.
Wp log can record:
User Login
User Logout
User Register
User Delete
Post Edit
Post Delete
Theme Switch
New Comment
Comment Approved
Comment Unapproved
Plugin Activated
Plugin Deactivated

There is a useful option panel for plugin that let you to define which actions can be saved in database or send it in email.

Function:
wl_logs_action_row()
Return saved data in database. Return data is an array.

&#60;&#63;php echo wl_logs_action_row('action_name','ip_address','127.0.0.1','10','date','ASC'); &#63;&#62;

Parameter 1 is output data that can be:
* - everything
action_name - action name
ip_address - return ip address
date - date of the action
data - extra data (need to be unserialized)

Parameter 2 is filter key.

Prameter 2 is filter value

parameter 3 is displayed items count

Parameter 4 is items order by that can be date, ip_address, id

Parameter 5 is order that can be ASC or DESC

Samples:

Display latest actions from specific ip:
&#60;&#63;php echo wl_logs_action_row('action_name','ip_address','YOUR IP ADDRESS','10','date','ASC'); &#63;&#62;


Display every thing of latest actions:
&#60;&#63;php echo wl_logs_action_row('*'); &#63;&#62;



Display only name of latest actions:
&#60;&#63;php echo wl_logs_action_row('action_name'); &#63;&#62;



Display latest actions ip address:
&#60;&#63;php echo wl_logs_action_row('ip_address'); &#63;&#62;

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `&#60;&#63;php do_action('plugin_name_hook'); &#63;&#62;` in your templates

== Frequently asked questions ==

How this plugin saves data in db?

When you installed plugin it create a table in db for data. Also you can set which actions can be log in database.

Can i show some actions in my site?

Yes there is a powerful function to do this.

== Screenshots ==

1. 
2. 

== Changelog ==

Version 1.0
First Release

== Upgrade notice ==



== Arbitrary section 1 ==
