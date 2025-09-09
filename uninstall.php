<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
delete_option( 'mll_data_src' );
delete_option( 'mll_google_key' );
delete_option( 'mll_tsml_config' );
delete_option( 'mll_custom_css' );
