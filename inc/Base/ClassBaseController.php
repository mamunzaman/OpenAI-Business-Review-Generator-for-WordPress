<?php

namespace Inc\Base;

class ClassBaseController
{
    public $plugin_path;
    public $plugin_url;
    public $plugin;

    public $managers = array();

    public function __construct(){
        $this->plugin_path = plugin_dir_path( dirname(__FILE__, 2) );
        $this->plugin_url = plugin_dir_url( dirname(__FILE__, 2) );
        $this->plugin = plugin_basename( dirname(__FILE__, 3) ) . '/google-review-ai.php';
         
        $this->managers = array( 
            'google_review_settings_manager' => "Google Reivew Plugin Settings", 
        );
    }
    
}

 
