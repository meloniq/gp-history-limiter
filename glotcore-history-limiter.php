<?php
/**
 * Plugin Name:       GlotCore History Limiter
 * Plugin URI:        https://blog.meloniq.net/gp-history-limiter/
 *
 * Description:       GlotPress plugin to limit the number of translation history entries per translation.
 * Tags:              glotpress, translations, history, limiter
 *
 * Requires at least: 4.9
 * Requires PHP:      7.4
 * Version:           1.0
 *
 * Author:            MELONIQ.NET
 * Author URI:        https://meloniq.net/
 *
 * License:           GPLv2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain:       glotcore-history-limiter
 *
 * Requires Plugins:  glotpress
 *
 * @package GlotCore\HistoryLimiter
 */

namespace GlotCore\HistoryLimiter;

// If this file is accessed directly, then abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'GC_HL_TD', 'glotcore-history-limiter' );
define( 'GC_HL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'GC_HL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * GP Init Setup.
 *
 * @return void
 */
function gp_init() {
	global $glotcore_historylimiter;

	require_once __DIR__ . '/src/class-core.php';

	$glotcore_historylimiter['core'] = new Core();
}
add_action( 'gp_init', 'GlotCore\HistoryLimiter\gp_init' );
