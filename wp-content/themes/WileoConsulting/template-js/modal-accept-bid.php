<?php 
global $wp_query, $ae_post_factory, $post, $user_ID;
$post_object    = $ae_post_factory->get( PROJECT );
$project = $post_object->convert( $post ); 
$post_object_new    = $ae_post_factory->get( BID );
?>
<div class="modal fade" id="acceptance_project" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<i class="fa fa-times"></i>
				</button>
				<h4 class="modal-title">
				<?php
				if(!empty($project->tax_input['project_type'][0]->slug) && $project->tax_input['project_type'][0]->slug == 'time-based'){
				 _e("Accept Application", ET_DOMAIN);
				
				}else{
				 _e("Offer acceptance confirmation", ET_DOMAIN);
				}
				?>
					
				</h4>
			</div>
			<div class="modal-body">
				<form role="form" id="escrow_bid" class="fre-modal-form">
					<div class="escrow-info fre-accept-bid-info">
		            	<!-- bid info content here -->
	                </div>

	            </form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog login -->
</div><!-- /.modal -->
<!-- MODAL BID acceptance PROJECT-->
<script type="text/template" id="bid-info-template">
	<?php
	if(!empty($project->tax_input['project_type'][0]->slug) && $project->tax_input['project_type'][0]->slug == 'time-based'){
		
		$user_info = get_userdata($post_object_new->current_post->post_author);
		
	?>
		<p>
			<?php _e( "Do you want to accept job application?  " , ET_DOMAIN );?> 
		</p>
		<div class="fre-form-btn">
			<button type="submit" class="fre-normal-btn">
	            <?php _e('Accept', ET_DOMAIN) ?>
	        </button>
	        <span class="fre-form-close" data-dismiss="modal"><?php _e('Cancel',ET_DOMAIN);?></span>
		</div>
	<?php
	}else{
	?>
		<p>
			<?php _e( 'You are about to accept this offer for' , ET_DOMAIN ); ?> <strong>{{=budget}}</strong>
			<?php _e( 'This offer acceptance requires the payment below' , ET_DOMAIN ); ?>
		</p>

		<p class="accept-bid-budget">
			<span><?php _e( 'Offer budget' , ET_DOMAIN ); ?></span>
			<span>{{= budget }}</span>
		</p>

		<# if(commission){ #>
		<p class="accept-bid-commision">
			<span><?php _e( 'Commission' , ET_DOMAIN ); ?></span>
			<span>{{= commission }}</span>
		</p>
		<# } #>

		<p class="accept-bid-amount">
			<span><?php _e( 'Total amount' , ET_DOMAIN ); ?></span>
			<span>{{=total}}</span>
		</p>	
	
		<?php  do_action('fre_after_accept_bid_infor'); ?>
		<# if(accept_bid){ #>
			<div class="fre-form-btn">
				<button type="submit" class="fre-normal-btn">
		            <?php _e('Accept Offer', ET_DOMAIN) ?>
		        </button>
		        <span class="fre-form-close" data-dismiss="modal"><?php _e('Cancel',ET_DOMAIN);?></span>
			</div>

		<# }else{ #>
			<div class="fre-form-btn">
				<a class="fre-normal-btn btn-buy-credit" href="#"><?php _e("Make Payment", ET_DOMAIN);?></a>
				<span class="fre-form-close" data-dismiss="modal"><?php _e( 'Cancel',ET_DOMAIN );?></span>
			</div>
		<# } #>
	<?php
	}
	?>
</script>