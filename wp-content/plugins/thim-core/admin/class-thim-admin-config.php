<?php

/**
 * Class Thim_Admin_Config
 *
 * @since 1.1.0
 */
class Thim_Admin_Config extends Thim_Singleton {
	/**
	 * @since 1.1.0
	 *
	 * @var null
	 */
	private static $configs = null;

	/**
	 * Thim_Admin_Config constructor.
	 */
	protected function __construct() {
		$this->set_config();
	}

	/**
	 * Set configs.
	 *
	 * @since 1.1.0
	 */
	private function set_config() {
		self::$configs = array(
			'api_check_self_update' => 'https://thimpresswp.github.io/thim-core/update-check.json',
			'api_update_plugins'    => 'http://downloads.thimpress.com/wp-json/thim_em/v1/plugins',
			'personal_token'        => 'lfCHoHSKA6DZD4BiwWo9HRZUknLi3sVm',
			'host_envato_app'       => 'https://updates.thimpress.com/thim-envato-market',
			'host_downloads'        => 'http://downloads.thimpress.com/thim-envato-market',
			'welcome_panel_remote'  => 'https://preview.thimpress.com/thewpcourse/wp-json/thim-nf/thim-core',
		);
	}

	/**
	 * Get config by key.
	 *
	 * @since 1.1.0
	 *
	 * @param      $key
	 * @param null $default
	 *
	 * @return mixed|null
	 */
	public static function get( $key, $default = null ) {
		if ( ! isset( self::$configs[ $key ] ) ) {
			return $default;
		}

		return apply_filters( "thim_core_ac_$key", self::$configs[ $key ] );
	}
}