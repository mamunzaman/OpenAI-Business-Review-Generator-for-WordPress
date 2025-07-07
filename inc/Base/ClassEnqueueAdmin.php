<?php


namespace Inc\Base;

use \Inc\Base\ClassBaseController;

class ClassEnqueueAdmin extends ClassBaseController
{
    public function register(){
        add_action( 'admin_enqueue_scripts', array($this, 'enqueue_assets_admin') );
        add_action( 'wp_enqueue_scripts', array($this, 'enqueue_assets_frontend') ); 
        //add_action( 'wp_enqueue_scripts', array($this, 'load_jquery') );
    }

    function enqueue_assets_admin(){
        wp_enqueue_style( 'admin-google-review-ai', $this->plugin_url . 'assets/admin/css/admin-google-review-ai.css' );
        wp_enqueue_script( 'admin-google-review-ai', $this->plugin_url . 'assets/admin/js/admin-google-review-ai.js' );

        //wp_enqueue_script( 'admin-google-review-tinymce.min', $this->plugin_url . 'assets/admin/js/tinymce.min.js' );
        //wp_enqueue_script( 'admin-google-review-tinymce.min.custom', $this->plugin_url . 'assets/admin/js/tinymce.custom.js' );

        //https://cdn.tiny.cloud/1/API_KEY/tinymce/5/tinymce.min.js
    }

    function enqueue_assets_frontend(){
        wp_enqueue_style( 'front-google-review-ai', $this->plugin_url . 'assets/front/css/front-google-review-ai.css' );
        if ( ! wp_script_is( 'jquery', 'enqueued' )) {
            //Enqueue
            wp_enqueue_script( 'jquery' );

        }

        //wp_enqueue_script( 'front-google-review-ai', $this->plugin_url . 'assets/front/js/front-google-review-ai.js' );
    }
    
}
