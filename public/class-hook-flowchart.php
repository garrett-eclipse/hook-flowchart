<?php

/**
 * Hook Flowchart
 *
 * @package   Hook_Flowchart
 * @author    Mte90 <daniele@codeat.it>
 * @license   GPL-2.0+
 * @link      http://codeat.it
 * @copyright 2015 GPL
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-hook-flowchart-admin.php`
 *
 * @package Hook_Flowchart
 * @author  Mte90 <daniele@codeat.it>
 */
class Hook_Flowchart {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 * @TODO - Rename "hook-flowchart" to the name of your plugin
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected static $plugin_slug = 'hook-flowchart';

	/**

	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected static $plugin_name = 'Hook Flowchart';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		add_action( 'admin_bar_menu', array( $this, 'register_menu' ), 99 );
		add_action( 'all', array( $this, 'parent_hook' ) );
		add_action( 'shutdown', array( $this, 'print_hookr_flowchart' ), 9999 );
	}

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	  add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );






	  // Load public-facing style sheet and JavaScript.
	  add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	  add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	  add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_js_vars' ) );


	  }

	  /**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return self::$plugin_slug;
	}

	/**
	 * Return the plugin name.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin name variable.
	 */
	public function get_plugin_name() {
		return self::$plugin_name;
	}

	/**
	 * Return the version
	 *
	 * @since    1.0.0
	 *
	 * @return    Version const.
	 */
	public function get_plugin_version() {
		return self::VERSION;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Registers the admin bar menu.
	 * 
	 * @param object $wp_admin_bar
	 * @return void
	 */
	function register_menu( $wp_admin_bar ) {
		$wp_admin_bar->add_node( array(
		    'id' => 'hook-flowchart',
		    'title' => 'Hook FlowChart',
		    'href' => '#'
		) );
	}

	function parent_hook() {
		global $wp_parent_hook, $wp_current_filter;
		foreach ( $wp_current_filter as $child => $hook_name ) {
			if ( $child === 0 && !isset( $wp_parent_hook[ $hook_name ] ) ) {
				$wp_parent_hook[ $hook_name ] = array();
			} elseif ( $child === 1 && !isset( $wp_parent_hook[ $wp_current_filter[ 0 ] ][ $hook_name ] ) ) {
				$wp_parent_hook[ $wp_current_filter[ 0 ] ][ $hook_name ] = array();
			} elseif ( $child === 2 && !isset( $wp_parent_hook[ $wp_current_filter[ 0 ] ][ $wp_current_filter[ 1 ] ][ $hook_name ] ) ) {
				$wp_parent_hook[ $wp_current_filter[ 0 ] ][ $wp_current_filter[ 1 ] ][ $hook_name ] = array();
			} elseif ( $child === 3 ) {
				$wp_parent_hook[ $wp_current_filter[ 0 ] ][ $wp_current_filter[ 1 ] ][ $wp_current_filter[ 2 ] ][ $hook_name ] = 1;
			}
		}
	}

	function print_hookr_flowchart() {
		global $wp_actions, $wp_filter, $wp_parent_hook;
		$html = '';
		ksort( $wp_parent_hook );
		foreach ( $wp_parent_hook as $hook_father => $hook_son ) {
			if ( is_array( $hook_son ) && count( $hook_son ) > 1 ) {
				$html .= '<div class="mermaid-noise" style="display:none">';
				$html .= '[n]graph LR' . "[n]";
				$html .= 'A>' . $hook_father . ']' . "[n]";
				if ( is_array( $hook_son ) && count( $hook_son ) > 1 ) {
					$i = 0;
					foreach ( $hook_son as $hook_son_father => $hook_son_father_son ) {
						$i++;
						$html .= 'A -->' . $i . '[' . $hook_son_father . ']' . "[n]";
						$y = 0;
						if ( is_array( $hook_son_father_son ) ) {
							foreach ( $hook_son_father_son as $hook_son_father_son_father => $hook_son_father_son_father_son ) {
								$y++;
								$html .= $i . ' -->' . $y . '[' . $hook_son_father_son_father . ']' . "[n]";
								$z = 0;
								if ( is_array( $hook_son_father_son_father_son ) ) {
									$z++;
									foreach ( $hook_son_father_son_father_son as $hook_son_father_son_father_son_father => $undefined ) {
										$html .= $y . ' -->' . $z . '[' . $hook_son_father_son_father_son_father . ']' . "[n]";
									}
								}
							}
						}
					}
				}
				$html .= '</div>';
			}
		}
		$html = '<html><head><title>Hook Flowchart - ' . $_SERVER[ 'REQUEST_URI' ] . '</title>'
			. '<link rel="stylesheet" type="text/css" href="' . get_site_url() . '/wp-admin/load-styles.php?c=1&dir=ltr&load=wp-admin,buttons" />'
			. '<link rel="stylesheet" type="text/css" href="' . plugins_url( 'assets/css/mermaid.css', __FILE__ ) . '" />'
			. '<script type="text/javascript" src="' . plugins_url( 'assets/js/mermaid.js', __FILE__ ) . '"></script>'
			. '<script type="text/javascript" src="' . plugins_url( 'assets/js/popupcode.js', __FILE__ ) . '"></script>'
			. '</head><body class="wp-core-ui"><div class="body" style="padding-left:20px"><h1>Hook Flowchart - ' . get_site_url() . $_SERVER[ 'REQUEST_URI' ] . '</h1><h3>' . __( 'Use ctrl + f to use your browser search function or click on that buttons to jump to the parent hook, check the hook to hide', $this->plugin_slug ) . '</h3><span class="buttons"></span>' . $html . '<button class="button button-primary gotop" style="float:right;position:fixed;bottom:10px;right:10px;">' . __( 'Go Top', $this->plugin_slug ) . '</button></div></body></html>';
		echo '<script>'
		. 'jQuery(document).ready(function() {'
		. 'jQuery( "#wp-admin-bar-hook-flowchart a" ).click(function() {
			html = jQuery("<div/>").html("' . htmlentities( $html ) . '").text();
			url = \'data:text/html,\' + html;
			var win = window.open(url, "_blank");
			win.focus();
		      });'
		. '});'
		. '</script>';
	}

}
