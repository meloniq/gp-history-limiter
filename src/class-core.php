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

use GP;
use GP_Translation;

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
		add_action( 'gp_translation_saved', array( $this, 'limit_waiting_translations' ), 13, 1 );
		add_action( 'gp_translation_saved', array( $this, 'limit_old_translations' ), 16, 1 );
	}

	/**
	 * Limit the number of old translation entries per translation.
	 *
	 * @param GP_Translation $translation Translation following the update.
	 *
	 * @return void
	 */
	public function limit_old_translations( GP_Translation $translation ) {
		$entries_over_limit = $this->get_translations_over_limit( $translation, 'old' );

		// Delete history entries exceeding the limit.
		foreach ( $entries_over_limit as $entry ) {
			$this->delete_translation_entry( $entry->id );
		}
	}

	/**
	 * Limit the number of waiting translation entries per translation.
	 *
	 * @param GP_Translation $translation Translation following the update.
	 *
	 * @return void
	 */
	public function limit_waiting_translations( GP_Translation $translation ) {
		$entries_over_limit = $this->get_translations_over_limit( $translation, 'waiting' );

		// Set the status of history entries exceeding the limit to 'old'.
		foreach ( $entries_over_limit as $entry ) {
			$this->set_translation_entry_to_old( $entry->id );
		}
	}

	/**
	 * Get the translation entries that exceed the limit for a given translation and status.
	 *
	 * @param GP_Translation $translation Translation following the update.
	 * @param string         $status The status of the translation entries to limit (default: 'old').
	 *
	 * @return array An array of translation entries that exceed the limit for the given translation and status.
	 */
	public function get_translations_over_limit( GP_Translation $translation, string $status = 'old' ) {
		$limit = $this->get_history_limit( $status );

		// Get the entries for the translation.
		$entries = GP::$translation->find_many(
			array(
				'original_id'        => $translation->original_id,
				'translation_set_id' => $translation->translation_set_id,
				'status'             => $status,
			),
			'id DESC'
		);

		// If the number of entries is less than or equal to the limit, then do nothing.
		if ( count( $entries ) <= $limit ) {
			return array();
		}

		// Get the entries that exceed the limit.
		$entries_over_limit = array_slice( $entries, $limit );

		return $entries_over_limit;
	}

	/**
	 * Set the status of translation entry to 'old'.
	 *
	 * @param int $translation_id The ID of the translation entry to update.
	 *
	 * @return bool True if the translation entry was updated successfully, false otherwise.
	 */
	public function set_translation_entry_to_old( int $translation_id ): bool {
		$translation = $this->get_translation_entry( $translation_id );
		if ( ! $translation ) {
			return false;
		}

		$params = array(
			'status'                => 'old',
			'user_id_last_modified' => get_current_user_id(),
		);

		return $translation->save( $params );
	}

	/**
	 * Delete translation entry.
	 *
	 * @param int $translation_id The ID of the translation entry to delete.
	 *
	 * @return bool True if the translation entry was deleted successfully, false otherwise.
	 */
	public function delete_translation_entry( int $translation_id ): bool {

		$translation = $this->get_translation_entry( $translation_id );
		if ( ! $translation ) {
			return false;
		}

		return $translation->delete();
	}

	/**
	 * Get the translation entry by ID.
	 *
	 * @param int $translation_id The ID of the translation entry to retrieve.
	 *
	 * @return GP_Translation|null The translation entry if found, null otherwise.
	 */
	public function get_translation_entry( int $translation_id ): ?GP_Translation {
		if ( $translation_id <= 0 ) {
			return null;
		}

		$translation = GP::$translation->find_one(
			array(
				'id' => $translation_id,
			)
		);

		if ( ! $translation ) {
			return null;
		}

		return $translation;
	}

	/**
	 * Returns the limit for the number of history entries per translation.
	 *
	 * @param string $status The status of the translation entries to limit (default: 'old').
	 *
	 * @return int The limit for the number of history entries per translation.
	 */
	public function get_history_limit( string $status = 'old' ): int {
		// Default limit for the number of history entries per translation.
		$default_limit = 3;
		$limit         = $default_limit;

		if ( defined( 'GLOTCORE_HISTORY_LIMIT' ) ) {
			$limit = (int) GLOTCORE_HISTORY_LIMIT;
		}

		/**
		 * Filter the limit for the number of history entries per translation.
		 * This filter allows modification of the limit for the number of history entries per translation. The default limit is 3, but it can be changed by defining the GLOTCORE_HISTORY_LIMIT constant or by using this filter.
		 *
		 * @param int $limit The limit for the number of history entries per translation.
		 * @param int $default_limit The default limit for the number of history entries per translation (default: 3).
		 * @param string $status The status of the translation entries to limit (default: 'old').
		 */
		$limit = apply_filters( 'glotcore_history_limit', $limit, $default_limit, $status );

		// Ensure the limit is a positive integer.
		$limit = absint( $limit );
		if ( $limit <= 0 ) {
			$limit = $default_limit;
		}

		return $limit;
	}
}
