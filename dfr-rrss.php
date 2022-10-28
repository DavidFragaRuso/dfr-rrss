<?php
/*
Plugin Name: DFR - WP SocialNetworks
Description: Easily add your social media icons to your WordPress theme
Version: 1.0
Author: David Fraga
License: GPLv2
*/
/*  Copyright 2022  David Fraga Ruso  (email : david.fraga.bcn@gmail.com)

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

//Prefix to rename = dfr_base_

//Verifies minimum WP Version needed to work
register_activation_hook( __FILE__, 'dfr_rrss_install' );
function dfr_rrss_install() {
    global $wp_version;
    if ( version_compare( $wp_version, '6.0', '<' ) ) {
        wp_die( 'This plugin requires WordPress version 6.0 or higher.' );
    }
}

// Load text domain and language files for traductions

/**
 * How to create .POT file for make translations (with free version of PoEdit)
 * 
 * In PoEdit:
 * 
 * 1.In the "File" menu select "New"
 * 2.Select the language that you used in your theme (probably English)
 * 3.In the "Catalog" menu select "Properties"
 * 4.Enter the project information in the "Translation properties" tab
 * 5.Go to the 3rd tab "Sources keywords"
 * 6.Click on the "New item" button (2nd button) and enter a keyword and repeat this for each of your keywords (__, _e, esc_attr_e, etc.)
 * 7.Click on the "OK" button at the bottom
 * 8.In the "File" menu select "Save As.."
 * 9.Save the file as "yourthemename.pot" in the "languages" folder in your theme directory (make sure you add the .pot extension to the filename because by default it will save as .po)
 * 10.In the "Catalog" menu select "Properties" again
 * 11.Go to the 2nd tab "Sources paths"
 * 12.Click the "+" button under "Path" textarea and select "Add folders".(this will make it scan your plugin directory and its subdirectories)
 * 13.Select the main folder of your plugin
 * 14.Click on the "OK" button at the bottom
 * 15.In the project window click on "Update" (2nd icon at the top)
 * 16.In the "File" menu click "Save". It creates .POT and .MO files
 * 
 * From the .POT file extension you can create different translation files and save them as .PO files.
 */

function dfr_rrss_languages() {
    load_plugin_textdomain( 'dfr-rrss', false,  'dfr-rrss/languages' );
}
add_action('init', 'dfr_rrss_languages');

// Register WPColorPicker and scripts
function dfr_rrss_add_color_picker( $hook ) {
    if( is_admin() ) {
        // Add the color picker css file
        wp_enqueue_style( 'wp-color-picker' );
        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'custom-script-handle', plugins_url( '/js/main.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
    }
}
add_action( 'admin_enqueue_scripts', 'dfr_rrss_add_color_picker' );

// Custom admin Style
function rrss_admin_styles() {
    ?>
    <style>
        .dfr-panels{
            display: flex;
            flex-direction: column;
            flex-wrap: nowrap;
        }
        @media screen and (min-width:1024px){
            .dfr-panels{
                flex-direction: row;
                gap: 3em;
            }
            .panel-left{
                flex-basis: 100%;
                flex-grow: 2;
            }
            .panel-left form table tbody tr td input[type=text]{
                min-width: 100%;
            }
            .panel-right{
                flex-grow: 1;
                margin-top: 50px;
            }    
        }
        .css-example{
            border: solid 1px #a3a3a3;
            display: inline-block;
            border-radius: 3px;
            background-color: #bbbbbb;
        }
        .css-example pre{
            padding: 1em 1.5em 1em 1.5em;
            margin: 0;
        }
        .css-example pre code{
            background: transparent;
            padding: 0;
            margin: 0;
        }
    </style>
    <?php
}
add_action( 'admin_head', 'rrss_admin_styles' );

//Hook to add menu item/page to WP backend example:
add_action( 'admin_menu', 'dfr_rrss_admin_menu' );
function dfr_rrss_admin_menu(){
    // Add option page ( page_title, menu_title, capabiblity, menu_slug, function, icon_url, position )
    add_options_page(
        __('DFR - WP Social Networks', 'dfr-rrss'), //HTML title text
        __('My Social Networks', 'dfr-rrss'), //Text for item name in the Dashboard
        'manage_options', //Minimum user capacity to access the section
        'dfr-rrss', //slug for page
        'dfr_rrss_page' //Function to build menu page
        //'img/generic.png', //icon url-rute
        //3 //Position of the item in the menu, by default it is placed at the end.
    );
}
function dfr_rrss_page() {
    ?>
    <div class="wrap dfr-panels">
        <div class="panel panel-left">
            <h1><?php _e( 'My social networks', 'dfr-rrss' ) ?></h1>
            <form action="options.php" method="post">
                <?php 
                settings_fields( 'dfr_rrss_options' );
                do_settings_sections( 'dfr-rrss' );
                submit_button( __('Save Changes', 'dfr-rrss'), 'primary' );
                ?>
            </form>
        </div>
        <div class="panel panel-right">
            <h2><?php _e('Plugin usage', 'dfr-rrss'); ?></h2>
            <p> <?php _e('Put the shortcode [rrss-links] in your pages, widgets or posts', 'dfr-rrss'); ?></p>
            <h3><?php _e('Do you need a second icon bar with different colors for your theme design?', 'dfr-rrss'); ?></h3>
            <p><?php _e('Simply add id="someid" to the shortcode like this:', 'dfr-rrss'); ?></p>
            <p><?php _e(' [rrss-links id="secondary_icons"]', 'dfr-rrss'); ?></p>
            <p><?php _e( 'Then you can add new CSS style', 'dfr-rrss') ?></p>
            <p><?php _e( 'Example:', 'dfr-rrss') ?></p>
            <div class="css-example">
                <pre>
                    <code>
ul#secondary_icons li a svg path, 
ul#secondary_icons li a svg circle, 
ul#secondary_icons li a svg polygon{
    fill: #d63638;
}
ul#secondary_icons li a:hover svg path,
ul#secondary_icons li a:hover svg circle, 
ul#secondary_icons li a:hover svg polygon{
    fill: #2b73d5;
}
                    </code>
                </pre>
            </div>
        </div>
    </div>
    <?php
}

// Register and define the settings
add_action( 'admin_init', 'dfr_rrss_admin_init' );
function dfr_rrss_admin_init () {
    $args = array(
        'type' => 'array',
        'sanitize_callback' => 'dfr_rrss_validate_options',
        'default' => array(
            'icon_size' => '25',
            'icon_margin' => '15'   
        )
    );

    // Register our settings ( option group, option name, args)
    register_setting( 'dfr_rrss_options', 'dfr_rrss_links', $args );

    // Add a settings section ( id, title, callback, page )
    add_settings_section(
        'dfr_rrss_main',
        __('Links to your social networks', 'dfr-rrss'),
        'dfr_rrss_section_url',
        'dfr-rrss'
    );

    // Draw the section header
    function dfr_rrss_section_url() {
        echo '<p>' . __('Enter the links to your social networks here', 'dfr-rrss' ) . '</p>';
    }

    add_settings_section(
        'dfr_rrss_config',
        __('Icon configuration', 'dfr-rrss'),
        'dfr_rrss_section_settings',
        'dfr-rrss'
    );

    function dfr_rrss_section_settings() {
        echo '<p>' . __('Configure the appearance of your social network icons', 'dfr-rrss') . '</p>';
    };
    
    // Create our settings field for urls inputs ( id, title, callback, page, section, args )
    add_settings_field( 
        'dfr_rrss_facebook',
        __('Facebook URL', 'dfr-rrss'),
        'dfr_rrss_setting_facebook',
        'dfr-rrss',
        'dfr_rrss_main'
    );
    add_settings_field( 
        'dfr_rrss_instagram',
        __('Instragram URL', 'dfr-rrss'),
        'dfr_rrss_setting_instagram',
        'dfr-rrss',
        'dfr_rrss_main'
    );
    add_settings_field( 
        'dfr_rrss_twitter',
        __('Twitter URL', 'dfr-rrss'),
        'dfr_rrss_setting_twitter',
        'dfr-rrss',
        'dfr_rrss_main'
    );
    add_settings_field( 
        'dfr_rrss_tumblr',
        __('Tumblr URL', 'dfr-rrss'),
        'dfr_rrss_setting_tumblr',
        'dfr-rrss',
        'dfr_rrss_main'
    );
    add_settings_field( 
        'dfr_rrss_linkedin',
        __('Linkedin URL', 'dfr-rrss'),
        'dfr_rrss_setting_linkedin',
        'dfr-rrss',
        'dfr_rrss_main'
    );
    add_settings_field( 
        'dfr_rrss_pinterest',
        __('Pinterest URL', 'dfr-rrss'),
        'dfr_rrss_setting_pinterest',
        'dfr-rrss',
        'dfr_rrss_main'
    );
    add_settings_field( 
        'dfr_rrss_youtube',
        __('YouTube URL', 'dfr-rrss'),
        'dfr_rrss_setting_youtube',
        'dfr-rrss',
        'dfr_rrss_main'
    );
    add_settings_field( 
        'dfr_rrss_tiktok',
        __('Tik Tok URL', 'dfr-rrss'),
        'dfr_rrss_setting_tiktok',
        'dfr-rrss',
        'dfr_rrss_main'
    );
    add_settings_field( 
        'dfr_rrss_telegram',
        __('Telegram URL', 'dfr-rrss'),
        'dfr_rrss_setting_telegram',
        'dfr-rrss',
        'dfr_rrss_main'
    );

    // Add color picker fields
    add_settings_field(
        'dfr_rrss_color',
        __('Icons color', 'dfr-rrss'),
        'dfr_rrss_setting_iconcolor',
        'dfr-rrss',
        'dfr_rrss_config'
    );

    add_settings_field(
        'dfr_rrss_color_hover',
        __('Icons color on Hover', 'dfr-rrss'),
        'dfr_rrss_setting_iconcolor_hover',
        'dfr-rrss',
        'dfr_rrss_config'
    );

    // Add number inputs fields
    add_settings_field(
        'dfr_rrss_icon_size',
        __('Icons size in pixels', 'dfr-rrss'),
        'dfr_rrss_setting_icon_size',
        'dfr-rrss',
        'dfr_rrss_config',
    );

    add_settings_field(
        'dfr_rrss_icon_margin',
        __('Margin between Icons in pixels', 'dfr-rrss'),
        'dfr_rrss_setting_icon_margin',
        'dfr-rrss',
        'dfr_rrss_config',
    );

    // Display and fill the form fields

    function dfr_rrss_setting_facebook() {
        // Get option 'text string' value from the database if exist
        $options = get_option( 'dfr_rrss_links' );
        if( array_key_exists( 'facebook', $options ) ){
            $facebook = $options['facebook'];
            echo "<input id='facebook' name='dfr_rrss_links[facebook]' type='text' value='" . esc_attr($facebook) . "'/>";
        } else {
            echo "<input id='facebook' name='dfr_rrss_links[facebook]' type='text' value=''/>";
        }

    }

    function dfr_rrss_setting_instagram() {
        $options = get_option( 'dfr_rrss_links' );
        if( array_key_exists( 'instagram', $options ) ){
            $instagram = $options['instagram'];
            echo "<input id='instagram' name='dfr_rrss_links[instagram]' type='text' value='" . esc_attr($instagram) . "'/>";
        } else {
            echo "<input id='facebook' name='dfr_rrss_links[instagram]' type='text' value=''/>";
        }
    }

    function dfr_rrss_setting_twitter() {
        $options = get_option( 'dfr_rrss_links' );
        if( array_key_exists( 'twitter', $options ) ){
            $twitter = $options['twitter'];
            echo "<input id='twitter' name='dfr_rrss_links[twitter]' type='text' value='" . esc_attr($twitter) . "'/>";
        } else {
            echo "<input id='twitter' name='dfr_rrss_links[twitter]' type='text' value=''/>";
        }
    }

    function dfr_rrss_setting_tumblr() {
        $options = get_option( 'dfr_rrss_links' );
        if( array_key_exists( 'tumblr', $options ) ){
            $tumblr = $options['tumblr'];
            echo "<input id='tumblr' name='dfr_rrss_links[tumblr]' type='text' value='" . esc_attr($tumblr) . "'/>";
        } else {
            echo "<input id='tumblr' name='dfr_rrss_links[tumblr]' type='text' value=''/>";
        }
    }

    function dfr_rrss_setting_linkedin() {
        $options = get_option( 'dfr_rrss_links' );
        if( array_key_exists( 'linkedin', $options ) ){
            $linkedin = $options['linkedin'];
            echo "<input id='linkedin' name='dfr_rrss_links[linkedin]' type='text' value='" . esc_attr($linkedin) . "'/>";
        } else {
            echo "<input id='linkedin' name='dfr_rrss_links[linkedin]' type='text' value=''/>";
        }
    }

    function dfr_rrss_setting_pinterest() {
        $options = get_option( 'dfr_rrss_links' );
        if( array_key_exists( 'pinterest', $options ) ){
            $pinterest = $options['pinterest'];
            echo "<input id='pinterest' name='dfr_rrss_links[pinterest]' type='text' value='" . esc_attr($pinterest) . "'/>";
        } else {
            echo "<input id='pinterest' name='dfr_rrss_links[pinterest]' type='text' value=''/>";
        }
    }

    function dfr_rrss_setting_youtube() {
        $options = get_option( 'dfr_rrss_links' );
        if( array_key_exists( 'youtube', $options ) ){
            $youtube = $options['youtube'];
            echo "<input id='youtube' name='dfr_rrss_links[youtube]' type='text' value='" . esc_attr($youtube) . "'/>";
        } else {
            echo "<input id='youtube' name='dfr_rrss_links[youtube]' type='text' value=''/>";
        }
    }

    function dfr_rrss_setting_tiktok() {
        $options = get_option( 'dfr_rrss_links' );
        if( array_key_exists( 'tiktok', $options ) ){
            $tiktok = $options['tiktok'];
            echo "<input id='tiktok' name='dfr_rrss_links[tiktok]' type='text' value='" . esc_attr($tiktok) . "'/>";
        } else {
            echo "<input id='tiktok' name='dfr_rrss_links[tiktok]' type='text' value=''/>";
        }
    }

    function dfr_rrss_setting_telegram() {
        $options = get_option( 'dfr_rrss_links' );
        if( array_key_exists( 'telegram', $options ) ){
            $telegram = $options['telegram'];
            echo "<input id='telegram' name='dfr_rrss_links[telegram]' type='text' value='" . esc_attr($telegram) . "'/>";
        } else {
            echo "<input id='telegram' name='dfr_rrss_links[telegram]' type='text' value=''/>";
        }
    }

    function dfr_rrss_setting_iconcolor() {
        $options = get_option( 'dfr_rrss_links' );
        if( array_key_exists( 'icon_color', $options ) ){
            $icon_color = $options['icon_color'];
            echo "<input id='icon_color' name='dfr_rrss_links[icon_color]' type='text' value='" . esc_attr($icon_color) . "' class='color-field' />";
        } else {
            echo "<input id='icon_color' name='dfr_rrss_links[icon_color]' type='text' value='' class='color-field' />";
        }  
    }

    function dfr_rrss_setting_iconcolor_hover() {
        $options = get_option( 'dfr_rrss_links' );
        if( array_key_exists( 'icon_color_hover', $options ) ){
            $icon_color_hover = $options['icon_color_hover'];
            echo "<input id='icon_color_hover' name='dfr_rrss_links[icon_color_hover]' type='text' value='" . esc_attr($icon_color_hover) . "' class='color-field' />";
        } else {
            echo "<input id='icon_color_hover' name='dfr_rrss_links[icon_color_hover]' type='text' value='' class='color-field' />";
        }  
    }

    function dfr_rrss_setting_icon_size() {
        $options = get_option( 'dfr_rrss_links' );
        if( array_key_exists( 'icon_size', $options ) ){
            $icon_size = $options['icon_size'];
            echo "<input id='icon_size' name='dfr_rrss_links[icon_size]' type='number' min='20' max='100' value='" . esc_attr($icon_size) . "' />";
        } else {
            echo "<input id='icon_size' name='dfr_rrss_links[icon_size]' type='number' min='20' max='100' value='' />";
        }  
    }

    function dfr_rrss_setting_icon_margin() {
        $options = get_option( 'dfr_rrss_links' );
        if( array_key_exists( 'icon_margin', $options ) ){
            $icon_margin = $options['icon_margin'];
            echo "<input id='icon_margin' name='dfr_rrss_links[icon_margin]' type='number' min='10' max='60' value='" . esc_attr($icon_margin) . "' />";
        } else {
            echo "<input id='icon_margin' name='dfr_rrss_links[icon_margin]' type='number' min='10' max='60' value='' />";
        }  
    }

    // Validate inputs
    function dfr_rrss_validate_options( $input ) {
        $valid = array();
    
        if( isset( $input['facebook'] ) && $input['facebook'] !== '' ){
            // Validate with standar php filters
            $valid['facebook'] = filter_var( $input['facebook'], FILTER_VALIDATE_URL, FILTER_NULL_ON_FAILURE );

            if( $valid['facebook'] !== $input['facebook'] ) {
                add_settings_error(
                    'dfr_rrss_url_string',
                    'dfr_rrss_facebook_error',
                    'Incorrect Facebook url entered!',
                    'error'   
                );
            }
        }
        
        if( isset( $input['instagram'] ) && $input['instagram'] !== '' ) {
            $valid['instagram'] = filter_var( $input['instagram'], FILTER_VALIDATE_URL, FILTER_NULL_ON_FAILURE );

            if( $valid['instagram'] !== $input['instagram'] ) {
                add_settings_error(
                    'dfr_rrss_url_string',
                    'dfr_rrss_instagram_error',
                    'Incorrect Instagram url entered!',
                    'error'   
                );
            }
        }

        if( isset( $input['twitter'] ) && $input['twitter'] !== '' ) {
            $valid['twitter'] = filter_var( $input['twitter'], FILTER_VALIDATE_URL, FILTER_NULL_ON_FAILURE );

            if( $valid['twitter'] !== $input['twitter'] ) {
                add_settings_error(
                    'dfr_rrss_url_string',
                    'dfr_rrss_twitter_error',
                    'Incorrect Twitter url entered!',
                    'error'   
                );
            }
        }

        if( isset( $input['tumblr'] ) && $input['tumblr'] !== '' ) {
            $valid['tumblr'] = filter_var( $input['tumblr'], FILTER_VALIDATE_URL, FILTER_NULL_ON_FAILURE );

            if( $valid['tumblr'] !== $input['tumblr'] ) {
                add_settings_error(
                    'dfr_rrss_url_string',
                    'dfr_rrss_tumblr_error',
                    'Incorrect Tumblr url entered!',
                    'error'   
                );
            }
        }

        if( isset( $input['linkedin'] ) && $input['linkedin'] !== '' ) {
            $valid['linkedin'] = filter_var( $input['linkedin'], FILTER_VALIDATE_URL, FILTER_NULL_ON_FAILURE );

            if( $valid['linkedin'] !== $input['linkedin'] ) {
                add_settings_error(
                    'dfr_rrss_url_string',
                    'dfr_rrss_linkedin_error',
                    'Incorrect Linkedin url entered!',
                    'error'   
                );
            }
        }

        if( isset( $input['pinterest'] ) && $input['pinterest'] !== '' ) {
            $valid['pinterest'] = filter_var( $input['pinterest'], FILTER_VALIDATE_URL, FILTER_NULL_ON_FAILURE );

            if( $valid['pinterest'] !== $input['pinterest'] ) {
                add_settings_error(
                    'dfr_rrss_url_string',
                    'dfr_rrss_pinterest_error',
                    'Incorrect Pinterest url entered!',
                    'error'   
                );
            }
        }

        if( isset( $input['youtube'] ) && $input['youtube'] !== '' ) {
            $valid['youtube'] = filter_var( $input['youtube'], FILTER_VALIDATE_URL, FILTER_NULL_ON_FAILURE );

            if( $valid['youtube'] !== $input['youtube'] ) {
                add_settings_error(
                    'dfr_rrss_url_string',
                    'dfr_rrss_youtube_error',
                    'Incorrect YouTube url entered!',
                    'error'   
                );
            }
        }

        if( isset( $input['tiktok'] ) && $input['tiktok'] !== '' ) {
            $valid['tiktok'] = filter_var( $input['tiktok'], FILTER_VALIDATE_URL, FILTER_NULL_ON_FAILURE );

            if( $valid['tiktok'] !== $input['tiktok'] ) {
                add_settings_error(
                    'dfr_rrss_url_string',
                    'dfr_rrss_tiktok_error',
                    'Incorrect Tik Tok url entered!',
                    'error'   
                );
            }
        }
        
        if( isset( $input['telegram'] ) && $input['telegram'] !== '' ) {
            $valid['telegram'] = filter_var( $input['telegram'], FILTER_VALIDATE_URL, FILTER_NULL_ON_FAILURE );

            if( $valid['telegram'] !== $input['telegram'] ) {
                add_settings_error(
                    'dfr_rrss_url_string',
                    'dfr_rrss_telegram_error',
                    'Incorrect Telegram url entered!',
                    'error'   
                );
            }
        }

        // Sanitize with WP filters
        $valid['icon_color'] = sanitize_text_field( $input['icon_color'] );
        $valid['icon_color_hover'] = sanitize_text_field( $input['icon_color_hover'] );
        $valid['icon_size'] = sanitize_text_field( $input['icon_size'] );
        $valid['icon_margin'] = sanitize_text_field( $input['icon_margin'] );
        
        return $valid;
    }
}

// Shortcodes and front-end functions
include_once( 'shortcodes.php' );