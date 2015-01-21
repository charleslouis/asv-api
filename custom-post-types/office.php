<?php

function office_init() {
	register_post_type( 'office', array(
		'labels'            => array(
			'name'                => __( 'Offices', 'YOUR-TEXTDOMAIN' ),
			'singular_name'       => __( 'Office', 'YOUR-TEXTDOMAIN' ),
			'all_items'           => __( 'Offices', 'YOUR-TEXTDOMAIN' ),
			'new_item'            => __( 'New office', 'YOUR-TEXTDOMAIN' ),
			'add_new'             => __( 'Add New', 'YOUR-TEXTDOMAIN' ),
			'add_new_item'        => __( 'Add New office', 'YOUR-TEXTDOMAIN' ),
			'edit_item'           => __( 'Edit office', 'YOUR-TEXTDOMAIN' ),
			'view_item'           => __( 'View office', 'YOUR-TEXTDOMAIN' ),
			'search_items'        => __( 'Search offices', 'YOUR-TEXTDOMAIN' ),
			'not_found'           => __( 'No offices found', 'YOUR-TEXTDOMAIN' ),
			'not_found_in_trash'  => __( 'No offices found in trash', 'YOUR-TEXTDOMAIN' ),
			'parent_item_colon'   => __( 'Parent office', 'YOUR-TEXTDOMAIN' ),
			'menu_name'           => __( 'Offices', 'YOUR-TEXTDOMAIN' ),
		),
		'public'            => true,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'supports'          => array( 'title', 'editor' ),
		'has_archive'       => true,
		'rewrite'           => true,
		'query_var'         => true,
		'menu_position'		=> 2	
	) );

}
add_action( 'init', 'office_init' );

function office_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['office'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Office updated. <a target="_blank" href="%s">View office</a>', 'YOUR-TEXTDOMAIN'), esc_url( $permalink ) ),
		2 => __('Custom field updated.', 'YOUR-TEXTDOMAIN'),
		3 => __('Custom field deleted.', 'YOUR-TEXTDOMAIN'),
		4 => __('Office updated.', 'YOUR-TEXTDOMAIN'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Office restored to revision from %s', 'YOUR-TEXTDOMAIN'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Office published. <a href="%s">View office</a>', 'YOUR-TEXTDOMAIN'), esc_url( $permalink ) ),
		7 => __('Office saved.', 'YOUR-TEXTDOMAIN'),
		8 => sprintf( __('Office submitted. <a target="_blank" href="%s">Preview office</a>', 'YOUR-TEXTDOMAIN'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		9 => sprintf( __('Office scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview office</a>', 'YOUR-TEXTDOMAIN'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		10 => sprintf( __('Office draft updated. <a target="_blank" href="%s">Preview office</a>', 'YOUR-TEXTDOMAIN'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'office_updated_messages' );
