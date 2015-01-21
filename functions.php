<?php 

// Inject Advance Custom Fields into the API
function json_api_prepare_post( $post_response, $post, $context ) {
  if( get_fields($post['ID']) ){
    $acf_fields = get_fields($post['ID']);
    foreach ($acf_fields as $key => $value) {
      $post_response[$key] = $value;
    }
    return $post_response;
  }
}
add_filter( 'json_prepare_post', 'json_api_prepare_post', 10, 3 );


// Require Custom post types
require_once('custom-post-types/office.php');
require_once('custom-post-types/team-member.php');
require_once('custom-post-types/participation.php');


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