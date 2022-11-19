<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AWS CloudWatch connection class.
 *
 * @package wsal
 * @subpackage external-db
 * @since 4.3.0
 */
class WSAL_Ext_Mirrors_AWSCloudWatchConnection extends WSAL_Ext_AbstractConnection {

	public static function get_type() {
		return 'aws_cloudwatch';
	}

	public static function get_name() {
		return __( 'AWS CloudWatch', 'wp-security-audit-log' );
	}

	public static function get_config_definition() {
		return [
			'desc'   => __( 'General mirror connection description.', 'wp-security-audit-log' ),
			'fields' => [
				'region'    => [
					'label'   => __( 'Region', 'wp-security-audit-log' ),
					'type'    => 'select',
					'options' => [
						'us-east-1'      => 'US East (N. Virginia)',
						'us-east-2'      => 'US East (Ohio)',
						'us-west-1'      => 'US West (N. California)',
						'us-west-2'      => 'US West (Oregon)',
						'af-south-1'     => 'Africa (Cape Town)',
						'ap-east-1'      => 'Asia Pacific (Hong Kong)',
						'ap-south-1'     => 'Asia Pacific (Mumbai)',
						'ap-northeast-3' => 'Asia Pacific (Osaka)',
						'ap-northeast-2' => 'Asia Pacific (Seoul)',
						'ap-southeast-1' => 'Asia Pacific (Singapore)',
						'ap-southeast-2' => 'Asia Pacific (Sydney)',
						'ap-northeast-1' => 'Asia Pacific (Tokyo)',
						'ca-central-1'   => 'Canada (Central)',
						'eu-central-1'   => 'Europe (Frankfurt)',
						'eu-west-1'      => 'Europe (Ireland)',
						'eu-west-2'      => 'Europe (London)',
						'eu-south-1'     => 'Europe (Milan)',
						'eu-west-3'      => 'Europe (Paris)',
						'eu-north-1'     => 'Europe (Stockholm)',
						'me-south-1'     => 'Middle East (Bahrain)',
						'sa-east-1'      => 'South America (São Paulo)'
					]
				],
				'key'       => [
					'label'    => __( 'AWS Key', 'wp-security-audit-log' ),
					'type'     => 'text',
					'required' => true
				],
				'secret'    => [
					'label'    => __( 'AWS Secret', 'wp-security-audit-log' ),
					'type'     => 'text',
					'required' => true
				],
				'token'     => [
					'label' => __( 'AWS Session Token', 'wp-security-audit-log' ),
					'type'  => 'text',
					'desc'  => esc_html__( 'This is optional.', 'wp-security-audit-log' )
				],
				'group'     => [
					'label'      => __( 'Log group name', 'wp-security-audit-log' ),
					'type'       => 'text',
					'validation' => 'cloudWatchGroupName',
					'error'      => sprintf(
						esc_html__( 'Invalid AWS group name. It must satisfy regular expression pattern: %s', 'wp-security-audit-log' ),
						'[\.\-_/#A-Za-z0-9]+'
					),
					'desc'       => sprintf(
						esc_html__( 'If you do not specify a group name, one will be created using the default group name "%s".', 'wp-security-audit-log' ),
						'WP_Activity_Log'
					)
				],
				'stream'    => [
					'label' => __( 'Log stream name', 'wp-security-audit-log' ),
					'type'  => 'text',
					'desc'  => esc_html__( 'If you do not specify a stream name, one will be created using the site name as stream name.', 'wp-security-audit-log' )
				],
				'retention' => [
					'label'   => __( 'Retention', 'wp-security-audit-log' ),
					'type'    => 'select',
					'options' => [
						'0'    => 'indefinite',
						'1'    => '1',
						'3'    => '3',
						'5'    => '5',
						'7'    => '7',
						'14'   => '14',
						'30'   => '30',
						'60'   => '60',
						'90'   => '90',
						'120'  => '120',
						'150'  => '150',
						'180'  => '180',
						'365'  => '365',
						'400'  => '400',
						'545'  => '545',
						'731'  => '731',
						'1827' => '1827',
						'3653' => '3653'
					],
					'desc'    => esc_html__( 'Days to keep logs.', 'wp-security-audit-log' ),
				],
			]
		];
	}

	public function get_monolog_handler() {

		$region    = array_key_exists( 'region', $this->connection ) ? $this->connection['region'] : 'eu-west-1';
		$awsKey    = array_key_exists( 'key', $this->connection ) ? $this->connection['key'] : '';
		$awsSecret = array_key_exists( 'secret', $this->connection ) ? $this->connection['secret'] : '';

		if ( empty( $awsKey ) || empty( $awsSecret ) ) {
			throw new Exception( 'AWS key and secret missing.' );
		}
		$sdkParams = [
			'region'      => $region,
			'version'     => 'latest',
			'credentials' => [
				'key'    => $awsKey,
				'secret' => $awsSecret,
			]
		];

		//  token is optional
		if ( array_key_exists( 'token', $this->connection ) && ! empty( $this->connection['token'] ) ) {
			$sdkParams['credentials']['token'] = $this->connection['token'];
		}

		//  instantiate AWS SDK CloudWatch Logs Client
		$client = new \Aws\CloudWatchLogs\CloudWatchLogsClient( $sdkParams );

		//  log group name, will be created if none
		$groupName = array_key_exists( 'group', $this->connection ) && ! empty( $this->connection['group'] ) ? $this->connection['group'] : 'WP_Activity_Log';

		// log stream name, will be created if none
		$streamName = array_key_exists( 'stream', $this->connection ) && ! empty( $this->connection['stream'] ) ? $this->connection['stream'] : get_bloginfo( 'name' );

		//  days to keep logs, 14 by default. Set to `null` to allow indefinite retention.
		$retentionDays = 14;
		if ( array_key_exists( 'retention', $this->connection ) && strlen( $this->connection['retention'] ) > 0 ) {
			$retentionDays = intval( $this->connection['retention'] );
			if ( $retentionDays <= 0 ) {
				$retentionDays = null;
			}
		}

		//  instantiate handler (tags are optional)
		$handler = new \Maxbanton\Cwh\Handler\CloudWatch( $client, $groupName, $streamName, $retentionDays, 1 );

		//  set the JsonFormatter to be able to access your log messages in a structured way
		$handler->setFormatter( new \WSAL_Vendor\Monolog\Formatter\JsonFormatter() );

		return $handler;
	}

}
