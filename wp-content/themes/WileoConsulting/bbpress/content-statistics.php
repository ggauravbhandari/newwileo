<?php

/**
 * Statistics Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Get the statistics
$stats = bbp_get_statistics(); ?>

<dl role="main">

	<?php do_action( 'bbp_before_statistics' ); ?>

	<dd>
		<strong><?php echo esc_html( $stats['user_count'] ); ?></strong>
	</dd>
    <dt><?php esc_html_e( 'Members', 'WileoConsulting' ); ?></dt>

	<dd>
		<strong><?php echo esc_html( $stats['topic_count'] ); ?></strong>
	</dd>
    <dt><?php esc_html_e( 'Threads', 'WileoConsulting' ); ?></dt>

	<dd>
		<strong><?php echo esc_html( $stats['reply_count'] ); ?></strong>
	</dd>
    <dt><?php esc_html_e( 'Replies', 'WileoConsulting' ); ?></dt>

	<?php do_action( 'bbp_after_statistics' ); ?>

</dl>

<?php unset( $stats );