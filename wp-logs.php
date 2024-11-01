<?php
	/*
		Plugin Name: Wordpress Log
		Plugin URI: http://vahidd.com
		Description: Simple Plugin To Log Some Actions
		Author: Vahidd
		Version: 1.0
		Author URI: http://vahidd.com
		Text Domain: wl
		Domain Path: /langs/
		Licence: GPL
	*/

	
	define('wl_dir',dirname(__FILE__) . '/');
	define('wl_url',plugin_dir_url(__FILE__));
	define('wl_site_url',get_bloginfo('url').'/');
	if ( !function_exists( 'get_plugins' ) ) { require_once ( ABSPATH . 'wp-admin/includes/plugin.php' ); }
	foreach(get_plugins() as $plugin_file => $plugin_data){
		$wl_plugins_file[] = WP_PLUGIN_DIR.'/'.$plugin_file;
	}
	
	load_plugin_textdomain( 'wl', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );
	
	require wl_dir.'actions.php';
	require wl_dir.'admin-panel/admin-menu.php';
	require wl_dir.'sql.php';
	
	register_activation_hook( __FILE__ , 'wp_logs_install' );
	function wp_logs_install(){
		require wl_dir.'install.php';
	}
	
	if( is_admin() && $pagenow == 'admin.php' && $_GET['page'] == 'wp_log_settings' ){
		function wl_admin_forntend_files() {
			wp_enqueue_style( 'wl-admin-style', wl_url.'css/admin-styles.css' );
		}
		add_action('init', 'wl_admin_forntend_files');
	}
	
	function wl_checkboxes($array=null,$this=null){
		if($array===null||!is_array($array)||$this===null) return;
		if(in_array($this,$array))
			echo 'checked="checked"';
	}

	new wp_logs_actions();