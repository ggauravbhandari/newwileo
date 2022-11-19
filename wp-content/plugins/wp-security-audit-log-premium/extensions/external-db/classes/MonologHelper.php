<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helper object that optimizes logging using monolog handlers.
 *
 * @since 4.3.0
 * @package Wsal
 * @subpackage ExternalDB
 */
class WSAL_Ext_MonologHelper {

	/**
	 * @var WpSecurityAuditLog
	 */
	private $plugin;

	/**
	 * WSAL_Ext_MonologHelper constructor.
	 *
	 * @param WpSecurityAuditLog $plugin
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * @param array $connection Connection config data.
	 * @param array $mirror Raw mirror data. This is not guaranteed as the function is sometimes called in context where mirror is not available.
	 * @param int $code Event code.
	 * @param string $message
	 * @param array $metadata
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function log( $connection, $mirror, $code, $message, $metadata ) {
		$mirror_types = $this->plugin->external_db_util->get_mirror_types();
		if ( ! is_array( $connection ) || empty( $connection ) ) {
			$connection = $this->plugin->external_db_util->get_connection( $mirror['connection'] );
		}

		if ( ! is_array( $connection ) ) {
			//  connection not valid
			throw new Exception( 'Invalid connection data. The link between the mirror and the connection is probably broken.' );
		}

		if ( ! array_key_exists( $connection['type'], $mirror_types ) ) {
			//  unrecognized mirror type
			throw new Exception( 'Unrecognized mirror type: ' . $connection['type'] );
		}

		$mirror_type = $mirror_types[ $connection['type'] ];
		/** @var WSAL_Ext_ConnectionInterface $mirror_instance */
		$mirror_instance = new $mirror_type['class']( $this->plugin, $connection );

		//  check if event is allowed to be logged to this mirror
		if ( is_array( $mirror ) && array_key_exists( 'filter', $mirror ) && ! empty( $mirror['filter'] ) ) {

			if ( 'event-codes' === $mirror['filter'] && ! empty( $mirror['event_codes'] ) ) {
				if ( ! in_array( $code, $mirror['event_codes'] ) ) {
					return false;
				}
			} else if ( 'except-codes' === $mirror['filter'] && ! empty( $mirror['exception_codes'] ) ) {
				if ( in_array( $code, $mirror['exception_codes'] ) ) {
					return false;
				}
			}
		}

		//  @todo check if given severity is allowed to be logged

		// create a log channel
		$logger_name = is_array( $mirror ) && array_key_exists( 'source', $mirror ) ? $mirror['source'] : sanitize_title( get_bloginfo( 'url' ) );
		$logger      = new \WSAL_Vendor\Monolog\Logger( $logger_name, [], [
			new WSAL_Ext_MetadataTimestampProcessor()
		], new DateTimeZone( 'GMT' ) );
		$logger->useMicrosecondTimestamps( false );

		$username = WSAL_Utilities_UsersUtils::GetUsername( $metadata );
		if ( ! empty( $username ) ) {
			$user = get_user_by( 'login', $username );
			if ( $user instanceof WP_User ) {
				$metadata['User'] = WSAL_Utilities_UsersUtils::get_display_label( $this->plugin, $user );
			}
		}

		$handler = $mirror_instance->get_monolog_handler();
		if ( is_null( $handler ) ) {
			throw new Exception( 'Failed to create Monolog handler for mirror ' . $mirror['name'] );
		}

		$handler_successfully_added_to_logger = false;
		$logger->pushHandler( $handler );
		$handler_successfully_added_to_logger = true;

		try {
			// work out the log level from the severity
			$severity  = array_key_exists( 'Severity', $metadata ) ? $metadata['Severity'] : \WSAL_Vendor\Monolog\Logger::DEBUG;
			$log_level = $this->get_log_level( $severity );
			$_metadata = $mirror_instance->pre_process_metadata( $metadata, $mirror );
			$_message  = $mirror_instance->pre_process_message( $message, $_metadata );

			$logger->log( $log_level, $_message, $_metadata );
		} catch ( Exception $exception ) {
			throw $exception;
		} finally {
			if ( $handler_successfully_added_to_logger ) {
				$logger->popHandler();
			}
		}

		return true;
	}


	/**
	 * Translates severity used by the plugin to the monolog log levels. Supports legacy severity number used prior to version 4.3.
	 *
	 * @param int $severity Event severity as defined by the plugin.
	 *
	 * @return int Monolog log level.
	 */
	public function get_log_level( $severity ) {
		switch ( $severity ) {
			//  WSAL_CRITICAL, E_CRITICAL
			case 500:
			case 1:
			case 400:
			case 6:
				return \WSAL_Vendor\Monolog\Logger::ALERT;

			//  WSAL_MEDIUM, E_WARNING
			case 300:
			case 10:
				return \WSAL_Vendor\Monolog\Logger::WARNING;

			//  WSAL_LOW, E_NOTICE
			case 250:
			case 15:
				return \WSAL_Vendor\Monolog\Logger::NOTICE;

			//  WSAL_INFORMATIONAL
			case 200:
			case 20:
			default:
				return \WSAL_Vendor\Monolog\Logger::INFO;
		}
	}
}