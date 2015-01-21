<?php

function team_member_init() {
	register_post_type( 'team-member', array(
		'labels'            => array(
			'name'                => __( 'Team members', 'YOUR-TEXTDOMAIN' ),
			'singular_name'       => __( 'Team member', 'YOUR-TEXTDOMAIN' ),
			'all_items'           => __( 'Team members', 'YOUR-TEXTDOMAIN' ),
			'new_item'            => __( 'New team member', 'YOUR-TEXTDOMAIN' ),
			'add_new'             => __( 'Add New', 'YOUR-TEXTDOMAIN' ),
			'add_new_item'        => __( 'Add New team member', 'YOUR-TEXTDOMAIN' ),
			'edit_item'           => __( 'Edit team member', 'YOUR-TEXTDOMAIN' ),
			'view_item'           => __( 'View team member', 'YOUR-TEXTDOMAIN' ),
			'search_items'        => __( 'Search team members', 'YOUR-TEXTDOMAIN' ),
			'not_found'           => __( 'No team members found', 'YOUR-TEXTDOMAIN' ),
			'not_found_in_trash'  => __( 'No team members found in trash', 'YOUR-TEXTDOMAIN' ),
			'parent_item_colon'   => __( 'Parent team member', 'YOUR-TEXTDOMAIN' ),
			'menu_name'           => __( 'Team members', 'YOUR-TEXTDOMAIN' ),
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
add_action( 'init', 'team_member_init' );

function team_member_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['team-member'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Team member updated. <a target="_blank" href="%s">View team member</a>', 'YOUR-TEXTDOMAIN'), esc_url( $permalink ) ),
		2 => __('Custom field updated.', 'YOUR-TEXTDOMAIN'),
		3 => __('Custom field deleted.', 'YOUR-TEXTDOMAIN'),
		4 => __('Team member updated.', 'YOUR-TEXTDOMAIN'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Team member restored to revision from %s', 'YOUR-TEXTDOMAIN'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Team member published. <a href="%s">View team member</a>', 'YOUR-TEXTDOMAIN'), esc_url( $permalink ) ),
		7 => __('Team member saved.', 'YOUR-TEXTDOMAIN'),
		8 => sprintf( __('Team member submitted. <a target="_blank" href="%s">Preview team member</a>', 'YOUR-TEXTDOMAIN'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		9 => sprintf( __('Team member scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview team member</a>', 'YOUR-TEXTDOMAIN'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		10 => sprintf( __('Team member draft updated. <a target="_blank" href="%s">Preview team member</a>', 'YOUR-TEXTDOMAIN'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'team_member_updated_messages' );
