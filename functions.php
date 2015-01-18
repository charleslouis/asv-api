<?php 

// Add CPT
// http://codex.wordpress.org/Post_Types
function create_post_type() {
  register_post_type( 'acme_product',
    array(
      'labels' => array(
        'name' => __( 'Products' ),
        'singular_name' => __( 'Product' )
      ),
      'public' => true,
      'has_archive' => true,
    )
  );
}
add_action( 'init', 'create_post_type' );

// Add CTax
// http://codex.wordpress.org/Taxonomies
// define "people" as a taxonomy for attachments. He uses it to allow people to mark the names of people in pictures, and using that his site can display pictures of people under the '/person/name' URL.
function people_init() {
  // create a new taxonomy
  register_taxonomy(
    'people',
    'post',
    array(
      'label' => __( 'People' ),
      'rewrite' => array( 'slug' => 'person' ),
      'capabilities' => array(
        'assign_terms' => 'edit_guides',
        'edit_terms' => 'publish_guides'
      )
    )
  );
}
add_action( 'init', 'people_init' );



function json_api_prepare_post( $post_response, $post, $context ) {
  $post_response['tttttttttttttttttttttttttttttttttttttttttttttt'] = 'passssssssssssssssssssssssssssssssssssssss';
  if( get_fields($post['ID']) ){
    $acf_fields = get_fields($post['ID']);
    foreach ($acf_fields as $key => $value) {
      $post_response[$key] = $value;
    }
    return $post_response;
  }
} 
add_filter( 'json_prepare_post', 'json_api_prepare_post', 10, 3 );
