<?php

namespace Inc\Base;

class ClassActivation
{
    public static function activate(){
        flush_rewrite_rules( );

        if(get_option('google_review_ai_plugin')){
            return;
        }
        $default = array();
        
        update_option( 'google_review_ai_plugin', $default );
    }
    
}
