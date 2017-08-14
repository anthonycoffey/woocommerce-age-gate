<?php
/**
 * Plugin Name: WooCommerce Age Gate
 * Description: Appends a simple input field to checkout that is required, with custom error message if input is invalid.
 * Author: Anthony Coffey
 * Author URI: http://coffeywebdev.com
 * Version: 1.0
 */
 
if ( ! defined( 'ABSPATH' ) )
  die("You don't have sufficient permission to access this page");


class WoocommerceAgeGate {
  public function __construct() {

    /* Add the field to the checkout */
    add_action( 'woocommerce_after_checkout_billing_form', array($this,'age_gate_field') );

    /* add validation for age field */
    add_action('woocommerce_checkout_process', array($this,'age_validate_checkout_field') );

  }


  public function age_gate_field( $checkout ) {
    // Add field
    echo '<h3 for="age">How old are you?</h3>';
    woocommerce_form_field( 'age', array(
      'type'          => 'text',
      'class'         => array('input-text'),
      'placeholder'   => __('Enter your age'),
      'required'          => true,
      'maxlength'         => 2,
    ), $checkout->get_value( 'age' ));

  }



  public function age_validate_checkout_field() {
  
    // get age from form data
    $age = filter_input(INPUT_POST, 'age');
    
    // remove all characters from field except numbers
    $age = preg_replace("/[^0-9]/","",$age);
 
    if(strlen($age)>0) {
    // if age is less than 13, push error
      if ($age < 13) {
        wc_add_notice(__('<strong>Invalid Age</strong>, you must be at least 13 years of age to purchase the item(s) you have selected.'), 'error');
      }
    } else {
      wc_add_notice(__('<strong>Age</strong> is a required field.'), 'error');
    }
  }


}


// if woocommerce active, run plugin
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    new WoocommerceAgeGate();
}


?>
