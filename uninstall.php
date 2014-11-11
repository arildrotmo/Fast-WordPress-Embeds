<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option( 'fwe_settings' );

// Multisite
delete_site_option( $option_name );
