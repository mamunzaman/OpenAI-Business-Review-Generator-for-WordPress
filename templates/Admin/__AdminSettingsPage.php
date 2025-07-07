<h1>Admin Settings Page</h1>
<?php settings_errors(); ?>

<ul class="nav nav-tabs">
    <li class="active">
        <a href="#mm-tab-1">Manage Settings</a>
    </li>
    <li>
        <a href="#mm-tab-2">Update</a>
    </li>
    <li>
        <a href="#mm-tab-3">Aout</a>
    </li>

</ul>

<div class="tab-content">
    <div id="mm-tab-1" class="tab-pane active">

        <form method="post" action="options.php">
            <?php 
                settings_fields( 'google_review_ai_group' );
                do_settings_sections( 'google-review-ai-settings-sub' );
                submit_button( );
            ?>
        </form>

    </div>
    <div id="mm-tab-2" class="tab-pane">Update</div>
    <div id="mm-tab-3" class="tab-pane">About</div>

</div>

