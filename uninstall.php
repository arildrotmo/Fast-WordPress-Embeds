<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

unregister_setting( 'pluginPage', 'fwe_settings' );
delete_option( 'fwe_settings' );

// Multisite
delete_site_option( 'fwe_settings' );
