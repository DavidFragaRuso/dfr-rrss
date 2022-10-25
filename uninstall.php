<?php
//If uninstall or delete not called from WordPress then exit
if ( !defined('WP_UNINSTALL_PLUGIN') ) {
    //wp_die( sprintf( __('%s should only be called when uninstalling the plugin.', 'dfr-rrss' ), __FILE__ ) );
    exit();
}

// Clean de-registration of registered setting
unregister_setting( 'dfr_rrss_options', 'dfr_rrss_links' );

// Remove saved options from the database
delete_option( 'dfr_rrss_links' );
