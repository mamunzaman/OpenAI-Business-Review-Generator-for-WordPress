<?php

class ClassSettingsPage
{
    public function add_admin_pages(){
        add_menu_page( 'Business Review', 
                        'Review settings', 
                        'manage_options', 
                        'google_review_ai_plugin', 
                    array($this, 'admin_index'), '', 'null' );
    }

    public function admin_index(){
        // template needed here
    }
}
