// JavaScript Document
jQuery(document).ready(function(){
	jQuery('#loaderimage').css('display','none'); 
	jQuery('.prod-price-member').click(function(event){
		event.preventDefault();
		var cls = jQuery(this).attr('data-panel');
		jQuery('.prod-price-member').removeClass('exrbp-tab-active');
			jQuery(this).addClass('exrbp-tab-active');
		
		jQuery('.display-tab').css('display','none');
		jQuery('.exrbp-tab-panel-general').css('display','none');
		jQuery('#exrbp-tab-panel-'+cls).css('display','block');
		
	});
	
	jQuery('#single-product-price-set').click(function(){
		
		var val = jQuery('#option-single-product :input').serialize();
		jQuery.ajax({
			 type: 'POST',
			 url: newjs_object.ajaxurl+'?action=set_single_product_price_member',
			 data : {
				 'data' : val	 
			},
			beforeSend : function () 
			{
					jQuery('#loaderimage').css('display','block');			
			},
			success: function(response){
			},
			complete:function()
			{
					jQuery('#loaderimage').css('display','none');
				
			}
		});
	});
	
 
});