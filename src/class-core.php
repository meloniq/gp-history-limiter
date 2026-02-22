<?php
/**
 * Core functionality.
 *
 * @package GlotCore\HistoryLimiter
 */

namespace GlotCore\HistoryLimiter;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Core class.
 */
class Core {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
	}

	/**
	 * Returns the limit for the number of history entries per translation.
	 *
	 * @return int The limit for the number of history entries per translation.
	 */
	public function get_import_strings_limit() {
		// Default limit for the number of history entries per translation.
		$default_limit = 5;
		$limit         = $default_limit;

		if ( defined( 'GLOTCORE_HISTORY_LIMIT' ) ) {
			$limit = (int) GLOTCORE_HISTORY_LIMIT;
		}

		/**
		 * Filter the limit for the number of history entries per translation.
		 * This filter allows modification of the limit for the number of history entries per translation. The default limit is 5, but it can be changed by defining the GLOTCORE_HISTORY_LIMIT constant or by using this filter.
		 *
		 * @param int $limit The limit for the number of history entries per translation.
		 * @param int $default_limit The default limit for the number of history entries per translation (default: 5).
		 */
		$limit = apply_filters( 'glotcore_history_limit', $limit, $default_limit );

		return $limit;
	}
}
