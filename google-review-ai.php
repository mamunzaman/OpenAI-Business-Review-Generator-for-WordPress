<?php
/*
* Plugin Name:  Google AI generated review Plugin
* Plugin URI:   #
* Description:  A short little description of the plugin. It will be displayed on the Plugins page in WordPress admin area.
* Version:      1.0
* * Author:       Md mamunuzzaman
Author URI:   #
* License:      GPL2
* License URI:  https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain:  gb-review-domain
* Domain Path: /languages
*/
/***** 
 * https://github.com/orhanerday/open-ai
 * Copyright 2024-2025.
 * @package GoogleAIGeneratedReviewInit
 */
defined('ABSPATH') or die('You better not try to direct access');


if(file_exists(__DIR__ . '/vendor/autoload.php' )){
    require_once __DIR__ . '/vendor/autoload.php';
}
 


//use Inc\Base\ClassActivation;
//use Inc\Base\ClassDeActivation;

function google_review_plugin_activate(){
    //ClassActivation::activate();
    Inc\Base\ClassActivation::activate();
}
register_activation_hook( __FILE__, 'google_review_plugin_activate' );


function google_review_plugin_deactivate(){
    //ClassDeActivation::deactivate();
    Inc\Base\ClassDeActivation::deactivate();
}
register_deactivation_hook( __FILE__, 'google_review_plugin_deactivate' );

/*****
 * this is responsible for all class initialization.
 */
if( class_exists('Inc\\Init')){
    Inc\Init::register_services();
}




