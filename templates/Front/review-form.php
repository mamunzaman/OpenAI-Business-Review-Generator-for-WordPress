<div id="mm-google-review-form-container">
<?php //echo get_the_id();

// Start the session
session_start();

// Check if the session data 'status' is set
if (!isset($_SESSION['first_click'])) {
    // If 'status' is not set, set it to a default value
    //$_SESSION['first_click'] = '0';
    $current_session_status = 'session_first_click';
    // You can also set other session data here if needed
}else{
    $current_session_status = '';
    //$_SESSION['first_click'] = '1';
}


$popupTexts = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";
$allPostMeta = get_post_meta( get_the_id(), '_google_review_business_locations_key', true );

//print_r($allPostMeta);
// get all settings page values.
$google_review_ai_plugin = get_option('google_review_ai_plugin');
$prompt_text = apply_filters('the_content', $allPostMeta['google_review_business_prompt_text']);
$google_review_business_keywords = apply_filters('the_content', $allPostMeta['google_review_business_keywords']);

//print_r($google_review_ai_plugin);


$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://maps.googleapis.com/maps/api/place/details/json?place_id='.$allPostMeta['business_id'].'&language=de&fields=name&key='. $google_review_ai_plugin['google_review_place_api'],
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);

curl_close($curl); 
$response = json_decode($response);

//print_r($response);

//echo $response->result->name;

$review_link_genarated = "https://search.google.com/local/writereview?placeid=".$allPostMeta['business_id'] ;

?>
    <?php if($allPostMeta['token'] !=0 ){ ?>
<form id="mm-google-review-ai-form" action="#" method="post" data-url="<?php echo admin_url('admin-ajax.php'); ?>">

<input type="hidden" name="mm_google_business_id" id="mm_google_business_id" value="<?php echo esc_attr($allPostMeta['business_id']); ?>" />
<input type="hidden" name="mm_google_email" id="mm_google_email" value="<?php echo esc_attr($allPostMeta['email']); ?>" />
<input type="hidden" name="mm_google_keywords" id="mm_google_keywords" value="<?php echo esc_attr($google_review_business_keywords);
?>" />
<input type="hidden" name="mm_google_company_name" id="mm_google_company_name" value="<?php echo
esc_attr($response->result->name); ?>" />
<input type="hidden" name="mm_curr_post_id" id="mm_curr_post_id" value="<?php echo get_the_id(); ?>" />
<input type="hidden" name="google_review_business_prompt_text" id="google_review_business_prompt_text" value="<?php echo esc_attr($prompt_text); ?>" />
<input type="hidden" name="google_review_business_prompt_text_type" id="google_review_business_prompt_text_type" value="0" />
<input type="hidden" name="google_goto_popup_link_trigger" id="google_goto_popup_link_trigger" value="0" />
    <input type="hidden" name="google_remaining_token" id="google_remaining_token" value="<?php echo esc_attr
    ($allPostMeta['token']); ?>" />

<div class="mm-google-review-company-name">
    <?php echo $response->result->name; ?>
</div><!-- mm-google-review-company-name --> 
<div class="rate">
    <input type="radio" id="star5" name="rate" value="5" />
    <label for="star5" title="text"><?php //esc_html_e( '5 stars', 'gb-review-domain' ); ?></label>
    <input type="radio" id="star4" name="rate" value="4" />
    <label for="star4" title="text"><?php //esc_html_e( '4 stars', 'gb-review-domain' ); ?></label>
    <input type="radio" id="star3" name="rate" value="3" />
    <label for="star3" title="text"><?php //esc_html_e( '3 stars', 'gb-review-domain' ); ?></label>
    <input type="radio" id="star2" name="rate" value="2" />
    <label for="star2" title="text"><?php //esc_html_e( '2 stars', 'gb-review-domain' ); ?></label>
    <input type="radio" id="star1" name="rate" value="1" />
    <label for="star1" title="text"><?php //esc_html_e( '1 stars', 'gb-review-domain' ); ?></label>
  </div>
    <!--<div>
        <p>
            <?php esc_html_e( 'Verbleibende Token: ', 'gb-review-domain' ); ?>
             <span id="total-current-token"><?php //echo esc_attr($allPostMeta['token']); ?></span> / 50 <br/>
            <small class="token-point-info"><?php //_e( 'Jede automatisch generierte Token-Anforderung kostet
    // <strong>1 Punkt</strong>.', 'gb-review-domain' ); ?></small>
        </p>
    </div>-->

  <div class="mm-google-review-ai-container">
      <div id="return_chat_text_text" style="display: none;"></div>
      <div id="generated_review_google"></div>
    <div class="mm-textarea-container">
        <textarea id="text-area-trigger-ai" name="mm-ai-textarea" placeholder="<?php esc_html_e( 'Please write your company review', 'gb-review-domain' ); ?>" ></textarea>
        <textarea name="google_mm_already_generated_text" id="google_mm_already_generated_text"
                  style="display:none;" ><?php echo esc_attr($allPostMeta['google_review_business_review_text']); ?></textarea>
    </div><!-- mm-textarea-container -->
  </div><!-- mm-google-review-ai-container --> 
    <input type="hidden" name="action" value="get_review_rating" >
    <div id="loading">
        <!-- 2 -->
        <div class="loader loader--style2" title="1">
            <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="60px" height="60px" viewBox="0 0 50 50"
                 style="enable-background:new 0 0 50 50;" xml:space="preserve">
  <path fill="#000" d="M25.251,6.461c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615V6.461z">
      <animateTransform attributeType="xml"
                        attributeName="transform"
                        type="rotate"
                        from="0 25 25"
                        to="360 25 25"
                        dur="0.6s"
                        repeatCount="indefinite"/>
  </path>
  </svg>
        </div>
    </div>
</form>

<div class="mm-ai-genaration-form-with-data">
    <div id="all_fomr_button_container">
    <input type="button" name="mm-review-generate-button" class="wp-element-button" id="mm-review-generate-button"
           value="<?php esc_html_e( 'Generate a rating', 'gb-review-domain' ); ?>" >

    <button name="mm-review-open-review-google" id="mm-review-open-review-google" class="wp-element-button mm-common-button-hide
    mm-review-open-review-google <?php echo $current_session_status; ?>" onclick="CopyToClipboard('generated_review_google');return false;">
<?php esc_html_e( 'Text fits like this, publish now', 'gb-review-domain' ); ?>
    </button>

    <input type="button" name="mm-review-generate-button-own" class="wp-element-button mm-review-generate-button-own"
           id="mm-review-generate-button-own" onclick="window.open('<?php echo $review_link_genarated; ?>')"
           value="<?php esc_html_e( 'Write your own text', 'gb-review-domain' ); ?>" >

    <input type="button" name="mm-review-ask-new-review" id="mm-review-ask-new-review" class="wp-element-button mm-common-button-hide
    mm-review-ask-new-review <?php echo $current_session_status; ?>"
           value="<?php esc_html_e( 'Write new text', 'gb-review-domain' ); ?>" >

    </div><!-- all_fomr_button_container -->
</div><!-- mm-ai-genaration-form-with-data -->
    <div id="normal-review-butoon-container">
        <input type="button" name="mm-review-normal-button" id="mm-review-normal-button" class="wp-element-button
    mm-review-normal-button <?php echo $current_session_status; ?>"
               value="<?php esc_html_e( 'Senden', 'gb-review-domain' ); ?>" onclick="window.open('<?php echo $review_link_genarated; ?>')">
    </div><!-- normal-review-butoon-container -->

</div><!-- mm-google-review-form-container -->

<div id="mm-googlemap-alert-with-info" class="white-popup mfp-hide">

    <div id="popup-info-before-gotolink">
        <p class="popup-info-before-gotolink-header-footer">
            Nur 3 Klicks...
        </p>
        <ol>
            <li>
                <p>Sterne erneut auswählen</p>
            </li>
            <li>
                <p>Der Textvorschlag wurde bereits kopiert. Fügen Sie diesen im Textfeld einfach ein</p>
            </li>
            <li>
                <p>Bewertung posten und helfen.</p>
            </li>
        </ol>
        <p class="popup-info-before-gotolink-header-footer">
            Danke für die Unterstützung
        </p>
    </div><!-- popup-info-before-gotolink -->

    <p>
        <!--<input type="button" name="mm-google-close-goto-link" id="mm-google-close-goto-link"
                  class="wp-element-button"
           value="<?php esc_html_e( 'Let\'s go', 'gb-review-domain' ); ?>" onclick="window.open('<?php echo
        $review_link_genarated; ?>')"> -->
        <input type="button" name="mm-google-close-goto-link" id="mm-google-close-goto-link"
               class="wp-element-button" data-gotid="<?php echo $review_link_genarated; ?>" value="<?php esc_html_e( 'Let\'s go', 'gb-review-domain' ); ?>" />
    </p>
</div>


<div id="mm-googlemap-error-text-alert" class="white-popup mfp-hide">
     <p><?php esc_html_e( 'Der von Ihnen angeforderte automatische Bewertungstext enthält Inhalte, die nicht zulässig sind und nicht automatisch verbreitet werden können.', 'gb-review-domain' ); ?></p>
</div>
<?php }else{ ?>

    <div class="information-container-if-no-token">
        <p style='text-align: center; margin-bottom: 0'>
            <img src='<?php echo $this->plugin_url; ?>/assets/images/front/info_icon.svg' width='80' alt='' />
        </p>
        <p style='text-align: center'>Sie haben keine Token und können daher keine Anfragen stellen. Bitte kaufen Sie
            neue Token. <br/><a href="#" target="_blank" >klicken Sie hier bitte.</a></p>
    </div><!-- information-container-if-no-token -->

<?php } ?>

