<?php

function participation_init() {
	register_post_type( 'participation', array(
		'labels'            => array(
			'name'                => __( 'Participations', 'YOUR-TEXTDOMAIN' ),
			'singular_name'       => __( 'Participation', 'YOUR-TEXTDOMAIN' ),
			'all_items'           => __( 'Participations', 'YOUR-TEXTDOMAIN' ),
			'new_item'            => __( 'New participation', 'YOUR-TEXTDOMAIN' ),
			'add_new'             => __( 'Add New', 'YOUR-TEXTDOMAIN' ),
			'add_new_item'        => __( 'Add New participation', 'YOUR-TEXTDOMAIN' ),
			'edit_item'           => __( 'Edit participation', 'YOUR-TEXTDOMAIN' ),
			'view_item'           => __( 'View participation', 'YOUR-TEXTDOMAIN' ),
			'search_items'        => __( 'Search participations', 'YOUR-TEXTDOMAIN' ),
			'not_found'           => __( 'No participations found', 'YOUR-TEXTDOMAIN' ),
			'not_found_in_trash'  => __( 'No participations found in trash', 'YOUR-TEXTDOMAIN' ),
			'parent_item_colon'   => __( 'Parent participation', 'YOUR-TEXTDOMAIN' ),
			'menu_name'           => __( 'Participations', 'YOUR-TEXTDOMAIN' ),
		),
		'public'            => true,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'supports'          => array( 'title', 'editor' ),
		'has_archive'       => true,
		'rewrite'           => true,
		'query_var'         => true,
	) );

}
add_action( 'init', 'participation_init' );

function participation_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['participation'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Participation updated. <a target="_blank" href="%s">View participation</a>', 'YOUR-TEXTDOMAIN'), esc_url( $permalink ) ),
		2 => __('Custom field updated.', 'YOUR-TEXTDOMAIN'),
		3 => __('Custom field deleted.', 'YOUR-TEXTDOMAIN'),
		4 => __('Participation updated.', 'YOUR-TEXTDOMAIN'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Participation restored to revision from %s', 'YOUR-TEXTDOMAIN'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Participation published. <a href="%s">View participation</a>', 'YOUR-TEXTDOMAIN'), esc_url( $permalink ) ),
		7 => __('Participation saved.', 'YOUR-TEXTDOMAIN'),
		8 => sprintf( __('Participation submitted. <a target="_blank" href="%s">Preview participation</a>', 'YOUR-TEXTDOMAIN'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		9 => sprintf( __('Participation scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview participation</a>', 'YOUR-TEXTDOMAIN'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		10 => sprintf( __('Participation draft updated. <a target="_blank" href="%s">Preview participation</a>', 'YOUR-TEXTDOMAIN'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'participation_updated_messages' );
