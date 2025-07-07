<?php

/***** 
 * https://github.com/orhanerday/open-ai
 * Copyright 2024-2025.
 * @package GoogleAIGeneratedReviewInit
 */

 if(! defined('WP_UNINSTALL_PLUGIN')){
    die;
 }

 // delete your post_type

 global $wpdb;
 $wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'business-lists'" );
 $wpdb->query( "DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)" );
 $wpdb->query( "DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts)" );