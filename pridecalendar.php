<?php
	/*
	Plugin Name: Pridecalendar
	Plugin URI: https://spartacus.gayguide.travel/prides
	Description: Plugin zur Einbindung des Pride Kalenders vom Spartacus Gayguide
	Author: GayGuide UG
	Version: 1.0.3
	Author URI: https://www.gayguide.travel
	Text Domain: pridecal-api
	License: WTFPL
	License URI: http://www.wtfpl.net/txt/copying/
	*/

	if ( ! defined( 'ABSPATH' ) ) {
		die( 'Directly access this file you can not!' );
	}
	define( 'PRIDECAL_ID', 'pridecalendar/pridecalendar.php' );
	define ('PRIDECAL_LANGS', '{"de":"Deutsch","en":"English","es":"Español","it":"Italiano","ru":"Русский","fr":"Français","pt":"Português","pl":"Polski","da":"Dansk"}');

	require_once( plugin_dir_path(__FILE__) . 'functions.php');

	function pridecal_uninstall(){
		$options = pridecal_get_options();
		$options['agb_accepted'] = false;
		update_option( 'travel.gayguide.options', $options );
	}
	register_uninstall_hook( __FILE__, 'pridecal_uninstall' );
	register_deactivation_hook( __FILE__, 'pridecal_uninstall' );


	function pridecal_plugin_options() {
		require( plugin_dir_path( __FILE__ ) . 'options.php' );
	}
	function pridecal_plugin_menu() {
		add_options_page( 'Pridecalendar', 'Pridecalendar', 'manage_options', PRIDECAL_OPTIONS, 'pridecal_plugin_options' );
	}
	add_action( 'admin_menu', 'pridecal_plugin_menu' );


// List
	function pridecal_list( $atts ) {
		// get settings from db
		$options        = pridecal_get_options();
		$pridecal_agb        = ( isset( $options ) && isset( $options['agb_accepted'] ) ) ? (bool)$options['agb_accepted'] : false;
		$pridecal_search = ( isset( $options ) && isset( $options['search'] ) ) ? $options['search'] : '';
		$pridecal_color = ( isset( $options ) && isset( $options['color'] ) ) ? $options['color'] : '';
		$pridecal_lang = ( isset( $options ) && isset( $options['lang'] ) ) ? $options['lang'] : 'en';
		$pridecal_extra_style= ( isset( $options ) && isset( $options['extra_style'] ) ) ? $options['extra_style'] : '';
		// overwrite if set directly
		$pridecal_search   = ( isset( $atts['search'] ) ) ? $atts['search'] : $pridecal_search;
		$pridecal_lang   = ( isset( $atts['lang'] ) ) ? $atts['lang'] : $pridecal_lang;
		// if is allready set up, add shortcode content
		if ( $pridecal_agb ) {
			$pridecal_url    = PRIDECAL_URL;
			$pridecal_text   = 'Gay Prides and Events';
			$pridecal_config = '{ wordpress: "' . get_bloginfo('version') . '"';
			if ( isset( $pridecal_search ) && ! empty( $pridecal_search ) ) {
				$pridecal_config .= ', searchTerm: "' . $pridecal_search . '"';
			}
			if ( isset( $pridecal_lang ) && ! empty( $pridecal_lang ) ) {
				$pridecal_config .= ', lang: "' . $pridecal_lang . '"';
			}
			if ( isset($pridecal_color) && ! empty( $pridecal_color )) {
				$pridecal_config .= ', color: "'. $pridecal_color .'"';
			}
			$pridecal_config .= '}';
			$pridecal_shortcode = '<script type="text/javascript" src="' . PRIDECAL_API . '"></script>';
			$pridecal_shortcode .= '<style type="text/css">' . $pridecal_extra_style . '</style>';
			$pridecal_shortcode .= '<span id="_fwlink">lade <a href="' . $pridecal_url . '">' . $pridecal_text . '</a>.</span>';
			$pridecal_shortcode .= '<script type="text/javascript">_fw.init(' . $pridecal_config . ')</script>';
			return $pridecal_shortcode;
		} else {
			return '<p>' . __('Das Pridecalendar Plugin muss erst im Backend eingerichtet werden...') . '</p>';
		}
	}
	add_shortcode( "pridecal_list", "pridecal_list" );

	function pridecal_add_quicktag() {

		if ( wp_script_is( 'quicktags' ) ) {
			?>
			<script type="text/javascript">
				QTags.addButton('pridecal_add_list', 'PRIDECAL', '[pridecal_list]', '', '', 'Fügt den Shortcode für den Pridecalendar ein', 200);
			</script>
			<?php
		}

	}
	add_action( 'admin_print_footer_scripts', 'pridecal_add_quicktag' );


	function pridecal_plugin_shortcode_button_init() {
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) && get_user_option( 'rich_editing' ) == 'true' ) {
			return;
		}
		add_filter( "mce_external_plugins", "pridecal_plugin_register_tinymce_plugin" );
		add_filter( 'mce_buttons', 'pridecal_plugin_add_tinymce_button' );
	}
	function pridecal_plugin_register_tinymce_plugin( $plugin_array ) {
		$plugin_array['pridecal_plugin_button'] = plugins_url( '/shortcode.js', __FILE__ );
		return $plugin_array;
	}
	function pridecal_plugin_add_tinymce_button( $buttons ) {
		$buttons[] = "pridecal_plugin_button";
		return $buttons;
	}
	add_action( 'admin_init', 'pridecal_plugin_shortcode_button_init' );
