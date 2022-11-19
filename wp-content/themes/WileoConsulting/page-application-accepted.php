<?php
/**
 *    Template Name: Application Accepted
 */

$session = et_read_session(); 
global $ad, $payment_return, $order_id, $user_ID;

$payment_type = get_query_var( 'paymentType' );
if ( $payment_type == 'usePackage' || $payment_type == 'free' ) {
	$payment_return = ae_process_payment( $payment_type, $session );
	if ( $payment_return['ACK'] ) {
		$project_url = get_the_permalink( $session['ad_id'] );
		// Destroy session for order data
		et_destroy_session();
		// Redirect to project detail
		wp_redirect( $project_url );
		exit;
	}
}

/**
 * get order
 */
$order_id = isset( $_GET['order-id'] ) ? $_GET['order-id'] : '';

if ( empty( $order_id ) && isset( $_POST['orderid'] ) ) {
	$order_id = $_POST['orderid'];
}

$order      = new AE_Order( $order_id );
global $order_data;
$order_data = $order->get_order_data();
// echo '<pre>';
// var_dump($order_data);
// var_dump($user_ID);
// var_dump($order);
// echo '</pre>';

if ( ( $payment_type == 'paypaladaptive' || $payment_type == 'frecredit' || $payment_type == 'stripe' ) && ! $order_id ) {

	// esroww
	//frecredit --> accept bid.

	$payment_return  = fre_process_escrow( $payment_type, $session );
	
	$payment_return  = wp_parse_args( $payment_return, array( 'ACK' => false, 'payment_status' => '' ) );


	extract( $payment_return );
	if ( (isset( $ACK ) && $ACK )):

		
		//change charge status transaction accept bid to pending from ver 1.8.2
		do_action( 'fre_change_status_accept_bid', $session['payKey'] );

		// Accept bid
		$ad_id 		 = $session['ad_id'];
		$order_id    = $session['order_id'];
		$permalink   = get_permalink( $ad_id );
		$permalink   = add_query_arg( array( 'workspace' => 1 ), $permalink );
		$workspace   = '<a href="' . $permalink . '">' . get_the_title( $ad_id ) . '</a>';
		$bid_id      = get_post_field( 'post_parent', $order_id );
		$bid_budget  = get_post_meta( $bid_id, 'bid_budget', true );
		$content_arr = array(
			'paypaladaptive' => __( 'Paypal', ET_DOMAIN ),
			'frecredit'      => __( 'Credit', ET_DOMAIN ),
			'stripe'         => __( 'Stripe', ET_DOMAIN )
		);

		// get commission settings
		$commission     = ae_get_option( 'commission', 0 );
		$commission_fee = $commission;

		// caculate commission fee by percent
		$commission_type = ae_get_option( 'commission_type' );
		if ( $commission_type != 'currency' ) {
			$commission_fee = ( (float) ( $bid_budget * (float) $commission ) ) / 100;
		}

		$commission          = fre_price_format( $commission_fee );
		$payer_of_commission = ae_get_option( 'payer_of_commission', 'project_owner' );
		if ( $payer_of_commission == 'project_owner' ) {
			$total = (float) $bid_budget + (float) $commission_fee;
		} else {
			$commission = 0;
			$total      = $bid_budget;
		}

		get_header();
		?>
        <div class="fre-page-wrapper">
            <div class="fre-page-title">
                <div class="container">
                    <h2><?php the_title(); ?></h2>
                </div>
            </div>
            <div class="fre-page-section">
                <div class="container">
                    <div class="page-purchase-package-wrap">
                        <div class="fre-purchase-package-box">
                            <div class="step-payment-complete">
                                <h2><?php _e( "Application accepted ", ET_DOMAIN ); ?></h2>
                                <p><?php _e( "Thank you. You have accepted consultant offer successfully.", ET_DOMAIN ); ?></p>
                               
                                <div class="fre-view-project-btn">
                                    <a class="fre-btn"
                                       href="<?php echo $permalink; ?>"><?php _e( "Move now", ET_DOMAIN ); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php
		get_footer();
	else: //ACK Fail

		// Redirect to 404
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		get_template_part( 404 );
		exit();
	endif;
} else {
	// Redirect to 404
	global $wp_query;
	$wp_query->set_404();
	status_header( 404 );
	get_template_part( 404 );
	exit();
}

