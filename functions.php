<?php
	if ( ! defined( 'ABSPATH' ) ) {
		die( 'Directly access this file you can not!' );
	}

	define( 'PRIDECAL_OPTIONS', 'pridecal-list' );
	define( 'PRIDECAL_SETTINGS_PAGE', admin_url() . 'options-general.php?page=' . PRIDECAL_OPTIONS );
	define( 'PRIDECAL_URL', 'https://spartacus.gayguide.travel/events/' );
	define( 'PRIDECAL_API', 'https://www.gayguide.travel/api.js' );
	define( 'PRIDECAL_MSG', '<div class="notice notice-warning is-dismissible" id="pridecal_needs_configuration"><p>PrideCalendar wurde installiert. Du solltest es noch <a href="' . PRIDECAL_SETTINGS_PAGE . '">konfigurieren</a>.</p></div>');

	function pridecal_get_options() {
		return get_option(
				'travel.gayguide.options',
				array(
						'search'       => '',
						'lang'         => 'en',
						'color'        => '#e91e63',
						'agb_accepted' => false,
						'extra_style'  => '#fw-list a {box-shadow: none;}',
				)
		);
	}

	function pridecal_admin_notice() {
		$options = pridecal_get_options();
		if (!$options['agb_accepted'] &&  current_user_can( 'manage_options' ) ) {
			echo PRIDECAL_MSG;
		}
	}
	add_action( 'admin_notices', 'pridecal_admin_notice' );

	function pridecal_settings_link( $links ) {
		$settings_link = '<a href="' . PRIDECAL_SETTINGS_PAGE . '">Einstellungen</a>';
		array_unshift( $links, $settings_link );
		$info_link = '<a href="' . PRIDECAL_URL . '" target="_blank">Website</a>';
		array_push( $links, $info_link );
		return $links;
	}
	add_filter( "plugin_action_links_" . PRIDECAL_ID, 'pridecal_settings_link' );
