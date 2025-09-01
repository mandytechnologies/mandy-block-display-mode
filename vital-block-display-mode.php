<?php
namespace Mandy;

/*
Plugin Name: Quick Build Block Settings — Display Mode
Plugin URI:  https://quickbuildwebsite.com
Description: Adds controls for display mode (light, dark, et al)
Version:     0.1
Author:      Quick Build
Author URI:  https://quickbuildwebsite.com
Text Domain: quick-build-block
*/

class MandyBlockDisplayMode {
	public static function setup() {
		add_action('enqueue_block_editor_assets', [__CLASS__, 'enqueue_block_editor_assets']);
		add_filter('skeletor_block_data', [__CLASS__, 'update_block_class'], 9, 2);

		if (!class_exists('\Skeletor\Plugin_Updater')) {
			require_once(__DIR__ . '/class--plugin-updater.php');
		}

		$updater = new \Skeletor\Plugin_Updater(
			plugin_basename(__FILE__),
			MANDY_BLOCK_DISPLAY_MODE_VERSION,
			'https://github.com/mandytechnologies/mandy-block-display-mode/blob/main/package.json'
		);
	}

	/**
	 * hook for any Skeletor theme using ACF blocks
	 *
	 * @param array $block_data
	 * @param array $props
	 * @return array
	 */
	static function update_block_class($block_data, $props) {
		$block_class = '';
		if (isset($block_data['_block_class'])) {
			$block_class = $block_data['_block_class'];
		}

		if (isset($props['displayMode'])) {
			$block_class .= sprintf(' is-style-%s', $props['displayMode']);
		}

		$block_data['_block_class'] = trim($block_class);

		return $block_data;
	}

	public static function enqueue_block_editor_assets() {
		if (($screen = get_current_screen()) && $screen->is_block_editor) {
			wp_enqueue_script(
				'mandy_block_display_mode',
				plugin_dir_url(__FILE__) . '/build/index.js',
				[],
				filemtime(__DIR__ . '/build/index.js'),
				true
			);
		}
	}
}

add_action('after_setup_theme', ['\Mandy\MandyBlockDisplayMode', 'setup']);