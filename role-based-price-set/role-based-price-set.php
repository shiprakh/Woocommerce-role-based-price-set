<?php 
/** 
 * Plugin Name:       WooCommerce Role Based Price Set
 * Description:       Sell product in different price for different membership plans based on your settings.
 * Version:           3.3.2
 * Author:            Expert Web Technologies 
 */


function set_price_enqueue_admin_style() 
{
	wp_enqueue_style( 'style', plugin_dir_url(__FILE__) . 'css/customprice.css' );
	wp_enqueue_script( 'newjs', plugin_dir_url(__FILE__) . 'js/customprice.js' );
	wp_localize_script('newjs','newjs_object',array(
		'ajaxurl' => admin_url('admin-ajax.php')
	)
	);
}

add_action( 'admin_enqueue_scripts', 'set_price_enqueue_admin_style' );


add_action('admin_menu', 'woo_admin_menu_set_price');
function woo_admin_menu_set_price()
{
	add_submenu_page('woocommerce','Role Based Set Price','Role Based Set Price','manage_woocommerce','set-price','set_price_member');
	
}
function set_price_member()
{
	$args = array(
	'post_type' => 'memberpressproduct',
	'post_per_page' => '-1',
	'order' => 'ASC',
	'orderby' => 'id',
	);
	$plans = get_posts($args);
	
	if(isset($_POST['submit']))
	{
		update_option('member',$_POST['member']);	
	}
	
	?>
    
    
    <form method="post" action="">
     <div class="row">
    <h3> General Settings </h3>
   
    	<table class="set_settings_table">
        	<tbody>
    <?php
	$opt = get_option('member');
	foreach($plans as $row)
	{
	
		//print_r($row);
		$price =  get_post_meta($row->ID,'_mepr_product_price',true);	
		$dur = get_post_meta($row->ID,'_mepr_product_period_type',true);
		
	
	?>
     		 <tr>
            <th scope="row">
            <label>	<?php echo $row->post_title; ?> 
            <p class="description">$<?php echo $price; ?>/ <?php echo $dur; ?></p></label>
            </th>
            <td>
            	<input type="text" id="<?php echo $row->ID.'-discount'; ?>" name="<?php echo 'member['.$row->ID.'][discount]'; ?>" placeholder = "Discount %" value="<?php echo $opt[$row->ID]['discount']; ?>"/>
                 <p class="description"> Enter Discount allowed to members </p>
            </td>
           
            </tr>
  <?php          
       }
	?>     
            </tbody></table>
            <input type="submit" name="submit" id="submit" value="Save Changes" class="button button-primary" />
    </div>
    </form>
<?php	
}

function set_product_price_metabox() {
   add_meta_box('set-product-price-editor','Role Based Price Set Editor', 
            'edit_product_metabox_callback', 'product', 'advanced', 'high');
}
add_action( 'add_meta_boxes', 'set_product_price_metabox' );
function edit_product_metabox_callback($post)
{
		global $id , $post;
	if( is_object($post) ) {
            $id = $post->ID;
        } else {
            $id = $post;
        }
		
	 include('tabing-edit-product.php');
}

add_action('wp_ajax_nopriv_set_single_product_price_member', 'set_single_product_price_member');
add_action('wp_ajax_set_single_product_price_member', 'set_single_product_price_member');
function set_single_product_price_member()
{
	
	$data = $_POST['data'];
	 parse_str($data,$arr);
	
	update_post_meta($arr['post-id'],'role-based-price-single',$arr);
	 exit;
	
	
}
function return_custom_price($price, $product) {
	if(is_admin())
		return $price;
	
	if( in_array('administrator',  wp_get_current_user()->roles))
		return $price;
		
 global $post;
	
$id = $product->get_id();
$regular_price = get_post_meta( $id, '_regular_price', true );
//$price = $product->get_sale_price();

	$opt = get_post_meta($post->ID,'role-based-price-single',true);
	
	$val = get_option('member');

	//  if ($product->is_type( 'simple' )) { 
	// $price = get_post_meta($post->ID, '_regular_price',true);
	
	//  }
	
		if(!empty($val)){
		
		foreach($val as $key => $row){
				
			if(current_user_can('mepr-active','memberships:'.$key)){
					
					if(!empty($opt)){
						if(isset($opt['enable-price']) && !empty($opt['enable-price']))	
						{
							$dis = $opt['member'][$key]['discount'];
						}
					}
					else 
					{
						$dis = $val[$key]['discount'];
					}
					$discount = ($regular_price*$dis/100);
					 wc_delete_product_transients($product->get_id());
					return $price = $regular_price - $discount;
					
			}
		}
		
		}
	
    //return $price;
}
add_filter('woocommerce_product_get_price', 'return_custom_price', 10, 2);
add_filter( 'woocommerce_product_get_sale_price', 'return_custom_price', 99, 2 );


//add_filter('woocommerce_product_variation_get_regular_price', 'custom_price', 99, 2 );
//add_filter('woocommerce_product_variation_get_price', 'custom_price' , 99, 2 );

// Variations (of a variable product)
add_filter('woocommerce_variation_prices_price', 'custom_variation_price', 99, 3 );
add_filter('woocommerce_variation_prices_sale_price', 'custom_variation_price', 99, 3 );



function custom_variation_price( $price, $variation, $product ) {
    if(is_admin())
		return $price;
	
	if( in_array('administrator',  wp_get_current_user()->roles))
		return $price;
 global $post;
	
	$price = $variation->get_regular_price();
	$opt = get_post_meta($post->ID,'role-based-price-single',true); 
$val = get_option('member');
    if(!empty($val)){
		
		foreach($val as $key => $row){
				
			if(current_user_can('mepr-active','memberships:'.$key)){
					
					if(!empty($opt)){
						if(isset($opt['enable-price']) && !empty($opt['enable-price']))	
						{
							$dis = $opt['member'][$key]['discount'];
						}
					}
					else 
					{
						$dis = $val[$key]['discount'];
					}
    				$discount = ($price*$dis/100);
					 wc_delete_product_transients($variation->get_id());
					return $price - $discount;
					
			}
		}
		
		}
	
    return $price;
}
