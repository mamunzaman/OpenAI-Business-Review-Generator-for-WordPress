<?php

namespace Inc\Front;

use \Inc\Base\ClassLanguage;
use \Inc\Base\ClassBaseController;
use \Inc\Base\ClassCustomPostTypeController;


class ClassShortcodes extends ClassBaseController
{


    public function register(){
        //echo $this->plugin;
        //add_filter("plugin_action_links_$this->plugin", array($this, 'settings_link'));
        //add_action( 'admin_enqueue_scripts', array($this, 'enqueue_assets_admin') );
        //echo "Found data";
        add_shortcode('COMPANY_NAME', array($this, 'mm_google_company_name'));
        add_shortcode('KEYWORDS', array($this, 'mm_google_company_keywords'));
        //add_filter('the_content', array($this, 'do_shortcode'));
        //apply_filters('the_content', array($this, 'mm_get_meta_data') );
    }

    public function mm_google_company_name($atts, $content = null){
        $returnData = '';
        extract(shortcode_atts(array(
            "href" => 'http://'
        ), $atts));

        return get_the_title();
    }

    public function mm_google_company_keywords($atts, $content = null){

        //$customPostTypeClass = new ClassCustomPostTypeController();
        extract(shortcode_atts(array(
            "href" => 'http://'
        ), $atts));

        $data = get_post_meta(get_the_id(), '_google_review_business_locations_key', true);

        return $data['Keywords'];

    }

    /*public function settings_link($links){
        $settings_link = '<a href="admin.php?page=google_review_ai_plugin">'.__( 'Settings', 'gb-review-domain' ) .'</a>';
        array_push($links, $settings_link);
        return $links;
    }*/



}