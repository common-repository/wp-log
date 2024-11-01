<?php
	if( !defined('wl_url') ) die();
	
	global $wpdb;
	$wp_log_table_prefix = $wpdb->prefix.'wp_logs';
	
	function wl_logs_count($action='*'){
		global $wpdb,$wp_log_table_prefix;
		if($action=='*'){
			$output = $wpdb->get_var("SELECT COUNT(*) FROM ".$wp_log_table_prefix);
		} else {
			$output = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM ".$wp_log_table_prefix." WHERE action_name = %s",$action) );
		}
		return $output;
	}
	
	function wl_logs_action_row($column_name='*',$filter_key=null,$filter_value=null,$limit=10,$orderby='date',$order='ASC'){
		global $wpdb,$wp_log_table_prefix;
		if( $filter_key === null  && $filter_value === null ){
			$output = $wpdb->get_results( "SELECT ".$column_name." FROM ".$wp_log_table_prefix." ORDER BY $orderby $order LIMIT $limit", ARRAY_A);
		}
		if( $filter_key !== null  && $filter_value !== null ){
			$output = $wpdb->get_results( $wpdb->prepare("SELECT ".$column_name." FROM ".$wp_log_table_prefix." WHERE ".$filter_key." = %s ORDER BY $orderby $order LIMIT $limit",$filter_value), ARRAY_A);
		}
		return $output;
	}
	
	