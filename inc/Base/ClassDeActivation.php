<?php


namespace Inc\Base;

class ClassDeActivation
{
    public static function deactivate(){
        flush_rewrite_rules();
    }
    
}