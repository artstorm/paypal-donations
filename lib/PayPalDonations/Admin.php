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
