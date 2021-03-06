<?php

/**
 * Disable gallery zoom at single product pages
 * */
remove_theme_support( 'wc-product-gallery-zoom' );

function show_currency_symbol() {
	global  $woocommerce;
	return get_woocommerce_currency_symbol();
}

/**
 * Edit my account menu order
 */
add_filter ( 'woocommerce_account_menu_items', 'kt_account_menu_order' );
function kt_account_menu_order() {
	$menuOrder = array(
		'dashboard'          => __( 'Dashboard', 'woocommerce' ),
		'orders'             => __( 'Orders', 'woocommerce' ),
		'downloads'          => __( 'Download', 'woocommerce' ),
		'edit-address'       => __( 'Addresses', 'woocommerce' ),
		'edit-account'    	=> __( 'Account Details', 'woocommerce' ),
		'customer-logout'    => __( 'Logout', 'woocommerce' ),
	);
	return $menuOrder;
}

/**
 * Hide shipping rates when free shipping is available.
 */
add_filter( 'woocommerce_package_rates', 'kt_shipping_when_free_is_available', 100 );
function kt_shipping_when_free_is_available( $rates ) {
	$free = array();
	foreach ( $rates as $rate_id => $rate ) {
		if ( 'free_shipping' === $rate->method_id ) {
			$free[ $rate_id ] = $rate;
			break;
		}
	}
	return ! empty( $free ) ? $free : $rates;
}


/**
 * Create Woocommerce logout link and login link for 'primary-menu'
 * */
//add_filter( 'wp_nav_menu_items', 'add_loginout_link', 10, 2 );
function add_loginout_link( $items, $args ) {

	if (is_user_logged_in() && $args->theme_location == 'primary-menu') {
		$items .= '<li class="menu-item menu-item-myaccount"><a href="'. get_permalink( wc_get_page_id( 'myaccount'
			) ) .'">'
		          .__('My Account', kt_textdomain) .'</a></li>';
	}
	elseif (!is_user_logged_in() && $args->theme_location == 'primary-menu') {
		$items .= '<li><a href="' . get_permalink( wc_get_page_id( 'myaccount' ) ) . '">'.__('Login','woocommerce').'</a></li>';
	}
	return $items;

}

/*
 * Added Custom User page and login page
 * */
function kt_add_loginout_icons() {
	if ( ! class_exists( 'woocommerce' ) || ! WC()->cart ) {
		return;
	}
	ob_start();
	$cart_number = WC()->cart->get_cart_contents_count();
	$cart_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : WC()->cart->get_cart_url();
	echo '<ul class="header-loginout-icons">';
		if ( is_user_logged_in() ) {
			echo '<li class="loginout-item loginout-item__myaccount">
						<a href="'. get_permalink( wc_get_page_id( 'myaccount' ) ) .'">
							<svg width="21" height="22" viewBox="0 0 21 22" fill="#58448B" xmlns="http://www.w3.org/2000/svg">
	<path fill="inherit" fill-rule="evenodd" clip-rule="evenodd" d="M6.97109 1.49268C7.99959 0.566258 9.30935 0.19043 10.4992 0.19043C11.6891 0.19043 12.9988 0.566258 14.0273 1.49268C15.0816 2.4423 15.7479 3.88854 15.7479 5.81709V8.88919C15.7479 10.9183 15.0497 12.7771 13.9151 14.0488V14.6494C13.9151 14.9332 13.9257 14.9696 14.0156 15.0648C14.1839 15.2429 14.7033 15.5949 16.1773 15.8885C17.2129 16.0907 18.1705 16.6023 18.9349 17.3617C19.4073 17.831 19.7934 18.3826 20.078 18.9886C20.3609 19.5912 20.2608 20.2366 19.9366 20.7019C19.6238 21.1507 19.1121 21.4281 18.5529 21.4281H2.43646C1.88042 21.4281 1.37063 21.1537 1.05729 20.7081C0.732522 20.2464 0.629008 19.6044 0.906192 19.0019C1.18918 18.3868 1.57757 17.8273 2.0553 17.3527C2.8231 16.5899 3.78782 16.0802 4.82968 15.8868C6.30001 15.6095 6.81424 15.2569 6.97943 15.0804C7.07186 14.9816 7.08328 14.9363 7.08328 14.6494V14.0488C5.94874 12.7771 5.25049 10.9183 5.25049 8.88919V5.81709C5.25049 3.88854 5.91685 2.4423 6.97109 1.49268ZM8.23879 3.03728C7.6588 3.55971 7.19494 4.41754 7.19494 5.81709V8.88919C7.19494 10.4552 7.74006 11.8111 8.51862 12.6744C8.81315 13.0009 9.02772 13.4585 9.02772 13.9838V14.6494C9.02772 14.6551 9.02772 14.6609 9.02773 14.6668C9.02791 15.0279 9.02831 15.8008 8.36791 16.5064C7.76948 17.1459 6.7566 17.5933 5.17309 17.8919L5.17012 17.8924C4.50285 18.016 3.88496 18.3423 3.39326 18.8308C3.22144 19.0015 3.06767 19.1893 2.93379 19.391H18.0519C17.9186 19.1929 17.7664 19.0082 17.597 18.8398C17.1044 18.3505 16.4871 18.0209 15.8197 17.8908L15.8161 17.8901C14.2368 17.5757 13.2292 17.1279 12.6339 16.498C11.9702 15.7958 11.9705 15.0287 11.9707 14.6656L11.9707 13.9838C11.9707 13.4585 12.1853 13.0009 12.4798 12.6744C13.2584 11.8111 13.8035 10.4552 13.8035 8.88919V5.81709C13.8035 4.41754 13.3396 3.55971 12.7596 3.03728C12.1539 2.49166 11.3254 2.22747 10.4992 2.22747C9.67301 2.22747 8.84452 2.49166 8.23879 3.03728Z"/>
	</svg>
							<span class="label">' .__('My Account', kt_textdomain) .'</span>
						</a></li>';
		}
		elseif ( !is_user_logged_in() ) {
			echo '<li class="loginout-item loginout-item__loggedin">
						<a href="' . get_permalink( wc_get_page_id( 'myaccount' ) ) . '">
							<svg width="21" height="22" viewBox="0 0 21 22" fill="#58448B" xmlns="http://www.w3.org/2000/svg">
	<path fill="inherit" fill-rule="evenodd" clip-rule="evenodd" d="M17.4862 0.422852C18.2886 0.422852 19.06 0.755214 19.6261 1.35477C20.1922 1.94781 20.5156 2.7559 20.5156 3.59658V18.4095C20.5156 19.2502 20.1922 20.0583 19.6261 20.6513C19.06 21.2509 18.2886 21.5832 17.4862 21.5832H13.4489C12.8953 21.5832 12.4412 21.114 12.4412 20.5275C12.4412 19.9475 12.8891 19.4718 13.4489 19.4718H17.4862C17.7536 19.4718 18.0149 19.3545 18.2015 19.159C18.3944 18.9569 18.5001 18.6897 18.5001 18.4095V3.6031C18.5001 3.32287 18.3882 3.04916 18.2015 2.85366C18.0087 2.65163 17.7536 2.54085 17.4862 2.54085H13.4489C12.8891 2.54085 12.4412 2.05859 12.4412 1.47859C12.4412 0.898586 12.8891 0.422852 13.4489 0.422852H17.4862ZM14.4576 10.9995C14.4576 11.1429 14.4265 11.2797 14.3767 11.4035C14.327 11.5339 14.2523 11.6512 14.1652 11.7555L9.12025 17.0407C8.72213 17.4512 8.0814 17.4512 7.6895 17.0407C7.29759 16.6236 7.29759 15.9523 7.6895 15.5418L11.0113 12.0552H1.33197C0.778328 12.0552 0.324219 11.586 0.324219 10.9995C0.324219 10.4195 0.772107 9.94376 1.33197 9.94376H11.0051L7.68328 6.46374C7.29137 6.04665 7.29137 5.37541 7.68328 4.96485C8.0814 4.55428 8.72213 4.55428 9.11403 4.96485L14.159 10.2501C14.2523 10.3478 14.327 10.4651 14.3767 10.5954C14.4265 10.7193 14.4576 10.8561 14.4576 10.9995Z"/>
	</svg>
							<span class="label">'.__('Login','woocommerce').'</span>
						</a></li>';
		}

	// Cart Link
		echo '<li class="loginout-item loginout-item__cart">
							<a href="' . $cart_url . '">
							'.($cart_number?'<div class="header-cart-count">'. $cart_number .'</div>':'') .'
			<svg width="22" height="22" viewBox="0 0 22 22" fill="#58448B" xmlns="http://www.w3.org/2000/svg">
	<path fill="inherit" fill-rule="evenodd" clip-rule="evenodd" d="M17.151 1.40534L20.4951 4.7494C21.1361 5.39049 21.5 6.2395 21.5 7.14049V17.987C21.5 18.9053 21.1535 19.7544 20.5124 20.3954C19.8713 21.0365 19.005 21.3831 18.104 21.3831H3.89604C2.97772 21.3831 2.12871 21.0365 1.48762 20.3954C0.846535 19.7544 0.5 18.888 0.5 17.987V7.14049C0.5 6.2395 0.846535 5.39049 1.50495 4.7494L4.83168 1.40534C5.47277 0.746925 6.33911 0.400391 7.2401 0.400391H14.7599C15.6436 0.400391 16.5272 0.764252 17.151 1.40534ZM7.2401 2.46227C6.89356 2.46227 6.56436 2.60089 6.30446 2.84346L4.01733 5.13059H17.9827L15.6955 2.84346C15.4356 2.60089 15.1064 2.46227 14.7426 2.46227H7.2401ZM19.0396 18.9573C19.2995 18.6974 19.4381 18.3509 19.4381 18.0044H19.4035V7.19247H2.52723V18.0044C2.52723 18.3682 2.68317 18.7147 2.92574 18.9573C3.18564 19.2172 3.53218 19.3558 3.87871 19.3558H18.0866C18.4505 19.3558 18.797 19.1999 19.0396 18.9573ZM14.2399 14.1579C13.3736 15.0242 12.23 15.492 10.9998 15.492C9.7696 15.492 8.62603 15.0069 7.7597 14.1579C6.91069 13.2915 6.42554 12.148 6.42554 10.9178C6.42554 10.6406 6.5295 10.3807 6.72009 10.1901C6.91069 9.99947 7.17059 9.89551 7.44781 9.89551C7.72504 9.89551 7.98494 9.99947 8.17554 10.1901C8.36613 10.3807 8.47009 10.6406 8.47009 10.9178C8.47009 11.5935 8.72999 12.2173 9.21514 12.7024C9.70029 13.1876 10.3241 13.4475 10.9998 13.4475C11.6755 13.4475 12.3166 13.1703 12.7845 12.7024C13.2696 12.2173 13.5295 11.5935 13.5295 10.9178C13.5295 10.6406 13.6335 10.3807 13.8241 10.1901C14.2052 9.80887 14.8983 9.80887 15.2795 10.1901C15.4701 10.3807 15.5741 10.6406 15.5741 10.9178C15.5741 12.148 15.1062 13.2915 14.2399 14.1579Z" />
	</svg>
								<span class="label">'.__('Cart','woocommerce').'</span>
							</a></li>';
	echo '</ul>';
	return ob_get_clean();
}

/**
 * Remove product data tabs
 */
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );
function woo_remove_product_tabs( $tabs ) {
	unset( $tabs['description'] );      	// Remove the description tab
	unset( $tabs['reviews'] ); 			// Remove the reviews tab
	unset( $tabs['additional_information'] );  	// Remove the additional information tab
	return $tabs;
}

/**
 * Remove Breadcrumb above Product Main Content
 * */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

/**
 * Added count of product in the cart to the icon in the header
 * */
add_filter( 'woocommerce_add_to_cart_fragments', 'iconic_cart_count_fragments', 10, 1 );
function iconic_cart_count_fragments( $fragments ) {
	$fragments['div.header-cart-count'] = '<div class="header-cart-count">' . WC()->cart->get_cart_contents_count() . '</div>';
	return $fragments;
}

/**
 * Add the code below to your theme's functions.php file
 * to add a confirm password field on the register form under My Accounts.
 */
add_filter('woocommerce_registration_errors', 'woocommerce_registration_errors_validation', 10, 3);
function woocommerce_registration_errors_validation($reg_errors, $sanitized_user_login, $user_email) {
	global $woocommerce;
	extract( $_POST );
	if ( strcmp( $password, $confirm_password ) !== 0 ) {
		return new WP_Error( 'registration-error', __( 'Passwords do not match.', 'woocommerce' ) );
	}
	return $reg_errors;
}

add_action( 'woocommerce_register_form', 'woocommerce_register_form_password_repeat' );
function woocommerce_register_form_password_repeat() {
	?>
	<p class="form-row form-row-wide">
		<label for="confirm_password"><?php _e( 'Confirm password', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="password" class="input-text" name="confirm_password" id="confirm_password" value="<?php if ( ! empty(
			$_POST['confirm_password'] ) ) echo esc_attr( $_POST['confirm_password'] ); ?>" />
	</p>
	<?php
}


// Separete Login form and registration form
//add_action('woocommerce_before_customer_login_form','load_registration_form', 2);
function load_registration_form(){
	if(isset($_GET['action'])=='register'){
		woocommerce_get_template( 'myaccount/form-registration.php' );
	}
}



