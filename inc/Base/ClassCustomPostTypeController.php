<?php

namespace Inc\Base;

//use Front\ClassShortcodes;
use Orhanerday\OpenAi\OpenAi;
use \Inc\Base\ClassBaseController;


class ClassCustomPostTypeController extends ClassBaseController
{
    public function register(){ 

        add_action( 'init', array($this, 'activate') );

        //add_action( 'phpmailer_init', array($this, 'mailer_config'), 10, 1);

        //add_action( 'admin_enqueue_scripts', array($this, 'enqueue_custom_type_enqueue') );

        add_action('add_meta_boxes', array($this,'add_custom_post_boxes'));
        add_action('save_post', array($this,'save_meta_boxes'));

        add_action('manage_google_review_client_posts_columns', array($this, 'set_custom_columns')); 
        add_action( 'manage_google_review_client_posts_custom_column', array($this, 'set_custom_column_data'), 10, 2 );
        add_filter( 'manage_edit-google_review_client_sortable_columns', array($this, 'set_custom_columns_sortable') );

        add_shortcode( 'google-review-generator-ai', array($this, 'google_review_generate_ai_form') );
        add_shortcode( 'google-review-compan-name', array($this, 'google_review_compan_name') );
        
        // used for called ajax in review Form.
        add_action('wp_ajax_get_review_rating',array($this, 'get_review_rating_ajax'));
        add_action('wp_ajax_nopriv_get_review_rating',array($this, 'get_review_rating_ajax'));
        //get_review_rating
    }

    /*function enqueue_custom_type_enqueue(){
        wp_enqueue_script( 'admin-google-review-ai', $this->plugin_url . 'assets/admin/js/tinymce.min.js' );
        wp_enqueue_script( 'admin-google-review-ai', $this->plugin_url . 'assets/admin/js/tinymce.custom.js' );
        //https://cdn.tiny.cloud/1/API_KEY/tinymce/5/tinymce.min.js
    }
*/


    /*function mailer_config(PHPMailer $mailer){
        $mailer->IsSMTP();
        $mailer->Host = "s30.internetwerk.de"; // your SMTP server
        $mailer->SMTPAuth   = false;
        $mailer->Port = 465;
        $mailer->Username   = 'info@boovis.com';                     //SMTP username
        $mailer->Password   = 'nacUctYakifEes2';                               //SMTP password
        $mailer->SMTPDebug = 0; // write 0 if you don't want to see client/server communication in page
        $mailer->CharSet  = "utf-8";

    }*/

    public function google_get_all_define_words($data, $curr_post_id){

        $prompt_text = $data['google_review_business_prompt_text'];
        // Example macros
        $macros = array(
            '{COMPANY_NAME}'       => get_the_title($curr_post_id),
            '{KEYWORDS}'     => $data['Keywords'],
            // Add more macros as needed
        );

        // Replace macros in the title
        $prompt_text_formatted = str_replace(array_keys($macros), array_values($macros), $prompt_text);

        return $prompt_text_formatted;

    }

    public function get_review_rating_ajax(){

        $classEmails = new ClassEmails();


        $curr_post_id = esc_attr($_POST['mm_curr_post_id']);
        $google_goto_popup_link_trigger = esc_attr($_POST['google_goto_popup_link_trigger']);
        $google_remaining_token = esc_attr($_POST['google_remaining_token']);
        $google_review_ai_plugin = get_option('google_review_ai_plugin');
        $data = get_post_meta($curr_post_id, '_google_review_business_locations_key', true);
        $prompt_text = $data['google_review_business_prompt_text'];
        $google_mm_already_generated_text = isset($data['google_mm_already_generated_text']) ? $data['google_mm_already_generated_text'] : '';

        $prompt_text_formatted = $this->google_get_all_define_words($data,$curr_post_id);
        //$google_get_all_define_words = $this->google_get_all_define_words($prompt_text);
        //$prompt_text = apply_filters('the_content', $prompt_text);


        // Start the session
        session_start();


        if($google_remaining_token <= 0){

            //$classEmails->emailSendForTokenNotification(),

            /*$to = 'test2@boovis.com';
            $subject = 'The subject';
            $body = 'The email body content';
            $headers = array('Content-Type: text/html; charset=UTF-8');

            wp_mail( $to, $subject, $body, $headers );*/

            /*$to = 'test2@boovis.com';
            $subject = 'The subject';
            $body = 'The email body content';
            $headers = array('Content-Type: text/html; charset=UTF-8;');

            $testWB = wp_mail( $to, $subject, $body, $headers );*/

            $arrayOfCompany = array(
                'company_name'  =>  get_the_title($curr_post_id),
                'id'            => $curr_post_id,
                'email'         =>  $data['email'],
                'link'          =>  get_the_permalink($curr_post_id),
            );
            $testWB = $classEmails->emailSendForTokenNotification($arrayOfCompany);



            $return  = array(
                'status'    => "error_token",
                "trigger"   => "no more token token",
                'found_class_token'   => $testWB,
                "content" => "
                            <p style='text-align: center'>
                                <img src='".$this->plugin_url."/assets/images/front/info_icon.svg' width='50' alt='' />
                            </p>
                            <p style='text-align: center'>Sie haben keine Wertmarken und können daher keine Anfragen stellen. Wir sind über Ihre Situation informiert und werden Sie in Kürze per E-Mail kontaktieren. 
<br/><br/>Vielen Dank für Ihr Verständnis</p>"
            );

            wp_send_json( $return );
            wp_die();

        }




        // Check if the session data 'status' is set
        if (!isset($_SESSION['first_click'])) {
            // If 'status' is not set, set it to a default value
            $_SESSION['first_click'] = '0';
            // You can also set other session data here if needed
        }else{
            $_SESSION['first_click'] = '1';
        }


        if($google_goto_popup_link_trigger == 1){




            $remain_token = $data['token'];
            $remain_token = $remain_token - 1;

            /*if(sanitize_textarea_field($_POST['mm-ai-textarea'])){
                $reviewData = $_POST['mm-ai-textarea'];
            }*/

            $postData = array(
                //"token" => sanitize_text_field($remain_token),
                "token" => sanitize_text_field($remain_token),
                "business_id" => sanitize_text_field($data['business_id']),
                "email" => sanitize_email( $data['email']),
                "Keywords" => sanitize_text_field( $data['Keywords']),
                "rating" => sanitize_text_field( $_POST['rate']),
                "google_review_business_review_text" => sanitize_textarea_field($data['google_review_business_review_text']),
                "google_review_business_prompt_text"  => sanitize_textarea_field($prompt_text)
            );

            update_post_meta($curr_post_id, '_google_review_business_locations_key', $postData);


            /*$to = 'test2@boovis.com';
            $subject = 'The subject';
            $body = 'The email body content';
            $headers = array('Content-Type: text/html; charset=UTF-8;');

            $testWB = wp_mail( $to, $subject, $body, $headers );*/

            $return  = array(
                'status'    => 'success',
                "trigger"   => "google_goto_popup_link_trigger = = ",
                'post_data' => $postData,
                "token" => $remain_token,
                "popup_status"  => 1

            );

            wp_send_json( $return );
            wp_die();

        }






        if(empty($data['google_review_business_prompt_text'])){
            //$prompt_text = $data['google_review_business_prompt_text'];
            $return  = array(
                'status'    => 'error',
                "trigger"   => "google_review_business_prompt_text"
            );

            wp_send_json( $return );

            wp_die();

        }else{




            // Array of forbidden words
            /*$forbidden_words = array("[COMPANY_NAME]", "[KEYWORDS]" );

            // Check if any of the forbidden words exist in the array
            $found_forbidden_word = false;
            foreach ($forbidden_words as $word) {
                if (strpos($prompt_text, $word) !== false) {
                    $found_forbidden_word = true;
                    break;
                }
            }

            // If a forbidden word was found, prevent execution
            if ($found_forbidden_word) {
                // Do not execute your code here
                //echo "Forbidden word found!";
                $prompt_text = __( 'Der von Ihnen angeforderte automatische Bewertungstext enthält Inhalte, die nicht zulässig sind und nicht automatisch verbreitet werden können.', 'gb-review-domain' );
                $return  = array(
                    'status'    => "error",
                    "content" => $prompt_text,
                    "trigger"   => "$found_forbidden_word"
                );

                wp_send_json( $return );

                wp_die();
            }*/











        }

        //google-review-keywords

        /*$text_prompt = "Make a very good review for the company called ". $_POST['mm_google_company_name']
            . " and this company use this Keyword ". $_POST['mm_google_keywords']. " and short explain why this rating ".$_POST['rate'].".";*/


        $messag=  [
            //"model" => "gpt-3.5-turbo-16k-0613",
            "model" => "gpt-4",
            "messages" => [
                [
                    "role" => "user",
                    "content" => $prompt_text_formatted
                ]
            ]
        ];


        $messag = json_encode($messag);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer '.$google_review_ai_plugin['google_review_openai_key'].'',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $messag);

        $response = curl_exec($ch);

        // Check for errors
        if ($response === false) {
            //echo 'cURL error: ' . curl_error($ch);
            $return  = array(
                'status'    => 'error',
                "trigger"   => "response Data",
                'data'  => curl_error($ch)
            );
        }else{
            $response = json_decode($response,false);
            $reviewData = $response->choices[0]->message->content;
        }
        // Get HTTP response code
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);



        if(isset($_POST['rate'])){

            $rating = sanitize_text_field( $_POST['rate'] );


            // Check the response code
            if ($http_code == 200) {

                /*$remain_token = $data['token'];
                $remain_token = $remain_token - 1;*/

                /*if(sanitize_textarea_field($_POST['mm-ai-textarea'])){
                    $reviewData = $_POST['mm-ai-textarea'];
                }*/


                $postData = array(
                    "token" => $data['token'],
                    "business_id" => sanitize_text_field($data['business_id']),
                    "email" => sanitize_email( $data['email']),
                    "Keywords" => sanitize_text_field( $data['Keywords']),
                    "rating" => sanitize_text_field( $_POST['rate']),
                    "google_review_business_review_text" => $reviewData,
                    "google_review_business_prompt_text"  => $prompt_text
                );
                update_post_meta($curr_post_id, '_google_review_business_locations_key', $postData);




                /*$to = 'test2@boovis.com';
                $subject = 'The subject';
                $body = 'The email body content';
                $headers = array('Content-Type: text/html; charset=UTF-8');

                $testWB = wp_mail( $to, $subject, $body, $headers );*/

                $return  = array(
                    'status'    => 'success',
                    'data'  => $response,
                    'post_data' => $data,
                    "token" => $data['token'],
                    "trigger"   => "http_code 200 = = ",
                    'click_status'  => $_SESSION['first_click'],
                    'promt_sentence'    => $prompt_text_formatted
                );





            } else {
                // Error
                $return  = array(
                    'status'    => 'error',
                    'data'    => $http_code,
                    "trigger"   => "http_code 200 ERROR",
                    'click_status'  => $_SESSION['first_click'],
                    'promt_sentence'    => $prompt_text_formatted
                );
                // Optionally, handle different error codes accordingly
            }



            wp_send_json( $return );
            wp_die();

        }

        $return  = array(
            'status'    => 'error',
            "trigger"   => "Simple ERROR",
        ); 
        
        wp_send_json( $return );

        wp_die();
    }


    public function google_review_generate_ai_form(){
        
        ob_start();

        require_once("$this->plugin_path/templates/Front/review-form.php");

        echo "<link rel=\"stylesheet\" id=\"google-review-magnific-popup\" href=\"$this->plugin_url/assets/front/css/magnific-popup.css\" type=\"text/css\" media=\"all\" />";
        echo "<script src=\"$this->plugin_url/assets/front/js/jquery.magnific-popup.min.js\"></script>";
        // review.form.js
        echo "<script src=\"$this->plugin_url/assets/front/js/review.form.js\"></script>";


        return ob_get_clean();
    }



    public function activate(){

        $labels = array(
            'name'                  => _x( 'Google Clients', 'Post Type General Name', 'gb-review-domain' ),
            'singular_name'         => _x( 'Google Client', 'Post Type Singular Name', 'gb-review-domain' ),
            'menu_name'             => __( 'Google Clients', 'gb-review-domain' ),
            'name_admin_bar'        => __( 'Google Client', 'gb-review-domain' ),
            'archives'              => __( 'Google Client Archives', 'gb-review-domain' ),
            'attributes'            => __( 'Google Client Attributes', 'gb-review-domain' ),
            'parent_item_colon'     => __( 'Parent Google Client:', 'gb-review-domain' ),
            'all_items'             => __( 'All Google Client', 'gb-review-domain' ),
            'add_new_item'          => __( 'Add New Client', 'gb-review-domain' ),
            'add_new'               => __( 'Add New  Client', 'gb-review-domain' ),
            'new_item'              => __( 'New Google Client', 'gb-review-domain' ),
            'edit_item'             => __( 'Edit Google Client', 'gb-review-domain' ),
            'update_item'           => __( 'Update Google Client', 'gb-review-domain' ),
            'view_item'             => __( 'View Google Client', 'gb-review-domain' ),
            'view_items'            => __( 'ViewGoogle Clients', 'gb-review-domain' ),
            'search_items'          => __( 'Search Google Client', 'gb-review-domain' ),
            'not_found'             => __( 'Not found', 'gb-review-domain' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'gb-review-domain' ),
            'featured_image'        => __( 'Featured Image', 'gb-review-domain' ),
            'set_featured_image'    => __( 'Set featured image', 'gb-review-domain' ),
            'remove_featured_image' => __( 'Remove featured image', 'gb-review-domain' ),
            'use_featured_image'    => __( 'Use as featured image', 'gb-review-domain' ),
            'insert_into_item'      => __( 'Insert into Google Client', 'gb-review-domain' ),
            'uploaded_to_this_item' => __( 'Uploaded to this Google Client', 'gb-review-domain' ),
            'items_list'            => __( 'Google Clients list', 'gb-review-domain' ),
            'items_list_navigation' => __( 'Google Clients list navigation', 'gb-review-domain' ),
            'filter_items_list'     => __( 'Filter Google Clients list', 'gb-review-domain' ),
        );
        $args = array(
            'label'                 => __( 'Google Client', 'gb-review-domain' ),
            'description'           => __( 'Post Type Description', 'gb-review-domain' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail',  'revisions',  'page-attributes'),
            //'taxonomies'            => array( 'category', 'post_tag' ),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 60,
            'menu_icon'             => 'dashicons-format-chat',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'page',
            'show_in_rest'          => true,
            'rewrite'               => array('slug' => 'cn','with_front' => true),
        );
        register_post_type( 'google_review_client', $args );
        

    }

    public function add_custom_post_boxes(){
        add_meta_box(
            'business_location_id',
            __( 'Business location Information', 'gb-review-domain' ),
            array($this,'render_feature_box'),
            'google_review_client',
            'normal',
            'default'
        );
    }

    public function render_feature_box($post){

        wp_nonce_field('google_review_business_locations','google_review_business_locations_nonce');

        $data = get_post_meta($post->ID, '_google_review_business_locations_key', true);


        /**********
         *
         * Predefine texts
         */



        $location_id = isset($data['business_id']) ? $data['business_id'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $keywords = isset($data['Keywords']) ? $data['Keywords'] : '';

        $rating = isset($data['rating']) ? $data['rating'] : '';
        $review = isset($data['google_review_business_review_text']) ? $data['google_review_business_review_text'] : '';
        $totalToken = isset($data['token']) ? $data['token'] : '';

        //$prompt = isset($data['google_review_business_prompt_text']) ? $data['google_review_business_prompt_text'] : '';

        $text_prompt = 'Verfasse eine kurze, positive Bewertung in deutscher Sprache für das Google My Business-Profil von {COMPANY_NAME}. Stelle dar, wie ein Kunde, der kürzlich deren Dienste in Anspruch genommen hat, seine Erfahrungen beschreibt. Verwende die Schlüsselwörter {KEYWORDS} . Der Text soll formell, aber zugänglich sein, die Schlüsselwörter effektiv integrieren, um Glaubwürdigkeit zu vermitteln, und achte besonders auf korrekte Rechtschreibung. Der Unternehmensname soll nur einmal verwendet werden.';

        $prompt = isset($data['google_review_business_prompt_text']) ? $data['google_review_business_prompt_text'] : $text_prompt;

        ?>
        <p>
            <b><label for="google_review_business_review_token"><?php _e( 'Remain tokens', 'gb-review-domain' );
                    ?></label></b><br/>

            <input type="text" class="large-text" id="google_review_business_review_token"
                   name="google_review_business_review_token" value="<?php echo esc_attr($totalToken); ?>">
        </p>
        <p>
            <b><label for="google_review_business_location_id"><?php _e( 'Business Location ID', 'gb-review-domain' ); ?></label></b><br/>
            <input type="text" class="large-text" id="google_review_business_location_id" name="google_review_business_location_id" value="<?php echo esc_attr($location_id); ?>">
        </p>

        <p>
            <b><label for="google_review_business_email"><?php _e( 'E-Mail', 'gb-review-domain' ); ?></label></b><br/>
            <input type="email" class="large-text" id="google_review_business_email" name="google_review_business_email" value="<?php echo esc_attr($email); ?>">
        </p>

        <p>
            <b><label for="google_review_business_keywords"><?php _e( 'Keywords', 'gb-review-domain' ); ?></label></b><br/>
            <input type="text" class="large-text" id="google_review_business_keywords" name="google_review_business_keywords" value="<?php echo esc_attr($keywords); ?>">
        </p>

        <p>
            <b><label for="google_review_business_rating"><?php _e( 'Rating', 'gb-review-domain' ); ?></label></b><br/>
            <input type="text" class="large-text" id="google_review_business_rating" name="google_review_business_rating" value="<?php echo esc_attr($rating); ?>">
        </p>

        <p>
            <b><label for="google_review_business_review_text"><?php _e( 'Review texts', 'gb-review-domain' );
            ?></label></b><br/>

            <textarea class="large-text" id="google_review_business_review_text"
                      name="google_review_business_review_text" rows="10"><?php echo esc_attr($review); ?></textarea>
        </p>

        <p>
            <b><label for="google_review_business_prompt_text"><?php _e( 'Prompt texts', 'gb-review-domain' );
                ?></label></b><br/>

            <textarea class="large-text" id="google_review_business_prompt_text"
                      name="google_review_business_prompt_text" rows="10"><?php echo esc_attr($prompt); ?></textarea>
        </p>




        <?php
    }

    public function save_meta_boxes($post_id){

        if(! isset($_POST['google_review_business_locations_nonce'])){
            return $post_id;
        }

        $nonce = $_POST['google_review_business_locations_nonce'];
        if(! wp_verify_nonce($nonce,'google_review_business_locations' )){
            return $post_id;
        }

        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
            return $post_id;
        }

        if(! current_user_can( 'edit_post',$post_id)){
            return $post_id;
        }


        //$totalToken = isset($data['token']) ? $data['token'] : '';

        $data = array(
                "token" => sanitize_text_field( $_POST['google_review_business_review_token']),
                "business_id" => sanitize_text_field( $_POST['google_review_business_location_id']),
                "email" => sanitize_text_field( $_POST['google_review_business_email']),
                "Keywords" => sanitize_text_field( $_POST['google_review_business_keywords']),
                "rating" => sanitize_text_field( $_POST['google_review_business_rating']),
                "google_review_business_review_text" => sanitize_textarea_field( $_POST['google_review_business_review_text']),
                "google_review_business_prompt_text" => sanitize_textarea_field( $_POST['google_review_business_prompt_text'])
        );

        update_post_meta($post_id, '_google_review_business_locations_key', $data);


    }

    public function set_custom_columns($columns){

        $title = $columns['title'];
        $date = $columns['date'];
        
        unset($columns['title'], $columns['date']);


        $columns['title'] = $title;
        $columns['email'] = __( 'E-Mail', 'gb-review-domain' );
        $columns['rating'] = __( 'Rating', 'gb-review-domain' );
        $columns['Keywords'] = __( 'Keywords', 'gb-review-domain' );
        $columns['token'] = __( 'Token', 'gb-review-domain' );
        $columns['date'] = $date;

        return $columns;
    }

    public function set_custom_column_data($column, $post_id){

        $data = get_post_meta($post_id, '_google_review_business_locations_key', true);


        $location_id = isset($data['business_id']) ? $data['business_id'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $keywords = isset($data['Keywords']) ? $data['Keywords'] : '';
        $rating = isset($data['rating']) ? $data['rating'] : '';
        $totalToken = isset($data['token']) ? $data['token'] : '';


        switch($column){
            case 'email';
            echo '<a href="mailto:' . $email . '">'.$email.'</a>';

            break;

            case 'rating':
                echo $rating;
                break;

            case 'Keywords':
                echo $keywords;
                break;

            case 'token':
                echo $totalToken;
                break;
        }

    }

    public function set_custom_columns_sortable($columns){

        $columns['email'] = __( 'E-Mail', 'gb-review-domain' );
        $columns['rating'] = __( 'Rating', 'gb-review-domain' );
        $columns['Keywords'] = __( 'Keywords', 'gb-review-domain' );
        $columns['token'] = __( 'Token', 'gb-review-domain' );

        return $columns;
    }



    
}