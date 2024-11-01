<?php
	if( !defined('wl_url') ) die();
	
	class wp_logs_actions{
		
	
		public function __construct(){
			add_action( 'clear_auth_cookie', array( $this, 'logout' ) );
			add_action( 'wp_login', array( $this, 'login' ), 10, 2 );
			add_action( 'pre_post_update', array( $this, 'post_updates' ) );
			add_action( 'user_register', array($this, 'user_register') );
			add_action( 'after_switch_theme', array( $this, 'theme_switch' ) );
			add_action( 'wp_insert_comment', array($this, 'new_comment'), 99, 2 );	
			add_action( 'comment_unapproved_to_approved', array($this, 'comment_approved') );
			add_action( 'comment_approved_to_unapproved', array($this, 'comment_unapproved') );
			add_action( 'delete_user', array($this, 'user_deleted') );
			add_action( 'before_delete_post', array($this, 'post_deleted') );
			add_action( 'activate_plugin', array($this, 'plugin_activate') );
			add_action( 'init', array($this, 'plugin_deactivate') );
		}
		
		function table_name(){
			global $wpdb;
			return $wpdb->prefix.'wp_logs';
		}
		
		function wl_option($option=null,$key=null){
			if($option===null||$key===null||!is_array(get_option($option))) return false;	
			$option = get_option($option);
			return in_array($key,$option);
		}

		
		function GetIpAddr(){
			if (!empty($_SERVER['HTTP_CLIENT_IP'])){
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}
		  return $ip;
		}
		
		private function _mail($action,$extra, $IP){
			
			switch($action){
				case ('user_logout'):
					$action_name = __('User Logout','wl');
				break;
				case ('user_login'):
					$action_name = __('User Login','wl');
				break;
				case ('post_edit'):
					$action_name = __('Post Edit','wl');
				break;
				case ('user_register'):
					$action_name = __('User Register','wl');
				break;
				case ('theme_switch'):
					$action_name = __('Theme Switch','wl');
				break;
				case ('new_comment_inserted'):
					$action_name = __('New Comment','wl');
				break;
				case ('comment_approved'):
					$action_name = __('Comment Approved','wl');
				break;
				case ('comment_unapproved'):
					$action_name = __('Comment Unapproved','wl');
				break;
				case ('user_deleted'):
					$action_name = __('User Delete','wl');
				break;
				case ('post_deleted'):
					$action_name = __('Post Delete','wl');
				break;
				case ('plugin_activated'):
					$action_name = __('Plugin Activated','wl');
				break;
				case ('plugin_deactivated'):
					$action_name = __('Plugin Deactivated','wl');
				break;
				default:
					$action_name = __('Not Found','wl');
				break;
			}
			
			$email = get_option('wl_receiver_mail',get_bloginfo('admin_email'));
			$sender = parse_url(site_url());
			$sender = $sender['host'];
			$sender = 'logs@'.$sender;
			$mime_boundary=md5(time());
			$headers = "";
			$headers .= 'From: Logs <'.$sender.'>' . "\n";
			$headers .= 'MIME-Version: 1.0'. "\n";
			$headers .= "Content-Type: text/html; charset=UTF-8; boundary=\"".$mime_boundary."\"". "\n";
			
			
			$text_direction = get_bloginfo('text_direction');
			$message = '<table style="margin-top:20px;margin-bottom:20px;margin-right:auto;margin-left:auto;background:#f3f3f7;border:1px solid #dedee3;padding: 10px;width:80%;direction:'.$text_direction.';">';	
			
			$message .= '<tr>';
			$message .= '<td>'.__('Action','wl').':</td>';
			$message .= '<td>'.$action_name.'</td>';
			$message .= '</tr>';
			
			$message .= '<tr>';
			$message .= '<td>'.__('Date','wl').':</td>';
			$message .= '<td>'.current_time('mysql').'</td>';
			$message .= '</tr>';
			
			$message .= '<tr>';
			$message .= '<td>'.__('User IP','wl').':</td>';
			$message .= '<td>'.$IP.'</td>';
			$message .= '</tr>';
			
			foreach( $extra as $name => $val ) {
				$message .= '<tr>';
				$message .= '<td>'.$name.':</td>';
				$message .= '<td>'.$val.'</td>';
				$message .= '</tr>';
			}
			$message .= '</table>';
			
			$user_subject = get_option( 'wl_emails_subject', __('New Activity - {action}','wl' ) );
			$email_subject = str_replace('{action}',$action_name,$user_subject);
			
			@wp_mail( $email, $email_subject, $message, $headers );
		}
		
		function logout(){
			global $wpdb;
			$userinfo['User ID'] = @wp_get_current_user()->data->ID;
			$userinfo['User Nicename'] = @wp_get_current_user()->data->user_nicename;
			$userinfo['User Email'] = @wp_get_current_user()->data->user_email;
			$userinfo['User Display Name'] = @wp_get_current_user()->data->display_name;
			$date = date("Y-m-d H:i:s", time());
			$data = array(
				'action_name' => 'user_logout',
				'ip_address' => $this->GetIpAddr(),
				'date' => $date,
				'data' => serialize($userinfo),
			);
			if( $this->wl_option('wl_user_logout_option','db') ){
				$wpdb->insert( $this->table_name(), $data);
			}	
			if( $this->wl_option('wl_user_logout_option','mail') ){
				$this->_mail('user_logout',$userinfo,$this->GetIpAddr());
			}	
		}

		function login($user_login, $user){
			global $wpdb;
			$userinfo['User ID'] = $user->data->ID;
			$userinfo['User Nicename'] = $user->data->user_nicename;
			$userinfo['User Email'] = $user->data->user_email;
			$userinfo['User Display Name'] = $user->data->display_name;
			$date = date("Y-m-d H:i:s", time());
			$data = array(
				'action_name' => 'user_login',
				'ip_address' => $this->GetIpAddr(),
				'date' => $date,
				'data' => serialize($userinfo),
			);
			if( $this->wl_option('wl_user_login_option','db') ){
				$wpdb->insert( $this->table_name(), $data);
			}	
			if( $this->wl_option('wl_user_login_option','mail') ){
				$this->_mail('user_login',$userinfo,$this->GetIpAddr());
			}	
		}
		
		function post_updates($post_ID){
			if ( !wp_is_post_revision( $post_ID ) ) {
				global $wpdb;
				$userinfo['User ID'] = wp_get_current_user()->data->ID;
				$userinfo['User Nicename'] = wp_get_current_user()->data->user_nicename;
				$userinfo['User Email'] = wp_get_current_user()->data->user_email;
				$userinfo['User Display Name'] = wp_get_current_user()->data->display_name;
				$date = date("Y-m-d H:i:s", time());
				$data = array(
					'action_name' => 'post_edit',
					'ip_address' => $this->GetIpAddr(),
					'date' => $date,
					'data' => serialize($userinfo),
				);
				if( $this->wl_option('wl_post_edit_option','db') ){
					$wpdb->insert( $this->table_name(), $data);
				}
				if( $this->wl_option('wl_post_edit_option','mail')) {
					$this->_mail('post_edit',$userinfo,$this->GetIpAddr());
				}	
			}	
		}
		
		function user_register($user_ID){
			global $wpdb;
			$date = date("Y-m-d H:i:s", time());
			$user_info = get_userdata($user_ID);
			$userinfo['User ID'] = $user_ID;
			$userinfo['User Nicename'] = $user_info->user_nicename;
			$userinfo['User Email'] = $user_info->user_email;
			$userinfo['User Display Name'] = $user_info->display_name;
			$data = array(
				'action_name' => 'user_register',
				'ip_address' => $this->GetIpAddr(),
				'date' => $date,
				'data' => serialize($userinfo),
			);
			if( $this->wl_option('wl_user_register_option','db') ){
				$wpdb->insert( $this->table_name(), $data);
			}
			if( $this->wl_option('wl_user_register_option','mail') ){
				$this->_mail('user_register',$userinfo,$this->GetIpAddr());
			}
		}
		
		function theme_switch(){
			global $wpdb;
			$date = date("Y-m-d H:i:s", time());
			$userinfo['User ID'] = wp_get_current_user()->data->ID;
			$userinfo['User Nicename'] = wp_get_current_user()->data->user_nicename;
			$userinfo['User Email'] = wp_get_current_user()->data->user_email;
			$userinfo['User Display Name'] = wp_get_current_user()->data->display_name;
			
			if( function_exists( 'wp_get_theme' ) ) {
				if( is_child_theme() ) {
					$temp_obj = wp_get_theme();
					$theme_obj = wp_get_theme( $temp_obj->get('Template') );
				} else {
					$theme_obj = wp_get_theme();    
				}
				$theme_version = $theme_obj->get('Version');
				$theme_name = $theme_obj->get('Name');
				$theme_uri = $theme_obj->get('ThemeURI');
				$author_uri = $theme_obj->get('AuthorURI');
			} else {
				$theme_data = get_theme_data( TEMPLATEPATH.'/style.css' );
				$theme_version = $theme_data['Version'];
				$theme_name = $theme_data['Name'];
				$theme_uri = $theme_data['AuthorURI'];
				$author_uri = $theme_data['AuthorURI'];
			}

			$userinfo['New Theme Name'] = $theme_name;
			$userinfo['New Theme Version'] = $theme_version;
			$userinfo['New Theme URI'] = $theme_uri;
			$userinfo['New Theme Author URI'] = $author_uri;
			$data = array(
				'action_name' => 'theme_switch',
				'ip_address' => $this->GetIpAddr(),
				'date' => $date,
				'data' => serialize($userinfo),
			);
			if( $this->wl_option('wl_theme_switch_option','db') ){
				$wpdb->insert( $this->table_name(), $data);
			}
			if( $this->wl_option('wl_theme_switch_option','mail') ){
				$this->_mail('theme_switch',$userinfo,$this->GetIpAddr());
			}
		}
	
		function new_comment($comment_id, $comment_object) {
			global $wpdb;
			$date = date("Y-m-d H:i:s", time());
			$_data['Comment ID'] = $comment_object->comment_ID;
			$_data['Comment Post ID'] = $comment_object->comment_post_ID;
			$_data['Comment Author'] = $comment_object->comment_author;
			$_data['Comment Author Email'] = $comment_object->comment_author_email;
			$_data['Comment Content'] = wp_html_excerpt($comment_object->comment_content, 60);
			$data = array(
				'action_name' => 'new_comment_inserted',
				'ip_address' => $this->GetIpAddr(),
				'date' => $date,
				'data' => serialize($_data),
			);
			if( $this->wl_option('wl_new_comment_option','db') ){
				$wpdb->insert( $this->table_name(), $data);
			}
			if( $this->wl_option('wl_new_comment_option','mail') ){
				$this->_mail('new_comment_inserted',$_data,$this->GetIpAddr());
			}
		}
	
		function comment_approved($comment) {
			global $wpdb;
			$date = date("Y-m-d H:i:s", time());
			$_data['Comment ID'] = $comment->comment_ID;
			$_data['Comment Post ID'] = $comment->comment_post_ID;
			$_data['Comment Author'] = $comment->comment_author;
			$_data['Comment Author Email'] = $comment->comment_author_email;
			$_data['Comment Content'] = wp_html_excerpt($comment->comment_content, 60);
			$data = array(
				'action_name' => 'comment_approved',
				'ip_address' => $this->GetIpAddr(),
				'date' => $date,
				'data' => serialize($_data),
			);
			if( $this->wl_option('wl_comment_approved_option','db') ){
				$wpdb->insert( $this->table_name(), $data);
			}
			if( $this->wl_option('wl_comment_approved_option','mail') ){
				$this->_mail('comment_approved',$_data,$this->GetIpAddr());
			}
		}
	
		function comment_unapproved($comment) {
			global $wpdb;
			$date = date("Y-m-d H:i:s", time());
			$_data['Comment ID'] = $comment->comment_ID;
			$_data['Comment Post ID'] = $comment->comment_post_ID;
			$_data['Comment Author'] = $comment->comment_author;
			$_data['Comment Author_email'] = $comment->comment_author_email;
			$_data['Comment Content'] = wp_html_excerpt($comment->comment_content, 60);
			$data = array(
				'action_name' => 'comment_unapproved',
				'ip_address' => $this->GetIpAddr(),
				'date' => $date,
				'data' => serialize($_data),
			);
			if( $this->wl_option('wl_comment_unapproved_option','db') ){
				$wpdb->insert( $this->table_name(), $data);
			}
			if( $this->wl_option('wl_comment_unapproved_option','mail') ){
				$this->_mail('comment_unapproved',$_data,$this->GetIpAddr());
			}
		}
		
		function user_deleted($user_ID){
			global $wpdb;
			$date = date("Y-m-d H:i:s", time());
			$user_info = get_userdata($user_ID);
			$_data['User ID'] = $user_ID;
			$_data['User Nicename'] = $user_info->user_nicename;
			$_data['User Email'] = $user_info->user_email;
			$_data['User Display Name'] = $user_info->display_name;
			$data = array(
				'action_name' => 'user_deleted',
				'ip_address' => $this->GetIpAddr(),
				'date' => $date,
				'data' => serialize($_data),
			);
			if( $this->wl_option('wl_user_deleted_option','db') ){
				$wpdb->insert( $this->table_name(), $data);
			}
			if( $this->wl_option('wl_user_deleted_option','mail') ){
				$this->_mail('user_deleted',$_data,$this->GetIpAddr());
			}
		}
		
		function post_deleted($post_ID){
			global $wpdb;
			$date = date("Y-m-d H:i:s", time());
			$post = get_post($post_ID);
			$_data['Post ID'] = $post->ID;
			$_data['Post Author ID'] = $post->post_author;
			$_data['Post Title'] = $post->post_title;
			$_data['Comment Count'] = $post->comment_count;
			$data = array(
				'action_name' => 'post_deleted',
				'ip_address' => $this->GetIpAddr(),
				'date' => $date,
				'data' => serialize($_data),
			);
			if( $this->wl_option('wl_post_delete_option','db') ){
				$wpdb->insert( $this->table_name(), $data);
			}
			if( $this->wl_option('wl_post_delete_option','mail') ){
				$this->_mail('post_deleted',$_data,$this->GetIpAddr());
			}
		}
		
		function plugin_activate(){
			global $wpdb;
			$date = date("Y-m-d H:i:s", time());
			$plugin_data = get_plugin_data(  WP_PLUGIN_DIR.'/'.$_GET['plugin'] );
			$_data['Plugin Name'] = $plugin_data['Name'];
			$_data['Plugin URI'] = $plugin_data['PluginURI'];
			$_data['Plugin Version'] = $plugin_data['Version'];
			$_data['Plugin Author'] = $plugin_data['Author'];
			$_data['Plugin Author URI'] = $plugin_data['AuthorURI'];
			$data = array(
				'action_name' => 'plugin_activated',
				'ip_address' => $this->GetIpAddr(),
				'date' => $date,
				'data' => serialize($_data),
			);
			if( $this->wl_option('wl_plugin_activated_option','db') ){
				$wpdb->insert( $this->table_name(), $data);
			}
			if( $this->wl_option('wl_plugin_activated_option','mail') ){
				$this->_mail('plugin_activated',$_data,$this->GetIpAddr());
			}
		}
		
		function plugin_deactivate(){
			if( isset($_GET['action']) && isset($_GET['plugin']) ){
				if( $_GET['action'] == 'deactivate' ){
					global $wpdb;
					$date = date("Y-m-d H:i:s", time());
					$plugin_data = get_plugin_data(  WP_PLUGIN_DIR.'/'.$_GET['plugin'] );
					$_data['Plugin Name'] = $plugin_data['Name'];
					$_data['Plugin URI'] = $plugin_data['PluginURI'];
					$_data['Plugin Version'] = $plugin_data['Version'];
					$_data['Plugin Author'] = $plugin_data['Author'];
					$_data['Plugin Author URI'] = $plugin_data['AuthorURI'];
					$data = array(
						'action_name' => 'plugin_deactivated',
						'ip_address' => $this->GetIpAddr(),
						'date' => $date,
						'data' => serialize($_data),
					);
					if( $this->wl_option('wl_plugin_deactivated_option','db') ){
						$wpdb->insert( $this->table_name(), $data);
					}
					if( $this->wl_option('wl_plugin_deactivated_option','mail') ){
						$this->_mail('plugin_deactivated',$_data,$this->GetIpAddr());
					}
				}
			}		
		}
		
	}