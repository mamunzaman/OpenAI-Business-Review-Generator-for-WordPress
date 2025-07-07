<?php
/**
 * @package AdminCallbacks
 */

namespace Inc\Api\Callbacks;

use Inc\Base\ClassBaseController;

class AdminCallback extends ClassBaseController{

    public function adminDashboard() {

        return require_once ("$this->plugin_path/templates/Admin/AdminPage.php");

    }

    public function adminSettingsPage() {

        return require_once ("$this->plugin_path/templates/Admin/AdminSettingsPage.php");

    }

    public function adminCptPage() {

        return require_once ("$this->plugin_path/templates/Admin/adminCptPage.php");

    }

    

    public function googleReviewAIGroup($input){
        return $input;
    }

    public function googleReviewAISection(){
        echo __( 'Check the file', 'gb-review-domain' );
    }  
    
    public function googleReviewAITextExample(){
        $google_review_ai_settings = esc_attr( get_option( 'google_review_ai_settings' ) );
        echo '<input type="text" class="regular-text" name="google_review_ai_settings" value="'.$google_review_ai_settings.'" placeholder="write" >';
    }
}