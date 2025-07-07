<?php


namespace Inc\Base;

use \Inc\Base\ClassBaseController;

class ClassLanguage extends ClassBaseController{

    public $mainMoFile;
    public function register(){
        add_action( 'init', array($this, 'mm_google_review_textdomain') );
        //add_action( 'wp_enqueue_scripts', array($this, 'enqueue_assets_frontend') );
        //add_action( 'wp_enqueue_scripts', array($this, 'load_jquery') );
        add_action( 'init', array($this, 'check_if_domain_exists') ); 
    }

    public function mm_google_review_textdomain(){

        $domain = "gb-review-domain";
        $locale = apply_filters('plugin_locale', get_locale(), $domain);
        $mofile = $domain . '-' . $locale . '.mo';

        //echo plugin_dir_path( dirname(__FILE__, 2) ) . "languages/".$mofile;

        $this->mainMoFile = $mofile;
        load_textdomain($domain, plugin_dir_path( dirname(__FILE__, 2) ) . 'languages/' . $mofile ); 

    }


    function check_if_domain_exists(){

        // Define the source file path
        $source_file = plugin_dir_path( dirname(__FILE__, 2) ) . 'languages/' . $this->mainMoFile;  // Replace this
        // with the actual path to your source file

// Define the destination folder path
        $destination_folder = WP_CONTENT_DIR . '/languages/plugins/';  // Replace this with the actual path to your destination folder

// Check if the source file exists
        if (file_exists($source_file)) {
            // Create the destination folder if it doesn't exist
            if (!file_exists($destination_folder)) {
                mkdir($destination_folder, 0755, true);
            }

            // Define the destination file path
            $destination_file = $destination_folder . basename($source_file);

            // Copy the file to the destination folder
            if (copy($source_file, $destination_file)) {
                //echo "File copied successfully.";
                ;
            } else {
                //echo "Failed to copy the file.";
                ;
            }
        } else {
            //echo "Source file does not exist.";
            ;
        }



    }

}