<?php //echo $this->plugin_url; ?>
<div class="review-admin-settings-container">
<h1><?php esc_html_e( 'Google Review AI Plugin Settings', 'gb-review-domain' ); ?></h1>
 
<?php settings_errors(); ?>

<ul class="nav nav-tabs">
    <li class="active">
        <a href="#mm-tab-1"><?php esc_html_e( 'Manage Settings', 'gb-review-domain' ); ?></a>
    </li> 
</ul>

<div class="tab-content">
    <div id="mm-tab-1" class="tab-pane active">

        <form method="post" action="options.php">
            <?php 
                settings_fields( 'google_review_ai_group' );    // option_group
                do_settings_sections( 'google_review_ai_plugin' );  // section page
                submit_button( );
            ?>
        </form>
    </div>
</div>
</div><!-- review-admin-settings-container -->
