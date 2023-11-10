<?php

// Delete options from the database
delete_option('mv_slider_options');

// Delete custom post type entries
$posts = get_posts([
    'post_type' => 'mv-slider',
    'numberposts' => -1,
    'post_status' => 'any',
]);

foreach ($posts as $post) {
    wp_delete_post($post->ID, true);
}

// Unregister the custom post type
unregister_post_type('mv-slider');

// Flush rewrite rules
flush_rewrite_rules();
