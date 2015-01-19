<?php 

// Add aé custom post type for LOCAL FUNDS
// http://codex.wordpress.org/Post_Types
function create_post_type() {
  register_post_type( 'local_fund',
    array(
      'labels' => array(
        'name' => __( 'Local funds' ),
        'singular_name' => __( 'Local fund' )
      ),
      'public' => true,
      'has_archive' => true,
      'hierchical' => true,
      'menu_position' => 2
    )
  );
  register_post_type( 'team_member',
    array(
      'labels' => array(
        'name' => __( 'Team members' ),
        'singular_name' => __( 'Team member' )
      ),
      'public' => true,
      'has_archive' => true,
      'hierchical' => true,
      'menu_position' => 2
    )
  );
  register_post_type( 'participations',
    array(
      'labels' => array(
        'name' => __( 'Participations' ),
        'singular_name' => __( 'Participation' )
      ),
      'public' => true,
      'has_archive' => true,
      'hierchical' => true,
      'menu_position' => 2
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
// add_action( 'init', 'people_init' );



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
