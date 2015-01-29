<?php 


function enrich_related_post_with_own_acf( $related_post_ID, $original_related_post ){
  
  // Check if get_fields() exists and if there are ACFs in this post
  if ( get_fields($related_post_ID) ) {
      
    // Get the related post own ACFs
    $related_post_acf_fields = get_fields($related_post_ID);

    foreach ($related_post_acf_fields as $key_acf => $value_acf) {
      
      // Prefix with ACF to avoid conflict with core wordpress
      $key_acf = 'acf_' . $key_acf;
      $related_post_acf_part[$key_acf] = $value_acf;
    }

    //then merge the core fields ($value)
    // and the ACFs ($related_post_acf_fields) into this post object
    $tmp_value = (object) array_merge((array) $original_related_post, (array) $related_post_acf_part);
    $original_related_post = $tmp_value;

  }

  // This is the original_related_post enriched with its own ACFs
  return $original_related_post;
}

// Inject Advance Custom Fields into the API
function json_api_prepare_post( $post_response, $post, $context ) {
  
  // Check if get_fields() exists and if there are ACFs in this post
  if( get_fields($post['ID'] ) ){

    // if so get ACFs
    $acf_fields = get_fields($post['ID']);


    foreach ($acf_fields as $key => $value) {

      // Prefix with ACF to avoid conflict with core wordpress
      $key = 'acf_' . $key;

      // As ACF related Post Object are return without their own ACFs, we need to recursively find for them

      ////////////////////////////////////////
      // SINGLE POST OBJECT
      ////////////////////////////////////////      
      if( null != $value->ID ){
        // there are godd chances we are returning a single post object
        // so let's get this posts own ACFs
        
        // temporary store $value
        $related_post = $value;
        
        $related_post_ID = $value->ID;
        $related_post = enrich_related_post_with_own_acf( $related_post_ID, $related_post );
        
        //put it back, once enriched
        $value = $related_post;
      }
      ////////////////////////////////////////
      // END of SINGLE POST OBJECT
      ////////////////////////////////////////      




      ////////////////////////////////////////
      // REPEATER of POST OBJECTS
      // Works with a Post Object returned format
      //
      // 
      //  
      // TO_DO : 
      // Make it work with a Post ID returned format
      ////////////////////////////////////////
      if( is_array($value) ){
        if ( null != $value[0]['post'] ) {        
          // there are godd chances we are returning a post object repeater
          // so let's get each posts own ACFs
          
          // temporary store $value
          $post_repeater = $value;

          // loop in the repeater
          foreach ($post_repeater as $post_key => $post_value) {
            
            // Get the post and it's ID
            $repeated_post = $value[$post_key]['post'];
            $repeated_post_ID = $repeated_post->ID;

            // Get repeated post own ACFs
            $repeated_post = enrich_related_post_with_own_acf( $repeated_post_ID, $repeated_post );
            $post_repeater[$post_key]['post'] = $repeated_post;
          }

          //put it back, once enriched
          $value = $post_repeater;
        }


        if ( null != $value[0]['post'] ) {        
          // there are godd chances we are returning a post object repeater
          // so let's get each posts own ACFs
          
          // temporary store $value
          $post_repeater = $value;

          // loop in the repeater
          foreach ($post_repeater as $post_key => $post_value) {
            
            // Get the post and it's ID
            $repeated_post = $value[$post_key]['post'];
            $repeated_post_ID = $repeated_post->ID;

            // Get repeated post own ACFs
            $repeated_post = enrich_related_post_with_own_acf( $repeated_post_ID, $repeated_post );
            $post_repeater[$post_key]['post'] = $repeated_post;
          }

          //put it back, once enriched
          $value = $post_repeater;
        }

      }


      ////////////////////////////////////////
      // END OF REPEATER of POST OBJECTS
      ////////////////////////////////////////

      ////////////////////////////////////////
      // OFFICES : get le list of offices posts enriched with their own ACFs(gmap, adress)
      ////////////////////////////////////////
      if( $key === 'acf_offices_page' ){
        // if so get main offices page ACF
        $offices_acf = get_fields($value);
        $offices_acf = $offices_acf['offices_ordner'];
          // loop in the repeater
          foreach ($offices_acf as $post_key => $post_value) {
            
            // Get the post and it's ID
            $repeated_post = $offices_acf[$post_key]['post'];
            $repeated_post_ID = $repeated_post->ID;

            // Get repeated post own ACFs
            $repeated_post = enrich_related_post_with_own_acf( $repeated_post_ID, $repeated_post );
            $offices_acf[$post_key]['post'] = $repeated_post;
          }

          //put it back, once enriched
          $value = $offices_acf;        
      }
      ////////////////////////////////////////
      // END OF OFFICES
      ////////////////////////////////////////

      // Add this ACF untouched or enriched with Post Object ACFs if needed
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