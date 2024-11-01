<?php
	if( !defined('wl_url') ) die();
	
	global $wpdb;
	
	$wp_log_table_prefix = $wpdb->prefix.'wp_logs';
	
	$sql = "CREATE TABLE IF NOT EXISTS $wp_log_table_prefix (
	  id int(11) NOT NULL AUTO_INCREMENT,
	  action_name TINYTEXT DEFAULT NULL,
	  ip_address VARCHAR(50) DEFAULT NULL,
	  date datetime DEFAULT NULL,
	  data text NOT NULL,
	  UNIQUE KEY id (id)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
	dbDelta( $sql );
	