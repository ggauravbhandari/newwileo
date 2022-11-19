<?php
global $order_data, $ad, $project_id, $order_id;
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
                        <h2><?php _e( "Payment Successfully Completed", ET_DOMAIN ); ?></h2>
                        <p><?php _e( "Thank you. Your payment has been received and the process is now being run.", ET_DOMAIN ); ?></p>
                        <div class="fre-table">
                            <div class="fre-table-row">
                                <div class="fre-table-col fre-payment-id"><?php _e( "Invoice No:", ET_DOMAIN ); ?></div>
                                <div class="fre-table-col"><?php echo $order_data['ID']; ?></div>
                            </div>
                            <div class="fre-table-row">
                                <div class="fre-table-col fre-payment-date"><?php _e( "Date:", ET_DOMAIN ); ?></div>
                                <div class="fre-table-col"><?php echo get_the_date( get_option( 'date_format' ), $order_id ); ?></div>
                            </div>
                            <div class="fre-table-row">
                                <div class="fre-table-col fre-payment-type"><?php _e( "Payment Type:", ET_DOMAIN ); ?></div>
                                <div class="fre-table-col"><?php echo $order_data['payment']; ?></div>
                            </div>
                            <div class="fre-table-row">
                                <div class="fre-table-col fre-payment-total"><?php _e( "Total:", ET_DOMAIN ); ?></div>
                                <div class="fre-table-col"><?php echo fre_order_format( $order_data['total'], $order_data['currency'] ); ?></div>
                            </div>
                        </div>
                        <div class="fre-view-project-btn">
                            <!-- <p><?php _e( "Your project detail is now available for you to view.", ET_DOMAIN ); ?></p>
							<a class="fre-btn" href="<?php //echo $permalink;?>"><?php //_e("Move now", ET_DOMAIN);?></a> -->
							<?php
							if ( isset( $order_data['products'] ) ) {
								$product = current( $order_data['products'] );
								$type    = $product['TYPE'];

								switch ( $type ) {
									case 'bid_plan':
										// buy bid
										if ( $project_id ) {
											$permalink = get_the_permalink( $project_id );
										} else {
											$permalink = et_get_page_link( 'my-project' );
										}
										echo "<p>" . __( 'Now you can return to the project pages', ET_DOMAIN ) . "</p>";
										echo "<a class='fre-btn' href='" . $permalink . "'>" . __( 'Return', ET_DOMAIN ) . "</a>";
										break;
									case 'fre_credit_plan':
										// deposit credit
										if ( $project_id ) {
											$permalink = get_the_permalink( $project_id );
											echo "<p>" . __( 'Return to Project page', ET_DOMAIN ) . "</p>";
										echo "<a class='fre-btn' href='" . $permalink . "'>" . __( 'Click here', ET_DOMAIN ) . "</a>";
										} else {
											$permalink = et_get_page_link( 'my-reports' );
											echo "<p>" . __( 'Return to My Reports Page', ET_DOMAIN ) . "</p>";
											echo "<a class='fre-btn' href='" . $permalink . "'>" . __( 'Click here', ET_DOMAIN ) . "</a>";
										}

										break;
									case 'fre_credit_fix':
										// deposit credit
										if ( $ad ) {

											$permalink = get_the_permalink( $ad->post_parent );
											echo "<p>" . __( 'Return to Project page', ET_DOMAIN ) . "</p>";
											echo "<a class='fre-btn' href='" . $permalink . "'>" . __( 'Click here', ET_DOMAIN ) . "</a>";
										} else {

											$permalink = et_get_page_link( 'my-reports' );
											echo "<p>" . __( 'Return to My Reports Page', ET_DOMAIN ) . "</p>";
											echo "<a class='fre-btn' href='" . $permalink . "'>" . __( 'Click here', ET_DOMAIN ) . "</a>";
										}

										break;

									default:

										if ( $order_data['status'] == 'publish' ) { //Buy package
											echo "<p>" . __( 'Click the button below to be redirected to the previous page', ET_DOMAIN ) . "</p>";
											echo "<a class='fre-btn' href='" . et_get_page_link( 'my-project' ) . "'>" . __( 'Go', ET_DOMAIN ) . "</a>";
										} else  if ( $ad ) { // Submit project
											$permalink = get_the_permalink( $ad->ID );
											echo "<p>" . __( 'Your project details is now available for you to view', ET_DOMAIN ) . "</p>";
											echo "<a class='fre-btn' href='" . $permalink . "'>" . __( 'Go', ET_DOMAIN ) . "</a>";
										}
										break;
								}
							}
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>