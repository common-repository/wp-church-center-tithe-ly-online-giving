<?php

/**
 *
 * @link              http://wpchurch.team
 * @since             1.0.0
 * @package           WPCC_Tithely
 *
 * @wordpress-plugin
 * Plugin Name:       WP Church Center: Tithe.ly Online Giving
 * Plugin URI:        http://wpchurch.center/addons
 * Description:       Adds a 'Tithe.ly' Giving card which allows churches to have giving initiated through their 'center'
 * Version:           1.0.0
 * Author:            Jordesign, WP Church Team
 * Author URI:        http://wpchurch.team/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       WPCC_Tithely
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}



/******* Add 'Tithely Giving' as an option  in the 'Card Type' field ******/
function wpcc_load_tithely_giving_card( $field ) {
             
    $field['choices'][ 'tithely_giving' ] = 'Tithe.ly Giving';
    return $field;   
}
add_filter('acf/load_field/name=wpcc_card_type', 'wpcc_load_tithely_giving_card');

/******* Add Fields for Church ID, Giving Amount and Giving To ******/
add_action( 'acf/init', 'wpcc_load_tithely_giving_fields',20 );

function wpcc_load_tithely_giving_fields() {
acf_add_local_field( array (
  'key' => 'field_59fa684340h82',
  'label' => 'Your Tithe.ly Church ID',
  '_name' => 'tithely_giving_id',
  'name' => 'tithely_giving_id',
  'type' => 'text',
  'value' => NULL,
  'instructions' => 'Enter your Tithe.ly Church ID, which you can find on the "Website Giving" page of your Tithe.ly account',
  'required' => 1,
  'wrapper' => array (
    'width' => '',
    'class' => '',
    'id' => '',
  ),
  'parent' => 'acf_card-content',
  'conditional_logic' => array (
          'status' => 1,
          'rules' => array (
            array (
              'field' => 'field_5994ca00ccd17',
              'operator' => '==',
              'value' => 'tithely_giving',
            ),
          ),
          'allorany' => 'all',
        ),
  'ui' => 1,
  'ajax' => 0,
  'return_format' => 'value',
  'placeholder' => '',
) );
acf_add_local_field( array (
  'key' => 'field_49fa6jn340h82',
  'label' => 'Giving To:',
  '_name' => 'tithely_giving_to',
  'name' => 'tithely_giving_to',
  'type' => 'text',
  'value' => NULL,
  'instructions' => 'Optionally set a specific purpose to "Give" to',
  'required' => 0,
  'wrapper' => array (
    'width' => '',
    'class' => '',
    'id' => '',
  ),
  'parent' => 'acf_card-content',
  'conditional_logic' => array (
          'status' => 1,
          'rules' => array (
            array (
              'field' => 'field_5994ca00ccd17',
              'operator' => '==',
              'value' => 'tithely_giving',
            ),
          ),
          'allorany' => 'all',
        ),
  'ui' => 1,
  'ajax' => 0,
  'return_format' => 'value',
  'placeholder' => '',
) );
acf_add_local_field( array (
  'key' => 'field_45ma6jn340h82',
  'label' => 'Giving Amount:',
  '_name' => 'tithely_giving_amount',
  'name' => 'tithely_giving_amount',
  'type' => 'text',
  'value' => NULL,
  'instructions' => 'Optionally set a specific amount to "Give"',
  'required' => 0,
  'wrapper' => array (
    'width' => '',
    'class' => '',
    'id' => '',
  ),
  'parent' => 'acf_card-content',
  'conditional_logic' => array (
          'status' => 1,
          'rules' => array (
            array (
              'field' => 'field_5994ca00ccd17',
              'operator' => '==',
              'value' => 'tithely_giving',
            ),
          ),
          'allorany' => 'all',
        ),
  'ui' => 1,
  'ajax' => 0,
  'return_format' => 'value',
  'placeholder' => '',
) );


}


/******* Include reference to tithely Giving Javascript ******/
add_action('wp_print_scripts', 'wpcc_tithely_giving_script', 100);

function wpcc_tithely_giving_script (){
	if ( is_post_type_archive('card') || is_page_template('center_home.php') ){

		wp_enqueue_script( 'wpcc-tithely-giving', 'https://tithe.ly/widget/v3/give.js?3', array( ), '1.0', true );
    wp_add_inline_script( 'wpcc-tithely-giving', "var tw = create_tithely_widget();" );

	}
}


/******* Filter the card link to trigger the popup ******/
function wpcc_tithely_giving_link($card_link) {
  
  if(get_field('wpcc_card_type', get_the_ID()) === 'tithely_giving') {
	   $card_link = '#';
  }
 
	return $card_link;
}
add_filter('wpcc_card_link', 'wpcc_tithely_giving_link');

/******* Filter the card linkattributes to add our details ******/
function wpcc_tithely_link_attr(){
  if(get_field('wpcc_card_type', get_the_ID()) === 'tithely_giving') {
    if($churchID = get_field('tithely_giving_id', get_the_ID() ) ){
      echo 'data-church-id="' . $churchID . '" ';
    }
    if($givingAmount = get_field('tithely_giving_amount', get_the_ID() ) ){
      echo 'data-amount="' . $givingAmount . '" ';
    }
    if($givingTo = get_field('tithely_giving_to', get_the_ID() ) ){
      echo 'data-giving-to="' . $givingTo . '"' ;
    }
  }
}

add_action( 'wpcc_card_link_attr', 'wpcc_tithely_link_attr', 10, 1 );


/******* Filter the card classes to include the Tithely class ******/
function wpcc_tithely_link_class(){
  if(get_field('wpcc_card_type', get_the_ID()) === 'tithely_giving') {
    echo 'tithely-give-btn' ;
  }
}

add_action( 'wpcc_card_link_classes', 'wpcc_tithely_link_class', 10, 1 );

