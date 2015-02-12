<?php 

// Handle CORS
add_action( 'init', 'handle_preflight' );
function handle_preflight() {
    header("Access-Control-Allow-Origin: " . get_http_origin());
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Credentials: true");

    if ( 'OPTIONS' == $_SERVER['REQUEST_METHOD'] ) {
        status_header(200);
        exit();
    }
}


// Require Custom post types
require_once('libs/custom-post-types/office.php');
require_once('libs/custom-post-types/team-member.php');
require_once('libs/custom-post-types/participation.php');

// Require image custom sizes
require_once('libs/images.php');

// Require ACF API enrichment
require_once('libs/acf-api.php');

