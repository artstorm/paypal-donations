<?php
/*
Plugin Name: PayPal Donations
Plugin URI: http://coding.cglounge.com/wordpress-plugins/paypal-donations/
Description: Easy and simple setup and insertion of PayPal donate buttons with a shortcode. Donation purpose can be set for each button. A few other customization options are available as well.
Version: 1.0
Author: Johan Steen
Author URI: http://coding.cglounge.com/
Text Domain: paypal-donations 

Copyright 2009  Johan Steen  (email : artstorm [at] gmail [dot] com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


class paypal_donations {
	var $plugin_options = "paypal_donations_options";
	var $donate_buttons = array("small" => "https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif",
						   "large" => "https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif",
						   "cards" => "https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif");
	/**
	* Constructor
	*
	*/
	function paypal_donations()
	{
		// define URL
		define('paypal_donations_ABSPATH', WP_PLUGIN_DIR.'/'.plugin_basename( dirname(__FILE__) ).'/' );
		define('paypal_donations_URLPATH', WP_PLUGIN_URL.'/'.plugin_basename( dirname(__FILE__) ).'/' );

		// Define the domain for translations
		load_plugin_textdomain(	'paypal-donations', false, dirname(plugin_basename(__FILE__)) . '/languages/');

		// Check installed Wordpress version.
		global $wp_version;
		if ( version_compare($wp_version, '2.7', '>=') ) {
//			include_once (dirname (__FILE__)."/tinymce/tinymce.php");
			$this->init_hooks();
		} else {
			$this->version_warning();
		}
	}

	/**
	* Initializes the hooks for the plugin
	*
	* @returns	Nothing
	*/
	function init_hooks() {
		add_action('admin_menu', array(&$this,'wp_admin'));
		add_shortcode('paypal-donation', array(&$this,'paypal_shortcode'));
	}
	
	/**
	* Displays a warning when installed in an old Wordpress Version
	*
	* @returns	Nothing
	*/
	function version_warning() {
		echo '<div class="updated fade"><p><strong>'.__('PayPal Donations requires WordPress version 2.7 or later!', 'paypal-donations').'</strong></p></div>';
	}
	
	/**
	* Create and register the PayPal shortcode
	*
	*/
	function paypal_shortcode($atts) {
		$pd_options = get_option($this->plugin_options);
		extract(shortcode_atts(array(
			'purpose' => $pd_options['purpose'],
			'reference' => $pd_options['reference'],
		), $atts));

		$paypal_btn =	'<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="donate">';
		$paypal_btn .=	'<div class="paypal-donations">';
		$paypal_btn .=	'<input type="hidden" name="cmd" value="_donations" />';
		$paypal_btn .=	'<input type="hidden" name="business" value="' .$pd_options['paypal_account']. '" />';

		// Optional Settings
		if ($pd_options['page_style'])
			$paypal_btn .=	'<input type="hidden" name="page_style" value="' .$pd_options['page_style']. '" />';
		if ($pd_options['return_page'])
			$paypal_btn .=	'<input type="hidden" name="return" value="' .$pd_options['return_page']. '" />'; // Return Page
		if ($purpose)
			$paypal_btn .=	'<input type="hidden" name="item_name" value="' .$purpose. '" />';	// Purpose
		if ($reference)
			$paypal_btn .=	'<input type="hidden" name="item_number" value="' .$reference. '" />';	// LightWave Plugin
		
		// Settings not implemented yet
		//		$paypal_btn .=     '<input type="hidden" name="currency_code" value="SEK" />';
		//		$paypal_btn .=     '<input type="hidden" name="amount" value="20" />';

		// Get the button URL
		if ( $pd_options['button'] == "custom" )
			$button_url = $pd_options['button_url'];
		else
			$button_url = $this->donate_buttons[$pd_options['button']];

		$paypal_btn .=	'<input type="image" src="' .$button_url. '" name="submit" alt="PayPal - The safer, easier way to pay online." />';
		$paypal_btn .=	'<img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />';
		$paypal_btn .=	'</div>';
		$paypal_btn .=	'</form>';
	
		return $paypal_btn;
	}
	
	/**
	* The Admin Page and all it's functions
	*
	*/
	function wp_admin()	{
		if (function_exists('add_options_page')) {
			add_options_page( 'PayPal Donations Options', 'PayPal Donations', 10, __FILE__, array(&$this, 'options_page') );
		}
	}

	function admin_message($message) {
		if ( $message ) {
			?>
			<div class="updated"><p><strong><?php echo $message; ?></strong></p></div>
			<?php	
		}
	}

	function options_page() {
		// Update Options
		if (isset($_POST['Submit'])) {
			$pd_options['paypal_account'] = trim( $_POST['paypal_account'] );
			$pd_options['page_style'] = trim( $_POST['page_style'] );
			$pd_options['return_page'] = trim( $_POST['return_page'] );
			$pd_options['purpose'] = trim( $_POST['purpose'] );
			$pd_options['reference'] = trim( $_POST['reference'] );
			$pd_options['button'] = trim( $_POST['button'] );
			$pd_options['button_url'] = trim( $_POST['button_url'] );
			update_option($this->plugin_options, $pd_options);
			$this->admin_message( __( 'The PayPal Donations settings have been updated.', 'paypal-donations' ) );
		}
?>
<div class=wrap>
    <h2>PayPal Donations</h2>

	<form method="post" action="">
	<?php wp_nonce_field('update-options'); ?>
	<?php $pd_options = get_option($this->plugin_options); ?>
    <table class="form-table">
    <tr valign="top">
    <th scope="row"><label for="paypal_account"><?php _e( 'PayPal Account', 'paypal-donations' ) ?></label></th>
    <td><input name="paypal_account" type="text" id="paypal_account" value="<?php echo $pd_options['paypal_account']; ?>" class="regular-text" /><span class="setting-description"><br/><?php _e( 'Your PayPal email address or your PayPal secure merchant account ID.', 'paypal-donations' ) ?></span></td>
    </tr>
    </table>

	<h3><?php _e( 'Optional Settings', 'paypal-donations' ) ?></h3>
    <table class="form-table">
    <tr valign="top">
    <th scope="row"><label for="page_style"><?php _e( 'Page Style', 'paypal-donations' ) ?></label></th>
    <td><input name="page_style" type="text" id="page_style" value="<?php echo $pd_options['page_style']; ?>" class="regular-text" /><span class="setting-description"><br/><?php _e( 'Specify the name of a custom payment page style from your PayPal account profile.', 'paypal-donations' ) ?></span></td>
    </tr>
    <tr valign="top">
    <th scope="row"><label for="return_page"><?php _e( 'Return Page', 'paypal-donations' ) ?></label></th>
    <td><input name="return_page" type="text" id="return_page" value="<?php echo $pd_options['return_page']; ?>" class="regular-text" /><span class="setting-description"><br/><?php _e( 'URL to which the donator comes to after completing the donation; for example, a URL on your site that displays a "Thank you for your donation".', 'paypal-donations' ) ?></span></td>
    </tr>    
    </table>

	<h3><?php _e( 'Defaults', 'paypal-donations' ) ?></h3>
    <table class="form-table">
    <tr valign="top">
    <th scope="row"><label for="purpose"><?php _e( 'Purpose', 'paypal-donations' ) ?></label></th>
    <td><input name="purpose" type="text" id="purpose" value="<?php echo $pd_options['purpose']; ?>" class="regular-text" /><span class="setting-description"><br/><?php _e( 'The default purpose of a donation (Optional).', 'paypal-donations' ) ?></span></td>
    </tr>
    <tr valign="top">
    <th scope="row"><label for="reference"><?php _e( 'Reference', 'paypal-donations' ) ?></label></th>
    <td><input name="reference" type="text" id="reference" value="<?php echo $pd_options['reference']; ?>" class="regular-text" /><span class="setting-description"><br/><?php _e( 'Default reference for the donation (Optional).', 'paypal-donations' ) ?></span></td>
    </tr>    
    </table>

	<h3><?php _e( 'Donation Button', 'paypal-donations' ) ?></h3>
    <table class="form-table">
    <tr>
	<th scope="row"><?php _e( 'Select Button', 'paypal-donations' ) ?></th>
	<td>
	<fieldset><legend class="hidden">PayPal Button</legend>
<?php
	$custom = TRUE;
	foreach ( $this->donate_buttons as $key => $button ) {
		echo "\t<label title='" . attribute_escape($key) . "'><input style='padding: 10px 0 10px 0;' type='radio' name='button' value='" . attribute_escape($key) . "'";
		if ( $pd_options['button'] === $key ) { // checked() uses "==" rather than "==="
			echo " checked='checked'";
			$custom = FALSE;
		}
		echo " /> <img src='" . $button . "' alt='" . $key  . "' style='vertical-align: middle;' /></label><br /><br />\n";
	}

	echo '	<label><input type="radio" name="button" value="custom"';
	checked( $custom, TRUE );
	echo '/> ' . __('Custom Button:') . ' </label>';
?>
	<input type="text" name="button_url" value="<?php echo $pd_options['button_url']; ?>" class="regular-text" />
	<p><span class="setting-description"><?php _e( 'Enter a URL to a custom donation button.', 'paypal-donations' ) ?></span></p>
	</fieldset>
	</td>
	</tr>
    </table>

    <p class="submit">
    <input type="submit" name="Submit" class="button-primary" value="<?php _e( 'Save Changes', 'paypal-donations' ) ?>" />
    </p>
</div>
<?php
	}
}
add_action( 'plugins_loaded', create_function( '', 'global $paypal_donations; $paypal_donations = new paypal_donations();' ) );
?>