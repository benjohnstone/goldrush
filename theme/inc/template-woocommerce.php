<?php

/**
 * Set WooCommerce image dimensions upon theme activation
 */
// Remove each style one by one
add_filter( 'woocommerce_enqueue_styles', 'goldrush_dequeue_styles' );
function goldrush_dequeue_styles( $enqueue_styles ) {
	//unset( $enqueue_styles['woocommerce-general'] );	// Remove the gloss
	unset( $enqueue_styles['woocommerce-layout'] );		// Remove the layout
	//unset( $enqueue_styles['woocommerce-smallscreen'] );	// Remove the smallscreen optimisation
	return $enqueue_styles;
}

// Or just remove them all in one line
//add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

add_action( 'wp', function(){
    if(get_field('hide_feature_image', get_the_ID() )) {
        remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
    }
} );


function goldrush_custom_post_classes($classes) {
    if(is_singular('product')) {
        array_push($classes, 'entry-content max-w-content mx-auto');
    }
    if(is_singular('product') && get_field('hide_feature_image', get_the_ID() )) {
        array_push($classes, 'hide-feature-image');
    }
    return $classes;
}
add_filter( 'post_class', 'goldrush_custom_post_classes' );


/**
* Change the number of related products
*/

add_filter( 'woocommerce_output_related_products_args', 'goldrush_related_products_args', 20 );
function goldrush_related_products_args( $args ) {
    $args['posts_per_page'] = 4; // 4 related products
    $args['columns'] = 3; // arranged in 2 columns
    return $args;
}

add_filter('loop_shop_columns', function( $columns ) {
		return 3;
	}, 10, 1);

add_action( 'woocommerce_before_single_product', 'goldrush_woocommerce_before_single_product_action' );

function goldrush_woocommerce_before_single_product_action(){
   
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
    add_action( 'woocommerce_single_product_summary', 'goldrush_template_single_long_description', 20 );

    if(get_field('remove_description_tab')) {
        add_filter( 'woocommerce_product_tabs', 'goldrush_remove_desc_tab', 9999 );
    }
    
    if(get_field('image_banner')) {
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
        $img = get_field('image_banner_background');
       
    ?>
    
    <div class="product-image-banner header-content relative overflow-hidden py-10 not-prose flex items-center min-h-[550px] mb-10">
        <?php  echo wp_get_attachment_image($img, 'full', false, array('class' => 'absolute z-0 h-full w-full object-cover object-center max-w-none top-0 left-0 right-0 bottom-0')); ?>
        <div class="absolute bg-black/30 top-0 right-0 bottom-0 left-0 z-10"></div>
        <h1 class="relative z-10 mt-40 text-4xl text-white max-w-content mx-auto px-4 text-center uppercase bold font-mono"><?php echo get_the_title(); ?></h1>
    </div>
    <?php
    }
}

function goldrush_remove_desc_tab( $tabs ) {
    unset( $tabs['description'] );
    return $tabs;
 }

function goldrush_template_single_long_description() {
    ?>
    <div class="prose mb-10 border-b-4 pb-8 border-gray-100"><?php the_content(); ?></div>
    <?php
 }

add_action( 'after_setup_theme', 'goldrush_theme_setup' );
function goldrush_theme_setup() {
    remove_theme_support( 'wc-product-gallery-zoom' );
}

add_filter( 'wc_product_sku_enabled', 'goldrush_remove_product_page_sku' );
  
function goldrush_remove_product_page_sku( $enabled ) {
    if ( ! is_admin() && is_product() ) {
        return false;
    }
    return $enabled;
}

add_filter('woocommerce_account_menu_items', function($items) {
	$logout = $items['customer-logout'];
	unset($items['customer-logout']);
    $items['my-memberships'] = __('My Memberships', 'goldrush');
	$items['customer-logout'] = $logout;
	return $items;
});

// Remove "Select options" button from (variable) products on the main WooCommerce shop page.
add_filter( 'woocommerce_loop_add_to_cart_link', function( $product ) {

	global $product;

    if(has_term('Membership', 'product_cat', $product->get_id()) || $product->get_type() == 'variable') {
        
        $addToCartText = 'Register Now';

        if($product->get_type() == 'variable') {
            $addToCartText = 'Select Options';
        } 
        

        return sprintf( '<a href="%s"  class="%s" %s>%s</a>',
        		esc_url( get_the_permalink($product->get_id()) ),
        		esc_attr( isset( $args['class'] ) ? $args['class'] : 'button-primary' ),
        		isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
        		esc_html( $addToCartText )
        	);
    } else {


        	return sprintf( '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
        		esc_url( $product->add_to_cart_url() ),
        		esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
        		esc_attr( isset( $args['class'] ) ? $args['class'] : 'button-primary' ),
        		isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
        		esc_html( $product->add_to_cart_text() )
        	);
        }

    

} );


add_action('woocommerce_account_my-memberships_endpoint', function() {
	
	$userId = get_current_user_id();
    $subscriptions = wcs_get_users_subscriptions( $userId );

    ?>
    <h2>My Memberships</h2>

    <?php verifyFamilyMembership($subscriptions, 'echo'); ?>
    <?php
    if ( ! empty( $subscriptions ) ) {
        ?>
        <?php
        foreach ( $subscriptions as $subscription ) {
            if ( $subscription->has_status( 'active') || $subscription->has_status( 'on-hold')  ) {
                $orderId = $subscription->get_parent_id();
                $order = wc_get_order($orderId);
                $subscriptionId = $subscription->get_id();
                foreach($order->get_items() as $id => $orderItem) {
                    
                    if($orderItem->get_name() == 'Membership') {
                        if($gfEntries = $orderItem->get_meta('gspc_gf_entry_ids')) {
                            
                            foreach($gfEntries as $index => $entryId ) {
                                
                                goldrush_member_card(
                                    $entryId, 
                                    ucwords($subscription->get_status()), 
                                    'Individual membership',
                                    $subscriptionId
                                );
                            }
                        }
                    }

                    if($orderItem->get_name() == 'Family Membership') {
                        if($gfEntries = $orderItem->get_meta('gspc_gf_entry_ids')) {
                            
                            foreach($gfEntries as $index => $entryId ) {
                                $famEntry = GFAPI::get_entry($entryId);
                                
                                $famMembers = explode(',',$famEntry['3']);
                                foreach($famMembers as $entryId) {
                                    goldrush_member_card(
                                        $entryId, 
                                        ucwords($subscription->get_status()), 
                                        'Family membership',
                                        $subscriptionId);
                                }
                                
                            }
                        }
                    }
                }
            }
        }
    }
    return false;
});

function verifyFamilyMembership($subscriptions, $returnOrEcho) {
    foreach ( $subscriptions as $subscription ) {
        if ( $subscription->has_status( 'active') || $subscription->has_status( 'on-hold')  ) {
            $orderId = $subscription->get_parent_id();
            $order = wc_get_order($orderId);
            $subscriptionId = $subscription->get_id();
            foreach($order->get_items() as $id => $orderItem) {
                
                if($orderItem->get_name() == 'Family Membership') {

                    $adults = array();
                    $youth = array();
                    if($gfEntries = $orderItem->get_meta('gspc_gf_entry_ids')) {
                            
                        foreach($gfEntries as $index => $entryId ) {
                            $famEntry = GFAPI::get_entry($entryId);
                            $famMembers = explode(',',$famEntry['3']);

                            foreach($famMembers as $entryId) {
                                $person = GFAPI::get_entry($entryId);
                                $dob = date_create($person['6']);
                                $today   = new DateTime('today');
                                $age = $dob->diff($today)->y;
                                if($age > 18) $adults[] = array('entry id' => $entryId, 'age' => $age, 'subscription id' => $subscriptionId);
                                if($age <= 18) $youth[] = array('entry id' => $entryId, 'age' => $age, 'subscription id' => $subscriptionId);

                            }
                        }
                    }
                    if(sizeof($adults) > 2): ?>
                    <?php if($returnOrEcho == 'return'){
                        return false;
                    } ?>
                        <?php if($returnOrEcho == 'echo'): ?>
                        <?php ob_start(); ?>
                        
                            Your family membership has too many adults to qualify as a Family Membership. We have put this membership on hold until you decide what to do:
                            <ul>
                                <li>
                            </ul>
                            

                            <p>You can cancel your Family membership renewal here. <a href="/my-account/view-subscription/<?php echo $subscriptionId; ?>/" class="button">Manage subscription</a></p>

                            <p>Register a new Adult membership here. <a href="/product/membership/" class="button">New Membership</a></p>
                        <?php $message = ob_get_clean(); 
                        wc_add_notice( __($message, 'goldrush'), 'error' );
                            endif;
                        ?>

                    <?php
                    endif;
               
                }
            }
        }
    }
}

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

add_action('init', function() {
	add_rewrite_endpoint('my-memberships', EP_ROOT | EP_PAGES);
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
});

add_action( 'woocommerce_account_dashboard', 'goldrush_woocommerce_account_dashboard_action' );

function goldrush_woocommerce_account_dashboard_action(){
    
    ?>
    <a href="/my-account/my-memberships/" class="button-primary">View your memberships</a>
    <?php

}

function goldrush_member_card($gfEntryId, $status, $membershipType, $subscriptionId) {
    
    if($entry = GFAPI::get_entry($gfEntryId)):
    
    $created = date_create($entry['date_created']);
    $interestsField = GFFormsModel::get_field( 1, 11 );
    $interests = is_object( $interestsField ) ? $interestsField->get_value_export( $entry ) : '';
    $dob = date_create($entry['6']);
    $memberId = $subscriptionId . '-' . $gfEntryId;
    ?>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <div class="shop-table-border mb-4 text-base not-prose">

        <div class="p-4 -mt-4 -ml-4 -mr-4 mb-5 <?php if($status == 'Active'): ?>bg-primary<?php else: ?>bg-concrete-700<?php endif; ?> text-white rounded-t-md md:flex items-center">
            <div class="font-bold text-lg"><?php echo $entry['1.3'] . ' ' . $entry['1.6']; ?></div>
            <div class="flex items-center md:ml-auto md:mr-0 flex-nowrap">
                <div class="md:ml-auto mr-0"><?php echo $membershipType; ?></div>
                <a href="/my-account/view-subscription/<?php echo $subscriptionId; ?>/" class="ml-5 mr-0 flex items-center block border border-white rounded-md py-2 px-3" data-tooltip-target="tooltip-hover-<?php echo $gfEntryId; ?>" data-tooltip-trigger="hover">
                
                    <?php if($status == 'Active'): ?>
                        <svg viewBox="0 0 16.3 12.03" class="fill-white w-4 mr-1"><path d="M5.7,12.03L0,6.33l1.43-1.43,4.28,4.28L14.88,0l1.43,1.43L5.7,12.03Z"/></svg>
                    <?php else: ?>   
                        <svg viewBox="0 0 14 14" class="fill-white w-4 mr-1"><path d="M1.4,14l-1.4-1.4,5.6-5.6L0,1.4,1.4,0l5.6,5.6L12.6,0l1.4,1.4-5.6,5.6,5.6,5.6-1.4,1.4-5.6-5.6L1.4,14Z"/></svg> 
                    <?php endif; ?>

                    <?php echo $status; ?>
                </a>

                <div id="tooltip-hover-<?php echo $gfEntryId; ?>" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm text-center text-white max-w-40 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Click here to manage your membership subscription
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>

                
            </div>
        </div>
        <div class="md:flex items-center">
            <div class="w-40 py-2">Member Id:</div>
            <div class="field-value "><div class="current-value p-2 border border-white"><?php echo $memberId; ?></div></div>
        </div>
        <div class="md:flex items-center">
            <div class="w-40 py-2"><label for="<?php echo $gfEntryId; ?>-phone">Phone:</label></div>
                <form class="editable-field-wrapper flex items-center gap-x-1" data-field-name="phone" data-gf-field-id="4" data-gf-entry-id="<?php echo $gfEntryId; ?>">
                    <div class="field-value">
                        <input id="<?php echo $gfEntryId; ?>-phone" type="text" value="<?php echo $entry['4']; ?>" class="editable-field p-2 focus:outline-primary/60 focus:outline-2 focus:outline-offset-1 border-0 border-transparent focus:border-transparent focus:bg-concrete rounded-sm" />
                    </div> 
                    <svg class="field-spinner hidden" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><style>.spinner_ajPY{transform-origin:center;animation:spinner_AtaB .75s infinite linear}@keyframes spinner_AtaB{100%{transform:rotate(360deg)}}</style><path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25"/><path d="M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z" class="spinner_ajPY"/></svg>
                    <label class="text-concrete-800 text-sm info-label" for="<?php echo $gfEntryId; ?>-phone">click to edit</label>
                    <button type="submit" class="save-field-btn text-sm rounded-sm bg-concrete border border-concrete-600 px-3 py-2 text-black/40 mx-2 hidden">save</button>
                    <span class="check-mark material-symbols-outlined opacity-0 transition-all duration-300 translate-y-5 text-primary">done</span>
                </form>
        </div>
        <div class="md:flex items-center">
            <div class="w-40 py-2">Date of birth:</div>
            <?php $today   = new DateTime('today'); ?>
            <div class="field-value"><div class="current-value p-2 border border-white"><?php echo date_format($dob, 'j M, Y'); ?> (<?php echo $dob->diff($today)->y; ?> Years old)</div></div>
        </div>
        <div class="">
            <?php 
                $interestsArr = explode(', ', $interests); 
                $interestsField = GFAPI::get_field(1, 11);
                
            ?>

            <div class="w-40 py-2">Interests: <label class="text-concrete-800 text-sm info-label" for="<?php echo $gfEntryId; ?>-phone">(click to edit)</label></div> 
            <form class="editable-field-wrapper interests" data-field-name="interests" data-gf-field-id="11" data-gf-entry-id="<?php echo $gfEntryId; ?>">
                <div class="field-value">
                    <div class="current-value p-2 border border-white">
                        
                        <?php foreach($interestsField['choices'] as $index => $interest): ?>
                           
                            <div class="checkbox-wrap">
                                <input type="checkbox" id="<?php echo $gfEntryId; ?>-interests-<?php echo $index; ?>" name="interests.<?php echo $index; ?>" value="<?php echo $interest['value']; ?>" <?php if(in_array( $interest['value'], $interestsArr)): ?>checked<?php endif; ?>  />
                                <label for="<?php echo $gfEntryId; ?>-interests-<?php echo $index; ?>"><?php echo $interest['text']; ?></label>
                            </div>
                        <?php endforeach; ?>
                            
                    </div>
                    <svg class="field-spinner hidden" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><style>.spinner_ajPY{transform-origin:center;animation:spinner_AtaB .75s infinite linear}@keyframes spinner_AtaB{100%{transform:rotate(360deg)}}</style><path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25"/><path d="M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z" class="spinner_ajPY"/></svg>
                    
                    <button type="submit" class="save-field-btn text-sm rounded-sm bg-concrete border border-concrete-600 px-3 py-2 text-black/40 mx-2 hidden">save</button>
                    <span class="check-mark material-symbols-outlined opacity-0 transition-all duration-300 translate-y-5 text-primary">done</span>
                
                </div>
                
                </form>
        </div>
        <div class="border border-concrete-600 p-4 pb-2 rounded-md my-4">
            <div class="font-bold -mt-7"><span class="bg-white px-2  -ml-2">Emergency Contact</span></div>
            <div class="md:flex items-center">
                <div class="w-40 py-2"><label for="<?php echo $gfEntryId; ?>-emergency-name">Name:</label></div>
                <form class="editable-field-wrapper flex items-center gap-x-1" data-field-name="emergency-contact-name" data-gf-field-id="9" data-gf-entry-id="<?php echo $gfEntryId; ?>">
                    <div class="field-value">
                        <input id="<?php echo $gfEntryId; ?>-emergency-name" type="text" value="<?php echo $entry['9']; ?>" class="editable-field p-2 focus:outline-primary/60 focus:outline-2 focus:outline-offset-1 border-0 border-transparent focus:border-transparent focus:bg-concrete rounded-sm" />
                    </div> 
                    <svg class="field-spinner hidden" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><style>.spinner_ajPY{transform-origin:center;animation:spinner_AtaB .75s infinite linear}@keyframes spinner_AtaB{100%{transform:rotate(360deg)}}</style><path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25"/><path d="M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z" class="spinner_ajPY"/></svg>
                    <label for="<?php echo $gfEntryId; ?>-emergency-name" class="text-concrete-800 text-sm info-label">click to edit</label>
                    <button type="submit" class="save-field-btn text-sm rounded-sm bg-concrete border border-conrete-600 px-3 py-2 text-black/40 mx-2 hidden">save</button>
                    <span class="check-mark material-symbols-outlined opacity-0 transition-all duration-300 translate-y-5 text-primary">done</span>
                </form>
            </div>
            <div class="md:flex items-center">
                <div class="w-40 py-2"><label for="<?php echo $gfEntryId; ?>-emergency-phone">Phone:</label></div>
                <form class="editable-field-wrapper flex items-center gap-x-1" data-field-name="emergency-contact-phone" data-gf-field-id="10" data-gf-entry-id="<?php echo $gfEntryId; ?>">
                    <div class="field-value">
                        <input id="<?php echo $gfEntryId; ?>-emergency-phone" type="text" value="<?php echo $entry['10']; ?>" class="editable-field p-2 focus:outline-primary/60 focus:outline-2 focus:outline-offset-1 border-0 border-transparent focus:border-transparent focus:bg-concrete rounded-sm" />
                    </div> 
                    <svg class="field-spinner hidden" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><style>.spinner_ajPY{transform-origin:center;animation:spinner_AtaB .75s infinite linear}@keyframes spinner_AtaB{100%{transform:rotate(360deg)}}</style><path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25"/><path d="M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z" class="spinner_ajPY"/></svg>
                    <label for="<?php echo $gfEntryId; ?>-emergency-phone" class="text-concrete-800 text-sm info-label">click to edit</label>
                    <button type="submit" class="save-field-btn text-sm rounded-sm bg-concrete border border-conrete-600 px-3 py-2 text-black/40 mx-2 hidden">save</button>
                    <span class="check-mark material-symbols-outlined opacity-0 transition-all duration-300 translate-y-5 text-primary">done</span>
                </form>
            </div>
        </div>
        
        <div class="md:flex py-2 bg-concrete flex items-center px-4">
            <div>Waiver signed <?php if($signedby = $entry['22']): ?>by <?php echo $signedby; ?> <?php endif; ?> on <?php echo date_format($created, 'j M, Y'); ?> </div>
            <div><img src="/wp-content/uploads/gravity_forms/signatures/<?php echo $entry['12']; ?>" width="80" class="mx-4"></div>
        </div>
        
    </div>
    <?php
    endif;
}

/**
 * delete order hook
 */
//add_action('before_delete_post', 'goldrush_delete_order', 10, 1);

function goldrush_delete_order($orderId) {
    write_log($orderId);
    $post_type = get_post_type($orderId);

    if ($post_type !== 'shop_order') {
        return;
    }

    $order = new WC_Order($id);
    foreach($order->get_items() as $id => $orderItem) {
        
        if($orderItem->get_name() == 'Membership') { // single membership
            if($gfEntries = $orderItem->get_meta('gspc_gf_entry_ids')) {
                
                if($gfEntries) {
                    write_log($gfEntries);
                    // foreach($gfEntries as $index => $entryId ) {
                    //     $entry = GFAPI::get_entry($entryId);
                    //     $entry['status'] = 'trash';
                    //     //GFAPI::update_entry($entry);
                        
                    // }
                }
                
            }
        }

        if($orderItem->get_name() == 'Family Membership') {

            // $valid = verifyFamilyMembership(array($subscription), 'return');
             if($gfEntries = $orderItem->get_meta('gspc_gf_entry_ids')) {
                write_log($gfEntries);
                //  foreach($gfEntries as $index => $entryId ) {
                //      $famEntry = GFAPI::get_entry($entryId);
                //      $famEntry['status'] = 'trash';
                //      GFAPI::update_entry($famEntry);
 
                //      $famMembers = explode(',',$famEntry['3']);
                //      foreach($famMembers as $entryId) {
                //          $entry = GFAPI::get_entry($entryId);
                //          $entry['status'] = 'trash';
                //          GFAPI::update_entry($entry);
                //      }
                     
                //  }
             }
         }
    }

}

add_action('woocommerce_subscription_status_on-hold', function($subscription){
    $orderId = $subscription->get_parent_id();
    $order = wc_get_order($orderId);
    
    //$valid = verifyFamilyMembership(array($subscription), 'return');
    foreach($order->get_items() as $id => $orderItem) {
        
        if($orderItem->get_name() == 'Membership') { // single membership
            if($gfEntries = $orderItem->get_meta('gspc_gf_entry_ids')) {
                
                if($gfEntries) {
                    foreach($gfEntries as $index => $entryId ) {
                        $entry = GFAPI::get_entry($entryId);
                        $entry['status'] = 'trash';
                        GFAPI::update_entry($entry);
                        
                    }
                }
                
            }
        }

        if($orderItem->get_name() == 'Family Membership') {

            // $valid = verifyFamilyMembership(array($subscription), 'return');
             if($gfEntries = $orderItem->get_meta('gspc_gf_entry_ids')) {
                 
                 foreach($gfEntries as $index => $entryId ) {
                     $famEntry = GFAPI::get_entry($entryId);
                     $famEntry['status'] = 'trash';
                     GFAPI::update_entry($famEntry);
 
                     $famMembers = explode(',',$famEntry['3']);
                     foreach($famMembers as $entryId) {
                         $entry = GFAPI::get_entry($entryId);
                         $entry['status'] = 'trash';
                         GFAPI::update_entry($entry);
                     }
                     
                 }
             }
         }
    }
});


/** hook for when an on-hold subscription changes to active  */
add_action('woocommerce_subscription_status_active', function($subscription){
    $orderId = $subscription->get_parent_id();
    $order = wc_get_order($orderId);
    
});


add_action( 'woocommerce_checkout_subscription_created', 'goldrush_subscription_end_date', 10, 3 );
function goldrush_subscription_end_date($subscription, $order, $recurring_cart) {
    
    if ( function_exists( 'wcs_order_contains_subscription' ) && wcs_order_contains_subscription( $order ) ) {
        

        $subscriptionId = $subscription->get_id();
  
    foreach($order->get_items() as $id => $orderItem) {

        /**
         * Check each family member to set subscription end date correctly
         */
        if($orderItem->get_name() == 'Family Membership') {
            

            $date_types = array('start', 'trial_end', 'next_payment', 'last_order_date_paid', 'end');
            $dates      = array(); // Initializing
            
            foreach( $date_types as $date_type ) {
                $dates[$date_type] = $subscription->get_date($date_type);
            }

           // $valid = verifyFamilyMembership(array($subscription), 'return');
            if($gfEntries = $orderItem->get_meta('gspc_gf_entry_ids')) {
                
                foreach($gfEntries as $index => $entryId ) {
                    $famEntry = GFAPI::get_entry($entryId);
                    $famEntry['status'] = 'active';
                    GFAPI::update_entry($famEntry);

                    $famMembers = explode(',',$famEntry['3']);
                    
                    foreach($famMembers as $entryId) {
                        $entry = GFAPI::get_entry($entryId);
                        $entry['status'] = 'active';
                        GFAPI::update_entry($entry);
                    }
                    $adults = array();
                    $youth = array();
                    foreach($famMembers as $entryId) {
                        $person = GFAPI::get_entry($entryId);
                        $dob = date_create($person['6']);
                        $today   = new DateTime('today');
                        $age = $dob->diff($today)->y;
                        if($age > 18) $adults[] = array('entry id' => $entryId, 'age' => $age, 'subscription id' => $subscriptionId);
                        if($age <= 18) $youth[] = array('entry id' => $entryId, 'age' => $age, 'subscription id' => $subscriptionId);
                    }
                    
                   

                    usort($youth, function($a, $b) {
                        return $b['age'] - $a['age'];
                    });

                    if(sizeof($adults) == 2 && sizeof($youth) > 0) {
                        // 2 adults already, when the oldest youth turns 18 that will be last year of membership
                        $oldestYouth = $youth[0];
                        $oldestYouthYearsLeft = 18 - $oldestYouth['age'];
                        
                        $endDate = (date("Y") + $oldestYouthYearsLeft) . '-12-31 00:00:00';
                        
                        $dates['end'] = $endDate;
                    }

                    if(sizeof($adults) == 1 && sizeof($youth) > 1) {
                        // 1 adults already, when the second oldest youth turns 18 that will be last year of membership
                        $oldestYouth = $youth[1];
                        $oldestYouthYearsLeft = 18 - $oldestYouth['age'];
                        
                        $endDate = (date("Y") + $oldestYouthYearsLeft) . '-12-31 00:00:00';
                        
                        $dates['end'] = $endDate;
                    }

                    if(sizeof($adults) == 0 && sizeof($youth) > 2) {
                        // 1 adults already, when the second oldest youth turns 18 that will be last year of membership
                        $oldestYouth = $youth[2];
                        $oldestYouthYearsLeft = 18 - $oldestYouth['age'];
                        
                        $endDate = (date("Y") + $oldestYouthYearsLeft) . '-12-31 00:00:00';
                        
                        $dates['end'] = $endDate;
                    }
                    
                }
            }

            $subscription->update_dates($dates);
            
        }

        
    }
        
       
    } 
}