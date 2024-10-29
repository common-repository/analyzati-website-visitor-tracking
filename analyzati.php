<?php
if (!defined('ABSPATH')) {
    exit;
}
/*
 * Plugin Name: Analyzati - website visitor tracking
 * Plugin URI:  https://analyzati.com/wordpress-plugin
 * Description: Adds Analyzati script before the head section of your website.
 * Version:     1.1.0
 * Requires at least: 5.0
 * Requires PHP:      7.0
 * Author:      Analyzati
 * Author URI:  https://analyzati.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

// Register menu item
add_action('admin_menu', 'analyzati_add_menu');

function analyzati_add_menu()
{
    add_menu_page(
        'Analyzati',
        'Analyzati',
        'manage_options',
        'analyzati',
        'analyzati_settings_page',
        'dashicons-chart-line',
        80
    );
}

// Render settings page
function analyzati_settings_page()
{
    ?>
    <div class="wrap">
        <h1>Analyzati Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('analyzati_options');
            do_settings_sections('analyzati_options');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register and display options
add_action('admin_init', 'analyzati_admin_init');

function analyzati_admin_init()
{
    register_setting('analyzati_options', 'analyzati_activate_tracking');
    register_setting('analyzati_options', 'analyzati_activate_dnt');

    add_settings_section(
        'analyzati_section',
        'Privacy focused website analytics',
        'analyzati_section_callback',
        'analyzati_options'
    );

    add_settings_field(
        'analyzati_activate_tracking',
        'Activate Website Tracking',
        'analyzati_activate_tracking_callback',
        'analyzati_options',
        'analyzati_section'
    );

    // Add an "EXTRA" section
    add_settings_section(
        'analyzati_extra_section',
        'Extra Options',
        'analyzati_extra_section_callback',
        'analyzati_options'
    );

    // Add "Activate Do Not Track - DNT" field
    add_settings_field(
        'analyzati_activate_dnt',
        'Activate Do Not Track - DNT',
        'analyzati_activate_dnt_callback',
        'analyzati_options',
        'analyzati_extra_section'
    );
}

// Section callback
function analyzati_section_callback()
{
    echo '<p>Hey;-) follow the steps below...</p>
		<ol>
		  <li>Open your Analyzati Dashboard and click on "+ New Website" button</li>
		  <li>Add your domain in the required field and select the options you need</li> 
		  <li>Come back here and activate the option "Activate Website Tracking" and save changes</li>
		  <li>To verify if everything is working, open another tab in your browser and visit your website. Now from your Analyzati dashboard go to "Realtime" and your visit should appear there. </li>
		</ol>
		If you need any advice, just check <a href="https://analyzati.com/help/get-started/add-your-domain/" target="_blank">this guide</a> in our Knowledgebase or send an email to <a href="mailto:support@analyzati.com">support</a>.';
}

// Activate tracking field callback
function analyzati_activate_tracking_callback()
{
    $option = get_option('analyzati_activate_tracking');
    echo '<input type="checkbox" name="analyzati_activate_tracking" value="1" ' . checked(1, $option, false) . ' />';
}

// Extra section callback
function analyzati_extra_section_callback()
{
    // Additional options section
}

// Activate Do Not Track - DNT field callback
function analyzati_activate_dnt_callback()
{
    $option_dnt = get_option('analyzati_activate_dnt');
    echo '<label for="analyzati_activate_dnt">
            <input type="checkbox" id="analyzati_activate_dnt" name="analyzati_activate_dnt" value="1" ' . checked(1, $option_dnt, false) . ' /> If this is active, we respect the user\'s decision not be tracked.
          </label>';
}

// Add custom script if tracking is activated
add_action('wp_head', 'analyzati_add_custom_script');

function analyzati_add_custom_script()
{
    $option = get_option('analyzati_activate_tracking');
    $option_dnt = get_option('analyzati_activate_dnt');
    $dnt_value = ($option_dnt) ? 'true' : 'false';

    if ($option) {
        echo '<!-- / Analyzati plugin. -->
        <script data-host="https://app.analyzati.com" data-dnt="' . $dnt_value . '" src="https://app.analyzati.com/js/script.js" id="ZwSg9rf6GA" async defer></script>
        <!-- / End Analyzati plugin. -->';
    }
}