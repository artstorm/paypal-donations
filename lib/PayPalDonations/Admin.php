<?php
/**
 * PayPal Donations Settings.
 *
 * Class that renders out the HTML for the settings screen and contains helpful
 * methods to simply the maintainance of the admin screen.
 *
 * @package PayPal Donations
 * @author  Johan Steen <artstorm at gmail dot com>
 * @since   Post Snippets 1.5
 */
class PayPalDonations_Admin
{
    private $plugin_options;
    private $currency_codes;
    private $donate_buttons;
    private $localized_buttons;
    private $checkout_languages;

    const PAGE_SLUG = 'paypal-donations-options';

    public function __construct()
    {
        add_action('admin_menu', array($this, 'menu'));
        add_action('admin_init', array($this, 'init'));
    }

    /**
     * Register the Menu.
     */
    public function menu()
    {
        add_options_page(
            'PayPal Donations Options',
            'PayPal Donations',
            'administrator',
            self::PAGE_SLUG,
            array($this, 'renderpage')
        );
    }    

    /**
     * Register the settings.
     */
    public function init()
    {
        add_settings_section(
            'account_setup_section',
            __('Account Setup', 'paypal-donations'),
            array($this, 'accountSetupCallback'),
            self::PAGE_SLUG
        );
        add_settings_field(
            'paypal_account',
            __('PayPal Account', 'paypal-donations'),
            array($this, 'paypalAccountCallback'),
            self::PAGE_SLUG,
            'account_setup_section',
            array(
                'label_for' => 'paypal_account',
                'description' => __(
                    'Your PayPal Email or Secure Merchant Account ID.',
                    'paypal-donations'
                    ),
            )
        );

        // register_setting(self::PAGE_SLUG, PayPalDonations::OPTION_DB_KEY);
    }

    // -------------------------------------------------------------------------
    // Section Callbacks
    // -------------------------------------------------------------------------

    /**
     * Description for the account setup section.
     */
    public function accountSetupCallback()
    {
        printf('<p>%s</p>', 'Required fields.');
    }

    // -------------------------------------------------------------------------
    // Fields Callbacks
    // -------------------------------------------------------------------------

    /**
     * PayPal Account.
     */
    public function paypalAccountCallback($args)
    {
        $optionKey = PayPalDonations::OPTION_DB_KEY;
        $options = get_option($optionKey);
        echo "<input type='text' id='paypal_account' ";
        echo "name='{$optionKey}[paypal_account]'' ";
        echo "value='{$options['paypal_account']}' />";

        echo "<p class='description'>{$args['description']}</p>";  
    }












    public function optionsPage()
    {
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
            $pd_options['disable_stats'] = isset($_POST['disable_stats']) ? true : false;
            $pd_options['center_button'] = isset($_POST['center_button']) ? true : false;
            $pd_options['set_checkout_language'] = isset($_POST['set_checkout_language']) ? true : false;
            $pd_options['checkout_language'] = trim( $_POST['checkout_language'] );
            update_option(self::OPTION_DB_KEY, $pd_options);
            $this->adminMessage( __( 'The PayPal Donations settings have been updated.', 'paypal-donations' ) );
        }

        // Render the settings screen
        // $settings = new PayPalDonations_Admin();
        // $settings->setOptions(
        //     get_option(self::OPTION_DB_KEY),
        //     $this->currency_codes,
        //     $this->donate_buttons,
        //     $this->localized_buttons,
        //     $this->checkout_languages
        // );
        // $settings->render();
    }

    public function adminMessage($message)
    {
        if ($message) {
            ?>
            <div class="updated"><p><strong>
                <?php echo $message; ?>
            </strong></p></div>
            <?php   
        }
    }







    public function renderpage()
    {

        $data = array();
        $data = array(
            'pageSlug' => self::PAGE_SLUG,
            'plugin_options' => $this->plugin_options,
            'currency_codes' => $this->currency_codes,
            'donate_buttons' => $this->donate_buttons,
            'localized_buttons' => $this->localized_buttons,
            'checkout_languages' => $this->checkout_languages,
        );
        echo PayPalDonations_View::render(
            plugin_dir_path(__FILE__).'../../views/admin.php', $data);


    }



    public function setOptions(
        $options,
        $code,
        $buttons,
        $loc_buttons,
        $checkout_lng
    ) {
        $this->plugin_options = $options;
        $this->currency_codes = $code;
        $this->donate_buttons = $buttons;
        $this->localized_buttons = $loc_buttons;
        $this->checkout_languages = $checkout_lng;
    }

    public function render()
    {
        $data = array(
            'plugin_options' => $this->plugin_options,
            'currency_codes' => $this->currency_codes,
            'donate_buttons' => $this->donate_buttons,
            'localized_buttons' => $this->localized_buttons,
            'checkout_languages' => $this->checkout_languages,
        );
        echo PayPalDonations_View::render(
            plugin_dir_path(__FILE__).'../../views/admin.php', $data);
    }

    // -------------------------------------------------------------------------
    // HTML and Form element methods
    // -------------------------------------------------------------------------
    
    /**
     * Checkbox.
     * Renders the HTML for an input checkbox.
     *
     * @param   string  $label      The label rendered to screen
     * @param   string  $name       The unique name to identify the input
     * @param   boolean $checked    If the input is checked or not
     */
    public static function checkbox($label, $name, $checked)
    {
        printf( '<input type="checkbox" name="%s" value="true"', $name );
        if ($checked) {
            echo ' checked';
        }
        echo ' />';
        echo ' '.$label;
    }
}
