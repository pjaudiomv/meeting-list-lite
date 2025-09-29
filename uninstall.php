<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
delete_option( 'meetinglistlite_data_src' );
delete_option( 'meetinglistlite_google_key' );
delete_option( 'meetinglistlite_tsml_config' );
delete_option( 'meetinglistlite_custom_css' );
