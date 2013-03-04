<!-- Create a header in the default WordPress 'wrap' container -->
<div class="wrap">
    <div id="icon-plugins" class="icon32"></div>
    <h2>PayPal Donations</h2>

    <h2 class="nav-tab-wrapper">
        <a href="#" class="nav-tab nav-tab-active">General</a>
        <a href="#" class="nav-tab">Advanced</a>
    </h2>

    <?php // settings_errors(); ?>
    <form method="post" action="options.php">
        <?php settings_fields(PayPalDonations::OPTION_DB_KEY); ?>
        <?php do_settings_sections(PayPalDonations_Admin::PAGE_SLUG); ?>

        <?php submit_button(); ?>
    </form>
