<?php

namespace Inc\Api;

class ClassSettingsApi
{

    public $admin_pages = array();
    public $admin_subpages = array();

    public $setings = array();
    public $sections = array();
    public $fields = array();

    public function register(){
        if(! empty($this->admin_pages)){
            add_action( 'admin_menu', array($this,'add_admin_menu') );
        }

        if(!empty($this->settings)){
            add_action( 'admin_init', array($this, 'registerCustomFields')  );
        }
    }
    public function AddPages(array $pages){
        $this->admin_pages = $pages;

        return $this;
    }

    public function with_sub_page(string $title = null){
        if(empty($this->admin_pages)){
            return $this;
        }

        $admin_page = $this->admin_pages[0];

        $sub_page = array(
            array(
                'parent_slug' =>$admin_page['menu_slug'],
                'page_title' =>$admin_page['page_title'],
                'menu_title' =>($title) ? $title : $admin_page['menu_title'],
                'capability' =>$admin_page['capability'],
                'menu_slug' =>$admin_page['menu_slug'],
                'callback' => $admin_page['callback']
            )
        );

        $this->admin_subpages = $sub_page;

        return $this;
    }

    public function add_sub_pages(array $pages){
        $this->admin_subpages = array_merge($this->admin_subpages, $pages);

        return $this;
    }

    public function add_admin_menu(){
        foreach ($this->admin_pages as $page) {
            # code...
            add_menu_page( 
                            $page['page_title'], 
                            $page['menu_title'], 
                            $page['capability'],
                            $page['menu_slug'],
                            $page['callback'],
                            $page['icon_url'],
                            $page['position']
                         );
        }

        foreach ($this->admin_subpages as $page) {
            # code...
            add_submenu_page( 
                            $page['parent_slug'], 
                            $page['page_title'], 
                            $page['menu_title'], 
                            $page['capability'],
                            $page['menu_slug'],
                            $page['callback']
                         );
        }
    }

    public function setSettings(array $settings){
        $this->settings = $settings; 
        return $this;
    }

    public function setSections(array $sections){
        $this->sections = $sections; 
        return $this;
    }

    public function setFields(array $fields){
        $this->fields = $fields; 
        return $this;
    }

    public function registerCustomFields(){

        // register settings
        foreach ($this->settings as $setting) { 
            register_setting( $setting['option_group'], $setting['option_name'], ( isset($setting['callback']) ? $setting['callback'] : '') );
        }

        // add settings section
        foreach ($this->sections as $section) { 
            add_settings_section($section['id'], $section['title'], ( isset($section['callback']) ? $section['callback'] : ''), $section['page']);
        } 
        
        // add settings field
        foreach ($this->fields as $field) { 
            add_settings_field( $field['id'], $field['title'], ( isset($field['callback']) ? $field['callback'] : ''), $field['page'], $field['section'], ( isset($field['args']) ? $field['args'] : '') );
        }
    }
    
}