=== PayPal Donations ===
Contributors: artstorm
Donate link: http://coding.cglounge.com/wordpress-plugins/paypal-donations/#pintware
Tags: paypal, donation, shortcode, widget, donate, button, sidebar
Requires at least: 2.7
Tested up to: 2.8
Stable tag: 1.2

Easy and simple setup and insertion of PayPal donate buttons with a shortcode or through a sidebar Widget. Donation purpose can be set for each button. A few other customization options are available as well.

== Description ==

Adds a PayPal donation shortcode and sidebar Widget to WordPress. The options menu lets you setup you PayPal ID and a few other optional settings. You can choose which donation button you want to use or if you want to use your own button. You can also set an optional default purpose and reference which can be overridden on each inserted instance with the shortcode options or in the Widget settings. There is also options available for currency, custom payment page style and the return page.

= Widget =

In the Appearance -> Widgets in WordPress 2.8 or newer you'll find the PayPal Donations widget. After adding it to your sidebar you can enter a title for the Widget, some descriptive text that will appear above the button and specify an optional purpose and reference for the donation button to override the default settings.

= Shortcode =

Insert the button in your pages or posts with this shortcode

`[paypal-donation]`

Which is the simplest option, and uses all default and optional settings. If you want to make a specific button for a specific purpose you can add these options:

`[paypal-donation purpose="Spline Importer" reference="3D Plugins"]`

as an example.

This donation plugin generates valid XHTML Transitional and Strict code.

For complete usage instructions see: [PayPal Donations](http://coding.cglounge.com/wordpress-plugins/paypal-donations/ "Complete Usage Instructions for PayPal Donations") 

== Installation ==

= Install =

1. Upload the 'paypal-donations' folder  to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings -> PayPal Donations and start entering your info.

= Uninstall =

1. Deactivate PayPal Donations in the 'Plugins' menu in Wordpress.
2. After Deactivation a 'Delete' link appears below the plugin name, follow the link and confim with 'Yes, Delete these files'.
3. This will delete all the plugin files from the server as well as erasing all options the plugin has stored in the database.

== Frequently Asked Questions ==

Please visit [PayPal Donations' Comments](http://coding.cglounge.com/wordpress-plugins/paypal-donations/#comments) for questions and answers.

== Screenshots ==

1. Admin page where you setup the plugin
1. Sidebar Widget Admin Options

== Changelog ==

= Version 1.2 - 18 June 2009 =
 * Added a sidebar Widget (WordPress 2.8 or newer)

= Version 1.1 - 23 May 2009 =
 * Added an option to select the currency to make the donations in.
 * Changed the default button in a new install from a custom button to the large button.

= Version 1.0 - 7 May 2009 =
 * Initial Release