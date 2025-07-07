<?php

namespace Inc\Base;

use \Inc\Base\ClassBaseController;

class ClassSettingsLink extends ClassBaseController
{ 

    
    public function register(){
        //echo $this->plugin;
        add_filter("plugin_action_links_$this->plugin", array($this, 'settings_link'));
        //add_action( 'admin_enqueue_scripts', array($this, 'enqueue_assets_admin') );
    }

    public function settings_link($links){
        $settings_link = '<a href="admin.php?page=google_review_ai_plugin">'.__( 'Settings', 'gb-review-domain' ) .'</a>';
        array_push($links, $settings_link);
        return $links;
    }

     
    
}