<?php
/**
 * @package AdminCallbacks
 */

namespace Inc\Api\Callbacks;

use Inc\Base\ClassBaseController;

class AdminManagerCallbacks extends ClassBaseController{ 

    public function checkboxSanitize($input){  
        $output = array(); 
        foreach($this->managers as $key => $value){
            $output[$key] = isset($input[$key]) ? true : false ;
        }
        
        return $output;
    }


    public function inputSanitize($input){

        return $input;
    }



    public function adminSectionManager() {
        echo __( 'This area is for setting up your basic Plugin.', 'gb-review-domain' );

    }

    public function textField($args){
        //var_dump($args);
        //print_r($args);
        $name = $args['label_for'];
        $classes = $args['classes'];
        $placeholder = $args['placeholder'];
        $option_name = $args['option_name'];


        $input = get_option( $option_name );

        $input = isset($input[$name]) ? ($input[$name] ? $input[$name] : false ) : false;
        
        echo '<input type="text" class="regular-text" id="'.$name.'" name="'. $option_name .'['.$name.']" 
        value="'. $input.'" placeholder="'. $placeholder .'" >';


         
    }

    public function allCustomField($args){
        //var_dump($args);
        //print_r($args);
        $name = $args['label_for'];
        $classes = $args['classes'];
        $option_name = $args['option_name']; 

        $checkbox = get_option( $option_name );

        $checked = isset($checkbox[$name]) ? ($checkbox[$name] ? true : false ) : false;

        echo '<div class="'. $classes .'">
            <input type="checkbox" id="'.$name.'" name="'. $option_name .'['.$name.']" value="1" class="'.$classes.'" 
            '. ($checked ? 'checked' : '' ) .' >
            <label for="'. $name .'"><div></div></label></div>';
    }

     
}