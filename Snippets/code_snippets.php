<?php 
/**
 * This function adds "Copy this node" button
 *
 * @param $form
 * @param $form_state
 * @param $node
 *
 * @return mixed
 */
function hs_tools_copy_node_form ( $form, &$form_state, $node ) {

	$form[ 'node' ] = [
		'#type'  => 'value',
		'#value' => $node,
	];

	if ( $form[ 'node' ][ '#value' ]->type == 'pr_course_au' ) {
		$form[ 'submit' ] = [
			'#type'  => 'submit',
			'#value' => 'Copy this course',
		];
		$form[ 'page_description' ] = [
			'#markup' => '<h2>To make a copy of this course please click on button below</h2>',
		];
	} else {
		$form[ 'submit' ] = [
			'#type'  => 'submit',
			'#value' => 'Copy this program',
		];
		$form[ 'page_description' ] = [
			'#markup' => '<h2>To make a copy of this program please click on button below</h2>',
		];

	}

	return $form;
}

/**
 * This function is custom submit for button above
 *
 * @param $form
 * @param $form_state
 *
 * @throws Exception
 */
function hs_tools_copy_node_form_submit ( $form, &$form_state ) {

	$new_program = $form_state[ 'values' ][ 'node' ];

	unset( $new_program->vid );
	unset( $new_program->nid );

	if ( isset( $new_program->field_pr_institution_id_ref ) ) {

		$new_program->field_pr_institution_id_ref = [];
	} else if ( isset( $new_program->field_pr_owner_au ) ) {
		$new_program->field_pr_owner_au = [];
	}
//	node_save( $new_program );
	field_attach_update( 'node', $new_program );
	field_attach_presave( 'node', $new_program );

	if ( !empty( $new_program->nid ) ) {

		if ( $form[ 'submit' ][ '#value' ] == 'Copy this course' ) {
			drupal_set_message( '<h2><b>You have succesfully made a copy of ' . $new_program->title . ' </h2><h2> Please add new Owner reference to it and save the changes.</b> </h2>' );
		} else {
			drupal_set_message( '<h2><b>You have succesfully made a copy of ' . $new_program->title . ' </h2><h2> Please add new Institution reference to it and save the changes.</b> </h2>' );
		}

		drupal_goto( "node/{$new_program->nid}/edit" );
	} else {
		drupal_set_message( 'Something went wrong...' );
	}
}






/**
 * This function adds "Add major" tab to this program view page
 * to user which have administer nodes access
 *
 * @param $node
 *
 * @return bool
 */
function add_major_node_access ( $node ) {
	if ( ( $node->type == 'pr_programs_us' && user_access( 'administer nodes' ) ) ) {
		return true;
	}

	return false;
}

/**
 * This function adds new major button on program page
 *
 * @param $form
 * @param $form_state
 * @param $node
 *
 * @return mixed
 */
function hs_tools_add_major ( $form, &$form_state, $node ) {
	$form[ 'node' ] = [
		'#type'  => 'value',
		'#value' => $node,
	];


	$form[ 'submit' ] = [
		'#type'  => 'submit',
		'#value' => 'Add new major to this program',
	];

	return $form;
}

/**
 *  Custom submit function for button above
 *
 * @param $form
 * @param $form_state
 *
 * @throws Exception
 */
function hs_tools_add_major_submit ( $form, &$form_state ) {

	global $user;

	$program_nid = $form_state[ 'values' ][ 'node' ]->nid;

	$node = new stdClass();  // Create a new node object
	$node->type = 'pr_program_major_crosswalk';  // Content type
	$node->title = '[node:content-type]-[node:nid]';
	node_object_prepare( $node ); // Sets some defaults. Invokes hook_prepare() and hook_node_prepare().
	$node->language = LANGUAGE_NONE; // Or e.g. 'en' if locale is enabled
	$node->uid = $user->uid;
	$node->status = 1;
	$node->field_pr_program_id[ $node->language ][] = [
		'target_id'   => $program_nid,
		'target_type' => 'node',
	];
	$node = node_submit( $node );
	node_save( $node );


	if ( ( isset( $node->nid ) && ( !empty( $node->nid ) ) ) ) {

		drupal_set_message( '<h2><b>You have succesfully created Crosswalk for linking major to this program</h2><h2> Please add existing major or create new one by clicking on link add/create and then click "Save" button to save changes.</b> </h2>' );


		$form_state[ 'redirect' ] = [ 'node/' . $node->nid . '/edit', [ 'query' => [ 'destination' => 'node/' . $program_nid . '/add-major', 'amid' => $node->nid ] ] ];
	} else {
		drupal_set_message( 'Something went wrong...' );
	}

}