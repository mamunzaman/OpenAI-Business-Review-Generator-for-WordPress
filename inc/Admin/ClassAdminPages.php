<?php

namespace Inc\Admin;

use \Inc\Base\ClassLanguage;
use \Inc\Api\ClassSettingsApi;
use \Inc\Base\ClassBaseController;
use \Inc\Api\Callbacks\AdminCallback;
use \Inc\Api\Callbacks\AdminManagerCallbacks;


class ClassAdminPages extends ClassBaseController
{

    public $settings;
    public $adminCallbacks;
    public $adminCallbacks_mngr;


    public $pages = array();
    public $subpages = array();

    public $baseOptionmane;

    //public $classLanguage;


    public function register(){

        //$this->classLanguage = new ClassLanguage();
        $this->settings = new ClassSettingsApi();
        $this->adminCallbacks = new AdminCallback();

        $this->adminCallbacks_mngr = new AdminManagerCallbacks();

        $this->setPages();
        $this->setSubPages();

        $this->setSettings();
        $this->setSections();
        $this->setFields();

        $this->baseOptionmane = "google_review_ai_plugin";

        $this->settings->AddPages( $this->pages )->with_sub_page('Dashboard')->add_sub_pages( $this->subpages )->register();
 
    }

    public function setPages(){

        $this->pages = array(
            array(
                'page_title' => __( 'GR-AI settings', 'gb-review-domain' ),
                'menu_title' => __( 'GR-AI settings', 'gb-review-domain' ),
                'capability' =>'manage_options',
                'menu_slug' =>'google_review_ai_plugin',
                'callback' => array($this->adminCallbacks, 'adminDashboard'),
                'icon_url' =>'dashicons-edit-page',
                'position' => 110,
            )
        );

    }

    public function setSubPages(){

        $this->subpages = array(
            /*array(
                'parent_slug' =>'google_review_ai_plugin',
                'page_title' =>'Inner Sub Page Title',
                'menu_title' =>'CPT manager',
                'capability' =>'manage_options',
                'menu_slug' =>'google-review-ai-cpt-manager',
                'callback' => array($this->adminCallbacks, 'adminCptPage')
            ),*/
            /*array(
                'parent_slug' =>'google_review_ai_plugin',
                'page_title' =>'Inner Sub Page Title',
                'menu_title' =>'Settings',
                'capability' =>'manage_options',
                'menu_slug' =>'google_review_ai_plugin_sub',
                'callback' => array($this->adminCallbacks, 'adminSettingsPage')
            )*/
        );

    }

    public function setSettings(){

        /*$args = array(); 
        foreach($this->managers as $key => $value){
            $args[] = array(
                'option_group'  => 'google_review_ai_group',
                'option_name'   =>  $key,
                'callback'      => array($this->adminCallbacks_mngr, 'checkboxSanitize')
            );
        }*/

        /*$args = array(); 
        //var_dump($this->managers);
        foreach($this->managers as $key => $value){
            //var_dump($key);
            $args[] = array(
                'option_group'  => 'google_review_ai_settings',
                'option_name'   =>  $key,
                'callback'      => array($this->adminCallbacks_mngr, 'checkboxSanitize')
            );
        }*/
        /*$arg = array(
            array(
                'option_group'  => 'google_review_ai_group',
                'option_name'   =>  'google_review_ai_settings',
                'callback'      => array($this->adminCallbacks_mngr, 'checkboxSanitize')
            ),
            array(
                'option_group'  => 'google_review_ai_group',
                'option_name'   =>  'cpt_manager',
                'callback'      => array($this->adminCallbacks_mngr, 'checkboxSanitize')
            )
        );*/

 
        /*foreach($this->managers as $key => $value){
            $args[] = array(
                'option_group'  => 'google_review_ai_group',
                'option_name'   =>  $key,
                'callback'      => array($this->adminCallbacks_mngr, 'checkboxSanitize')
            );
        }*/

        $args = array(
            array(
            'option_group'  => 'google_review_ai_group',
            'option_name'   =>  'google_review_ai_plugin',
            'callback'      => array($this->adminCallbacks_mngr, 'inputSanitize')
        )
    );

        $this->settings->setSettings($args);
    }

    public function setSections(){
        
        $args = array(
            array(
                'id'  => 'google_review_admin_index', 
                'title'   =>  __( 'Settings Manager', 'gb-review-domain' ),
                'callback'      => array($this->adminCallbacks_mngr, 'adminSectionManager'),
                'page'  => 'google_review_ai_plugin'    // menu page slug
            )
        );

        $this->settings->setSections($args);
    }

    public function setFields(){

        $args = array(
            array(
                'id'  => "google_review_openai_key",
                'title'   =>  __( 'OpenAI Key', 'gb-review-domain' ),
                'callback'      => array($this->adminCallbacks_mngr, 'textField'),
                'page'  => 'google_review_ai_plugin',
                'section'   => 'google_review_admin_index', // id from sections
                'args'  => array(
                    'option_name'   => 'google_review_ai_plugin', 
                    'label_for' => "google_review_openai_key",
                    'classes' => 'ui-toggle',
                    'placeholder'   => __( 'OpenAI Key', 'gb-review-domain' )
                )
            ),
            array(
                'id'  => "google_review_place_api",
                'title'   =>  __( 'Google Place ID', 'gb-review-domain' ),
                'callback'      => array($this->adminCallbacks_mngr, 'textField'),
                'page'  => 'google_review_ai_plugin',
                'section'   => 'google_review_admin_index', // id from sections
                'args'  => array(
                    'option_name'   => 'google_review_ai_plugin',
                    'label_for' => "google_review_place_api",
                    'classes' => 'ui-toggle',
                    'placeholder'   => __( 'Google Place ID', 'gb-review-domain' )
                )
            )
        ); 
        
        /*foreach($this->managers as $key => $value){
            $args[] = array(
                'id'  => $key,
                'title'   =>  $value,
                'callback'      => array($this->adminCallbacks_mngr, 'allCustomField'),
                'page'  => 'google_review_ai_plugin',
                'section'   => 'google_review_admin_index', // id from sections
                'args'  => array(
                    'option_name'   => 'google_review_ai_plugin', 
                    'label_for' => $key,
                    'classes' => 'ui-toggle',
                )
            );
        }*/
        
        /*$args = array(
            array(
                'id'  => 'google_review_cpt_mamanger',
                'title'   =>  'Activate CPT Manager',
                'callback'      => array($this->adminCallbacks_mngr, 'checkboxField'),
                'page'  => 'google_review_ai_plugin',
                'section'   => 'google_review_admin_index',
                'args'  => array(
                    'label_for' => 'google_review_cpt_mamanger',
                    'classes' => 'ui-toggle'
                )
                )
        );
*/
        $this->settings->setFields($args);
    }

   
    
}
