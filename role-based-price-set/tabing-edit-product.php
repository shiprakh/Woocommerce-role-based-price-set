
<div id="option-single-product">
 
<div class="exrbp-tabs  exrbp-tabs-left"> 
       <ul class="exrbp-tab-nav">
          <li data-status="no" class="prod-price-member exrbp-tab-general exrbp-tab-active" data-panel="general">
            <a href="#"><i class="dashicons-admin-tools dashicons"></i>General</a>
          </li>
          <input type="hidden" name="post-id" id="post-id" value="<?php echo $id; ?>"  />
          <?php 
		  $args = array(
	'post_type' => 'memberpressproduct',
	'post_per_page' => '-1',
	'order' => 'ASC',
	'orderby' => 'id',
	);
	$plans = get_posts($args);
		  
		  foreach($plans as $row){ ?>
          <li class=" prod-price-member exrbp-tab-<?php echo $row->post_name; ?>" data-panel="<?php echo $row->post_name; ?>">
            <a href="#"><i class="dashicons dashicons-admin-users"></i><?php echo $row->post_title; ?><i class="wc-rbp-tab-status bgblue"></i></a>
           </li>
           <?php }  ?>
         
      </ul>
           
             <?php   $opt = get_post_meta($id,'role-based-price-single',true); ?>
            <div class="exrbp-tab-panels">
            	<div id="exrbp-tab-panel-general" class="display-tab" style="display: block;">
                <div class="wc_rbp_price_container wc_rbp_popup_section wc_rbp_popup_section_general">
                <div class="enable_field_container">
                <p class="form-field ">
                <label class="enable_text" for="enable_role_based_price">Enable Role Based Pricing </label>  
                
             <label class="switch">
  				<input type="checkbox" name="enable-price" id="enable-price" value="enable" <?php if(!empty($opt) && isset($opt['enable-price'])) { echo 'checked';  } ?>>
 					 <span class="slider round"></span>
                    </label>

                </div>
                </div>
                </div>
    
            <?php foreach($plans as $val){
				
				 ?>
            	
                	<div id="exrbp-tab-panel-<?php echo $val->post_name; ?>" class="display-tab" style="display: none;">
                    	 <div class="wc_rbp_price_container wc_rbp_popup_section wc_rbp_popup_section_general">
                         <div class="tab-content">
                         <h3> Set Individual Price for Product   </h3>
                         	<h4> For <?php echo $val->post_title; ?> </h4>
                        	<input type="text" id="<?php echo $val->ID.'-discount'; ?>" name="<?php echo 'member['.$val->ID.'][discount]'; ?>" placeholder = "Discount %" value="<?php if(!empty($opt)){ echo $opt['member'][$val->ID]['discount']; } ?>"/>
                            </div>
                         </div>
                    </div>
                    
            <?php } ?>
            	
            </div>
        </div> 
        
        
        <h2 class="" style="margin: 0px -12px -12px; border-top: 1px solid #eee; text-align:right;">
          <div id="loaderimage">
 	<img src="<?php  echo plugin_dir_url(__FILE__).'js/loading.gif' ?>" width="50px" height="50px"  />
 </div>      
           <?php /*?> <input type="submit" name="submit" class="button button-primary" onclick="javascript:void(0);" value="Save Price"><?php */?>
           <a href="javascript:void(0);"> <div class="button button-primary" id="single-product-price-set">Save Price </div></a>
            </h2>
        
        
        </div>
        