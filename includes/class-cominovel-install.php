<?php

use Ramphor\User\Db\UserTable;
use Ramphor\PostViews\Setup as SetupPostViews;

class Cominovel_Install {
	public static function active() {
		/**
		 * Flush rewrite rules after register Cominovel post types
		 */
		add_action( 'init', 'flush_rewrite_rules', 90 );

		if ( class_exists( UserTable::class ) ) {
			UserTable::create();
		}
		if ( class_exists( SetupPostViews::class ) ) {
			$post_views = new SetupPostViews();
			$post_views->createTables();
		}

		self::create_tables();
	}

	public static function deactive() {
		add_action( 'init', 'flush_rewrite_rules', 90 );
	}

	public static function create_tables() {
		global $wpdb;

		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}cominovel_user_likes`(
			`like_id` BIGINT UNSIGNED NOT NULL auto_increment,
			`user_id` BIGINT UNSIGNED NOT NULL,
			`chapter_id` BIGINT UNSIGNED NOT NULL,
			`liked` TINYINT NOT NULL DEFAULT 0,
			`created_at` TIMESTAMP,
			`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`like_id`)
		)";
		$wpdb->query( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}cominovel_user_follows`(
			`follow_id` BIGINT UNSIGNED NOT NULL auto_increment,
			`user_id` BIGINT UNSIGNED NOT NULL,
			`post_id` BIGINT UNSIGNED NOT NULL,
			`followed` TINYINT NOT NULL DEFAULT 0,
			`created_at` TIMESTAMP,
			`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`follow_id`)
		)";
		$wpdb->query( $sql );
	}
}
