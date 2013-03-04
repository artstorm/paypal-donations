<!-- Create a header in the default WordPress 'wrap' container -->
<div class="wrap">
    <div id="icon-plugins" class="icon32"></div>
    <h2>PayPal Donations</h2>

    <h2 class="nav-tab-wrapper">
        <a href="#" class="nav-tab nav-tab-active">General</a>
        <a href="#" class="nav-tab">Advanced</a>
    </h2>

    <form method="post" action="options.php">
        <?php
            settings_fields($optionDBKey);
            do_settings_sections($pageSlug);

            submit_button();
        ?>
    </form>
