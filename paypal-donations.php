<?php
/*
Plugin Name: PayPal Donations
Plugin URI: http://coding.cglounge.com/wordpress-plugins/paypal-donations/
Description: Easy and simple setup and insertion of PayPal donate buttons with a shortcode or through a sidebar Widget. Donation purpose can be set for each button. A few other customization options are available as well.
Version: 1.4
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
	var $plugin_options = 'paypal_donations_options';
	var $donate_buttons = array('small' => 'https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif',
						  		'large' => 'https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif',
						  		'cards' => 'https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif');
	var $currency_codes = array('AUD' => 'Australian Dollars (A $)',
						   		'CAD' => 'Canadian Dollars (C $)',
						   		'EUR' => 'Euros (&euro;)',
						   		'GBP' => 'Pounds Sterling (&pound;)',
						   		'JPY' => 'Yen (&yen;)',
						   		'USD' => 'U.S. Dollars ($)',
						   		'NZD' => 'New Zealand Dollar ($)',
						   		'CHF' => 'Swiss Franc',
						   		'HKD' => 'Hong Kong Dollar ($)',
						   		'SGD' => 'Singapore Dollar ($)',
						   		'SEK' => 'Swedish Krona',
						   		'DKK' => 'Danish Krone',
						   		'PLN' => 'Polish Zloty',
						   		'NOK' => 'Norwegian Krone',
						   		'HUF' => 'Hungarian Forint',
						   		'CZK' => 'Czech Koruna',
						   		'ILS' => 'Israeli Shekel',
						   		'MXN' => 'Mexican Peso');
	var $localized_buttons = array('en_AL' => 'Albania - U.K. English',
								   'en_DZ' => 'Algeria - U.K. English',
								   'en_AD' => 'Andorra - U.K. English',
								   'en_AO' => 'Angola - U.K. English',
								   'en_AI' => 'Anguilla - U.K. English',
								   
								   'en_AG' => 'Antigua and Barbuda - U.K. English',
								   'en_AR' => 'Argentina - U.K. English',
								   'en_AM' => 'Armenia - U.K. English',
								   'en_AW' => 'Aruba - U.K. English',
								   'en_AU' => 'Australia - Australian English',
								   'de_AT' => 'Austria - German',
								   'en_AT' => 'Austria - U.S. English',
								   'en_AZ' => 'Azerbaijan Republic - U.K. English',
								   'en_BS' => 'Bahamas - U.K. English',
								   
								   'en_BH' => 'Bahrain - U.K. English',
								   'en_BB' => 'Barbados - U.K. English',
								   'en_BE' => 'Belgium - U.S. English',
								   'nl_BE' => 'Belgium - Dutch',
								   'fr_BE' => 'Belgium - French',
								   'en_BZ' => 'Belize - U.K. English',
								   'en_BJ' => 'Benin - U.K. English',
								   'en_BM' => 'Bermuda - U.K. English',
								   'en_BT' => 'Bhutan - U.K. English',
								   
								   'en_BO' => 'Bolivia - U.K. English',
								   'en_BA' => 'Bosnia and Herzegovina - U.K. English',
								   'en_BW' => 'Botswana - U.K. English',
								   'en_BR' => 'Brazil - U.K. English',
								   'en_VG' => 'British Virgin Islands - U.K. English',
								   'en_BN' => 'Brunei - U.K. English',
								   'en_BG' => 'Bulgaria - U.K. English',
								   'en_BF' => 'Burkina Faso - U.K. English',
								   'en_BI' => 'Burundi - U.K. English',
								   
								   'en_KH' => 'Cambodia - U.K. English',
								   'en_CA' => 'Canada - U.S. English',
								   'fr_CA' => 'Canada - French',
								   'en_CV' => 'Cape Verde - U.K. English',
								   'en_KY' => 'Cayman Islands - U.K. English',
								   'en_TD' => 'Chad - U.K. English',
								   'en_CL' => 'Chile - U.K. English',
								   'en_C2' => 'China - U.S. English',
								   'zh_C2' => 'China - Simplified Chinese',
								   
								   'en_CO' => 'Colombia - U.K. English',
								   'en_KM' => 'Comoros - U.K. English',
								   'en_CK' => 'Cook Islands - U.K. English',
								   'en_CR' => 'Costa Rica - U.K. English',
								   'en_HR' => 'Croatia - U.K. English',
								   'en_CY' => 'Cyprus - U.K. English',
								   'en_CZ' => 'Czech Republic - U.K. English',
								   'en_CD' => 'Democratic Republic of the Congo - U.K. English',
								   'en_DK' => 'Denmark - U.K. English',
								   
								   'en_DJ' => 'Djibouti - U.K. English',
								   'en_DM' => 'Dominica - U.K. English',
								   'en_DO' => 'Dominican Republic - U.K. English',
								   'en_EC' => 'Ecuador - U.K. English',
								   'en_SV' => 'El Salvador - U.K. English',
								   'en_ER' => 'Eritrea - U.K. English',
								   'en_EE' => 'Estonia - U.K. English',
								   'en_ET' => 'Ethiopia - U.K. English',
								   'en_FK' => 'Falkland Islands - U.K. English',
								   
								   'en_FO' => 'Faroe Islands - U.K. English',
								   'en_FM' => 'Federated States of Micronesia - U.K. English',
								   'en_FJ' => 'Fiji - U.K. English',
								   'en_FI' => 'Finland - U.K. English',
								   'fr_FR' => 'France - French',
								   'en_FR' => 'France - U.S. English',
								   'en_GF' => 'French Guiana - U.K. English',
								   'en_PF' => 'French Polynesia - U.K. English',
								   'en_GA' => 'Gabon Republic - U.K. English',
								   
								   'en_GM' => 'Gambia - U.K. English',
								   'de_DE' => 'Germany - German',
								   'en_DE' => 'Germany - U.S. English',
								   'en_GI' => 'Gibraltar - U.K. English',
								   'en_GR' => 'Greece - U.K. English',
								   'en_GL' => 'Greenland - U.K. English',
								   'en_GD' => 'Grenada - U.K. English',
								   'en_GP' => 'Guadeloupe - U.K. English',
								   'en_GT' => 'Guatemala - U.K. English',
								   
								   'en_GN' => 'Guinea - U.K. English',
								   'en_GW' => 'Guinea Bissau - U.K. English',
								   'en_GY' => 'Guyana - U.K. English',
								   'en_HN' => 'Honduras - U.K. English',
								   'zh_HK' => 'Hong Kong - Traditional Chinese',
								   'en_HK' => 'Hong Kong - U.K. English',
								   'en_HU' => 'Hungary - U.K. English',
								   'en_IS' => 'Iceland - U.K. English',
								   'en_IN' => 'India - U.K. English',
								   
								   'en_ID' => 'Indonesia - U.K. English',
								   'en_IE' => 'Ireland - U.K. English',
								   'en_IL' => 'Israel - U.K. English',
								   'it_IT' => 'Italy - Italian',
								   'en_IT' => 'Italy - U.S. English',
								   'en_JM' => 'Jamaica - U.K. English',
								   'ja_JP' => 'Japan - Japanese',
								   'en_JP' => 'Japan - U.S. English',
								   'en_JO' => 'Jordan - U.K. English',
								   
								   'en_KZ' => 'Kazakhstan - U.K. English',
								   'en_KE' => 'Kenya - U.K. English',
								   'en_KI' => 'Kiribati - U.K. English',
								   'en_KW' => 'Kuwait - U.K. English',
								   'en_KG' => 'Kyrgyzstan - U.K. English',
								   'en_LA' => 'Laos - U.K. English',
								   'en_LV' => 'Latvia - U.K. English',
								   'en_LS' => 'Lesotho - U.K. English',
								   'en_LI' => 'Liechtenstein - U.K. English',
								   
								   'en_LT' => 'Lithuania - U.K. English',
								   'en_LU' => 'Luxembourg - U.K. English',
								   'en_MG' => 'Madagascar - U.K. English',
								   'en_MW' => 'Malawi - U.K. English',
								   'en_MY' => 'Malaysia - U.K. English',
								   'en_MV' => 'Maldives - U.K. English',
								   'en_ML' => 'Mali - U.K. English',
								   'en_MT' => 'Malta - U.K. English',
								   'en_MH' => 'Marshall Islands - U.K. English',
								   
								   'en_MQ' => 'Martinique - U.K. English',
								   'en_MR' => 'Mauritania - U.K. English',
								   'en_MU' => 'Mauritius - U.K. English',
								   'en_YT' => 'Mayotte - U.K. English',
								   'es_MX' => 'Mexico - Spanish',
								   'en_MX' => 'Mexico - U.S. English',
								   'en_MN' => 'Mongolia - U.K. English',
								   'en_MS' => 'Montserrat - U.K. English',
								   'en_MA' => 'Morocco - U.K. English',
								   
								   'en_MZ' => 'Mozambique - U.K. English',
								   'en_NA' => 'Namibia - U.K. English',
								   'en_NR' => 'Nauru - U.K. English',
								   'en_NP' => 'Nepal - U.K. English',
								   'nl_NL' => 'Netherlands - Dutch',
								   'en_NL' => 'Netherlands - U.S. English',
								   'en_AN' => 'Netherlands Antilles - U.K. English',
								   'en_NC' => 'New Caledonia - U.K. English',
								   'en_NZ' => 'New Zealand - U.K. English',
								   
								   'en_NI' => 'Nicaragua - U.K. English',
								   'en_NE' => 'Niger - U.K. English',
								   'en_NU' => 'Niue - U.K. English',
								   'en_NF' => 'Norfolk Island - U.K. English',
								   'en_NO' => 'Norway - U.K. English',
								   'en_OM' => 'Oman - U.K. English',
								   'en_PW' => 'Palau - U.K. English',
								   'en_PA' => 'Panama - U.K. English',
								   'en_PG' => 'Papua New Guinea - U.K. English',
								   
								   'en_PE' => 'Peru - U.K. English',
								   'en_PH' => 'Philippines - U.K. English',
								   'en_PN' => 'Pitcairn Islands - U.K. English',
								   'pl_PL' => 'Poland - Polish',
								   'en_PL' => 'Poland - U.S. English',
								   'en_PT' => 'Portugal - U.K. English',
								   'en_QA' => 'Qatar - U.K. English',
								   'en_CG' => 'Republic of the Congo - U.K. English',
								   'en_RE' => 'Reunion - U.K. English',
								   
								   'en_RO' => 'Romania - U.K. English',
								   'en_RU' => 'Russia - U.K. English',
								   'en_RW' => 'Rwanda - U.K. English',
								   'en_VC' => 'Saint Vincent and the Grenadines - U.K. English',
								   'en_WS' => 'Samoa - U.K. English',
								   'en_SM' => 'San Marino - U.K. English',
								   'en_ST' => 'São Tomé and Príncipe - U.K. English',
								   'en_SA' => 'Saudi Arabia - U.K. English',
								   'en_SN' => 'Senegal - U.K. English',
								   
								   'en_SC' => 'Seychelles - U.K. English',
								   'en_SL' => 'Sierra Leone - U.K. English',
								   'en_SG' => 'Singapore - U.K. English',
								   'en_SK' => 'Slovakia - U.K. English',
								   'en_SI' => 'Slovenia - U.K. English',
								   'en_SB' => 'Solomon Islands - U.K. English',
								   'en_SO' => 'Somalia - U.K. English',
								   'en_ZA' => 'South Africa - U.K. English',
								   'en_KR' => 'South Korea - U.K. English',
								   
								   'es_ES' => 'Spain - Spanish',
								   'en_ES' => 'Spain - U.S. English',
								   'en_LK' => 'Sri Lanka - U.K. English',
								   'en_SH' => 'St. Helena - U.K. English',
								   'en_KN' => 'St. Kitts and Nevis - U.K. English',
								   'en_LC' => 'St. Lucia - U.K. English',
								   'en_PM' => 'St. Pierre and Miquelon - U.K. English',
								   'en_SR' => 'Suriname - U.K. English',
								   'en_SJ' => 'Svalbard and Jan Mayen Islands - U.K. English',
								   
								   'en_SZ' => 'Swaziland - U.K. English',
								   'en_SE' => 'Sweden - U.K. English',
								   'de_CH' => 'Switzerland - German',
								   'fr_CH' => 'Switzerland - French',
								   'en_CH' => 'Switzerland - U.S. English',
								   'en_TW' => 'Taiwan - U.K. English',
								   'en_TJ' => 'Tajikistan - U.K. English',
								   'en_TZ' => 'Tanzania - U.K. English',
								   'en_TH' => 'Thailand - U.K. English',
								   
								   'en_TG' => 'Togo - U.K. English',
								   'en_TO' => 'Tonga - U.K. English',
								   'en_TT' => 'Trinidad and Tobago - U.K. English',
								   'en_TN' => 'Tunisia - U.K. English',
								   'en_TR' => 'Turkey - U.K. English',
								   'en_TM' => 'Turkmenistan - U.K. English',
								   'en_TC' => 'Turks and Caicos Islands - U.K. English',
								   'en_TV' => 'Tuvalu - U.K. English',
								   'en_UG' => 'Uganda - U.K. English',
								   
								   'en_UA' => 'Ukraine - U.K. English',
								   'en_AE' => 'United Arab Emirates - U.K. English',
								   'en_GB' => 'United Kingdom - U.K. English',
								   'en_US' => 'United States - U.S. English',
								   'fr_US' => 'United States - French',
								   'es_US' => 'United States - Spanish',
								   'zh_US' => 'United States - Simplified Chinese',
								   'en_UY' => 'Uruguay - U.K. English',
								   'en_VU' => 'Vanuatu - U.K. English',
								   
								   'en_VA' => 'Vatican City State - U.K. English',
								   'en_VE' => 'Venezuela - U.K. English',
								   'en_VN' => 'Vietnam - U.K. English',
								   'en_WF' => 'Wallis and Futuna Islands - U.K. English',
								   'en_YE' => 'Yemen - U.K. English',
								   'en_ZM' => 'Zambia - U.K. English');
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
		global $wp_version;
		if ( version_compare($wp_version, '2.8', '>=') )
			add_action( 'widgets_init',  array(&$this,'load_widget') );
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
	* Register the Widget
	*
	*/
	function load_widget() {
		register_widget( 'paypal_donations_Widget' );
	}

	/**
	* Create and register the PayPal shortcode
	*
	*/
	function paypal_shortcode($atts) {
		extract(shortcode_atts(array(
			'purpose' => '',
			'reference' => '',
			'amount' => '',
		), $atts));

		return $this->generate_html($purpose, $reference, $amount);
	}
	
	/**
	* Generate the PayPal button HTML code
	*
	*/
	function generate_html($purpose = null, $reference = null, $amount = null) {
		$pd_options = get_option($this->plugin_options);

		// Set overrides for purpose and reference if defined
		$purpose = (!$purpose) ? $pd_options['purpose'] : $purpose;
		$reference = (!$reference) ? $pd_options['reference'] : $reference;
		$amount = (!$amount) ? $pd_options['amount'] : $amount;
		
		# Build the button
		$paypal_btn =	'<form action="https://www.paypal.com/cgi-bin/webscr" method="post">';
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
		if ($amount)
			$paypal_btn .=     '<input type="hidden" name="amount" value="' .$amount. '" />';

		// More Settings
		if (isset($pd_options['currency_code']))
			$paypal_btn .=     '<input type="hidden" name="currency_code" value="' .$pd_options['currency_code']. '" />';
		if (isset($pd_options['button_localized']))
			{ $button_localized = $pd_options['button_localized']; } else { $button_localized = 'en_US'; }

		// Settings not implemented yet
		//		$paypal_btn .=     '<input type="hidden" name="amount" value="20" />';

		// Get the button URL
		if ( $pd_options['button'] == "custom" )
			$button_url = $pd_options['button_url'];
		else
			$button_url = str_replace('en_US', $button_localized, $this->donate_buttons[$pd_options['button']]);

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
			$pd_options['currency_code'] = trim( $_POST['currency_code'] );
			$pd_options['amount'] = trim( $_POST['amount'] );
			$pd_options['button_localized'] = trim( $_POST['button_localized'] );
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
    <tr valign="top">
    <th scope="row"><label for="currency_code"><?php _e( 'Currency', 'paypal-donations' ) ?></label></th>
    <td><select name="currency_code" id="currency_code">
<?php   if (isset($pd_options['currency_code'])) { $current_currency = $pd_options['currency_code']; } else { $current_currency = 'USD'; }
		foreach ( $this->currency_codes as $key => $code ) {
	        echo '<option value="'.$key.'"';
			if ($current_currency == $key) { echo ' selected="selected"'; }
			echo '>'.$code.'</option>';
		}?></select>
        <span class="setting-description"><br/><?php _e( 'The currency to use for the donations.', 'paypal-donations' ) ?></span></td>
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
    <th scope="row"><label for="amount"><?php _e( 'Amount', 'paypal-donations' ) ?></label></th>
    <td><input name="amount" type="text" id="amount" value="<?php echo $pd_options['amount']; ?>" class="regular-text" /><span class="setting-description"><br/><?php _e( 'The default amount for a donation (Optional).', 'paypal-donations' ) ?></span></td>
    </tr>
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
	if (isset($pd_options['button_localized'])) { $button_localized = $pd_options['button_localized']; } else { $button_localized = 'en_US'; }
	if (isset($pd_options['button'])) { $current_button = $pd_options['button']; } else { $current_button = 'large'; }
	foreach ( $this->donate_buttons as $key => $button ) {
		echo "\t<label title='" . attribute_escape($key) . "'><input style='padding: 10px 0 10px 0;' type='radio' name='button' value='" . attribute_escape($key) . "'";
		if ( $current_button === $key ) { // checked() uses "==" rather than "==="
			echo " checked='checked'";
			$custom = FALSE;
		}
		echo " /> <img src='" . str_replace('en_US', $button_localized, $button) . "' alt='" . $key  . "' style='vertical-align: middle;' /></label><br /><br />\n";
	}

	echo '	<label><input type="radio" name="button" value="custom"';
	checked( $custom, TRUE );
	echo '/> ' . __('Custom Button:', 'paypal-donations') . ' </label>';
?>
	<input type="text" name="button_url" value="<?php echo $pd_options['button_url']; ?>" class="regular-text" /><br/>
	<span class="setting-description"><?php _e( 'Enter a URL to a custom donation button.', 'paypal-donations' ) ?></span>
	</fieldset>
	</td>
	</tr>
    <tr valign="top">
    <th scope="row"><label for="button_localized"><?php _e( 'Country and Language', 'paypal-donations' ) ?></label></th>
    <td><select name="button_localized" id="button_localized">
<?php   foreach ( $this->localized_buttons as $key => $localize ) {
	        echo '<option value="'.$key.'"';
			if ($button_localized == $key) { echo ' selected="selected"'; }
			echo '>'.utf8_encode($localize).'</option>';
		}?></select>
        <span class="setting-description"><br/><?php _e( 'Localize the language and the country for the button (Updated after saving the settings).', 'paypal-donations' ) ?></span></td>
    </tr>    
    </table>

    <p class="submit">
    <input type="submit" name="Submit" class="button-primary" value="<?php _e( 'Save Changes', 'paypal-donations' ) ?>" />
    </p>
</div>
<?php
	}
}


/**
 * The Class for the Widget
 *
 */
if (class_exists('WP_Widget')) :
class paypal_donations_Widget extends WP_Widget {
	/**
	* Constructor
	*
	*/
	function paypal_donations_Widget() {
		// Widget settings.
		$widget_ops = array ( 'classname' => 'widget_paypal_donations', 'description' => __('PayPal Donation Button', 'paypal-donations') );

		// Widget control settings.
		$control_ops = array( 'id_base' => 'paypal_donations' );

		// Create the Widget
		$this->WP_Widget( 'paypal_donations', 'PayPal Donations', $widget_ops );
	}

	/**
	* Output the Widget
	*
	*/
	function widget( $args, $instance ) {
		extract( $args );
		global $paypal_donations;

		// Get the settings
		$title = apply_filters('widget_title', $instance['title'] );
		$text = $instance['text'];
		$purpose = $instance['purpose'];
		$reference = $instance['reference'];

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		if ( $text )
			echo wpautop( $text );
		echo  $paypal_donations->generate_html( $purpose, $reference );
		echo $after_widget;
	}
	
	/**
	  * Saves the widgets settings.
	  *
	  */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

	    $instance['title'] = strip_tags(stripslashes($new_instance['title']));
	    $instance['text'] = $new_instance['text'];
	    $instance['purpose'] = strip_tags(stripslashes($new_instance['purpose']));
	    $instance['reference'] = strip_tags(stripslashes($new_instance['reference']));

		return $instance;
	}

	/**
	* The Form in the Widget Admin Screen
	*
	*/
	function form( $instance ) {
		// Default Widget Settings
		$defaults = array( 'title' => __('Donate', 'paypal-donations'), 'text' => '', 'purpose' => '', 'reference' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
        
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'paypal-donations'); ?> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
            </label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text:', 'paypal-donations'); ?> 
            <textarea class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo esc_attr($instance['text']); ?></textarea>
            </label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('purpose'); ?>"><?php _e('Purpose:', 'paypal-donations'); ?> 
            <input class="widefat" id="<?php echo $this->get_field_id('purpose'); ?>" name="<?php echo $this->get_field_name('purpose'); ?>" type="text" value="<?php echo esc_attr($instance['purpose']); ?>" />
            </label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('reference'); ?>"><?php _e('Reference:', 'paypal-donations'); ?> 
            <input class="widefat" id="<?php echo $this->get_field_id('reference'); ?>" name="<?php echo $this->get_field_name('reference'); ?>" type="text" value="<?php echo esc_attr($instance['reference']); ?>" />
            </label>
        </p>
        <?php 
	}
}
endif;

/**
 * Uninstall
 * Clean up the WP DB by deleting the options created by the plugin.
 *
 */
if ( function_exists('register_uninstall_hook') )
	register_uninstall_hook(__FILE__, 'paypal_donations_deinstall');
 
function paypal_donations_deinstall() {
	delete_option('paypal_donations_options');
	delete_option('widget_paypal_donations');
}

// Start the Plugin
add_action( 'plugins_loaded', create_function( '', 'global $paypal_donations; $paypal_donations = new paypal_donations();' ) );

?>