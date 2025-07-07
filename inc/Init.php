<?php

 namespace Inc;

 final class Init
 {
    /*
    *   Store all the classes inside an array. 
    *
    */
    public static function get_services(){
        return [

            Base\ClassEmails::class,
            Base\ClassLanguage::class,
            Front\ClassShortcodes::class,
            Admin\ClassAdminPages::class,
            Base\ClassSettingsLink::class,
            Base\ClassEnqueueAdmin::class,
            Base\ClassCustomPostTypeController::class,
            
        ];
    }
    /**********Loop through the class */
    public static function register_services(){
        foreach(self::get_services() as $class):
            $service = self::instantiate($class);
            if(method_exists($service, 'register')){
                $service->register();
            }
        endforeach;
    }
    /******
     * **
     * Initialize the class 
     */
    private static function instantiate($class){
        $service = new $class;

        return $service;
    }
 }
 