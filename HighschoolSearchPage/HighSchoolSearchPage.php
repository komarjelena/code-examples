<?php

///Purpose of this pages was to show all necessary info from complex realtionship between HS Elsi, Naviance and District
// in order to respect HS year to be different for every school. This is changed before release.
///So, this page is not in use at the moment due to merging these 2 HS Content types.
// It can be removed if the new model is approved and it won't be changed to previous version again.
// Function that calls this page are commented in HS tools module in menu hook.



# Init Form  For High shool search page
function hs_tools_highschool_search_page_form($form, &$form_state) {

	$form['#method']= 'GET';


	$form['highschool_name'] = array(
		'#type' => 'textfield',
		'#title' => 'High school Name',
		'#maxlength' => 225,
		'#default_value'=> (isset($_GET['highschool_name'])) ? $_GET['highschool_name'] : '',
		'#autocomplete_path' => 'hs-overview/autocomplete/highschool_name',
	);



	$form['district'] = array(
		'#type' => 'textfield',
		'#title' => 'District',
		'#maxlength' => 225,
		'#default_value'=> (isset($_GET['district'])) ? $_GET['district'] : '',
		'#autocomplete_path' => 'hs-overview/autocomplete/district',
	);

	$form['hs_year'] = array(
		'#type' => 'textfield',
		'#title' => 'School year',
		'#maxlength' => 225,
		'#default_value'=> (isset($_GET['hs_year'])) ? $_GET['hs_year'] : '',
		// '#autocomplete_path' => 'career/autocomplete',
	);


	$form['country'] = array(
		'#type' => 'textfield',
		'#title' => 'Country',
		'#maxlength' => 225,
		'#default_value'=> (isset($_GET['country'])) ? $_GET['country'] : '',
		'#autocomplete_path' => 'hs-overview/autocomplete/country',
	);



	$form['submit'] = array(
		'#type' => 'submit',
		'#value' => 'Search',
	);


//dpm($form_state);
	return $form;
}


function hs_tools_highschoolname_autocomplete($string) {

	$query = db_select('field_data_field_hs_na_nces_id', 'hs_nces_id');

	$query->leftJoin('node', 'node_hs', 'node_hs.nid = hs_nces_id.entity_id');

	$query->addField('node_hs', 'title', 'node_hs_title');
	$query->addField('node_hs', 'nid', 'node_hs_nid');

	$query->condition('hs_nces_id.bundle', 'hs_high_school_us', '=');
	$query->condition('node_hs.title', '%' .$string. '%', 'LIKE');
	$query->range(0, 20);

	$result = $query->execute()->fetchAll();

	foreach ($result as $row) {
		$title = $row->node_hs_title .' [' . $row->node_hs_nid . ']';
		// dpm($title);
		$matches[$title] = check_plain($title);
	}

	drupal_json_output( $matches );
}


function hs_tools_district_autocomplete($string) {

	$query = db_select('field_data_field_hs_na_nid', 'nces_id')->extend('TableSort');


	$query->leftJoin('node', 'node_nces_id', 'nces_id.entity_id = node_nces_id.nid');
	$query->leftJoin('field_data_field_hs_el_school_year', 'school_year', 'school_year.entity_id = nces_id.nid');
	$query->leftJoin('field_data_field_hs_na_district_ref', 'district_ref', 'district_ref.entity_id = nces_id.entity_id');
	$query->leftJoin('node', 'node_district', 'node_district.nid = district_ref.field_hs_na_district_ref_target_id');

	$query->addField('node_district', 'title', 'node_district_title');
	$query->addField('node_district', 'nid', 'node_district_nid');

	$query->condition('nces_id.bundle', 'hs_high_school_us', '=');

	$query->condition('node_district.title', '%' .$string. '%', 'LIKE');
	$query->range(0, 20);

	$result = $query->execute()->fetchAll();

	foreach ($result as $row) {
		$title = $row->node_district_title .' [' . $row->node_district_nid . ']';
		// dpm($title);
		$matches[$title] = check_plain($title);
	}

	drupal_json_output( $matches );

}
function hs_tools_country_autocomplete ($string) {

	$query = db_select('field_data_field_hs_na_nid', 'nces_id')->extend('TableSort');


	$query->leftJoin('node', 'node_nces_id', 'nces_id.entity_id = node_nces_id.nid');
	$query->leftJoin('field_data_field_hs_el_school_year', 'school_year', 'school_year.entity_id = node_nces_id.nid');
	$query->leftJoin('field_data_field_hs_na_district_ref', 'district_ref', 'district_ref.entity_id = nces_id.entity_id');
	$query->leftJoin('node', 'node_district', 'node_district.nid = district_ref.field_hs_na_district_ref_target_id');
	$query->leftJoin('field_data_field_hs_na_country_name_ref', 'country_ref', 'country_ref.entity_id = node_district.nid');
	$query->leftJoin('taxonomy_term_data', 'country', 'country.tid = country_ref.field_hs_na_country_name_ref_target_id');

	$query->addField('country', 'name', 'country_title');
	$query->addField('country', 'tid', 'country_nid');

	$query->condition('nces_id.bundle', 'hs_high_school_us', '=');


	$query->condition('country.name', '%' .$string. '%', 'LIKE');

	$query->range(0, 20);

	$result = $query->execute()->fetchAll();

	foreach ($result as $row) {
		$title = $row->country_title .' [' . $row->country_nid . ']';

		$matches[$title] = check_plain($title);
	}

	drupal_json_output( $matches );

}

function hs_tools_highschool_search_page_form_submit() {

	$out = '';

	if ((!empty($_GET['district']))  || (!empty($_GET['hs_year'])) || (!empty($_GET['highschool_name']) || (!empty($_GET['country'])))) {


		$header = array(
			array('data' => 'High school', 'field' => 'node_nav_title'),
			array('data' => 'District', 'field' => 'node_district_title'),
			array('data' => 'School Year', 'field' => 'school_year_value'),
			array('data' => 'Country', 'field' => 'country_title','sort' => 'ASC'),
		);


		$query = db_select('field_data_field_hs_na_nid', 'nces_id')->extend('TableSort');


		$query->leftJoin('node', 'node_nces_id', 'nces_id.entity_id = node_nces_id.nid');
		$query->leftJoin('field_data_field_hs_el_school_year', 'school_year', 'school_year.entity_id = node_nces_id.nid');
		$query->leftJoin('field_data_field_hs_na_district_ref', 'district_ref', 'district_ref.entity_id = nces_id.entity_id');
		$query->leftJoin('node', 'node_district', 'node_district.nid = district_ref.field_hs_na_district_ref_target_id');
		$query->leftJoin('field_data_field_hs_na_country_name_ref', 'country_ref', 'country_ref.entity_id = node_district.nid');
		$query->leftJoin('taxonomy_term_data', 'country', 'country.tid = country_ref.field_hs_na_country_name_ref_target_id');

		$query->addField('node_nces_id', 'title', 'node_nces_title');
		$query->addField('node_nces_id', 'nid', 'node_nces_nid');
		$query->addField('node_district', 'title', 'node_district_title');
		$query->addField('node_district', 'nid', 'node_district_nid');
		$query->addField('school_year', 'field_hs_el_school_year_value', 'school_year_value');
		$query->addField('country', 'name', 'country_title');
		$query->addField('country', 'tid', 'country_nid');

		$query->condition('nces_id.bundle', 'hs_high_school_us', '=');



		// Condition depends on which field is used for search
		if ( !empty($_GET['highschool_name']) ) {
			preg_match ( '/([0-9]+)/', $_GET['highschool_name'], $nid);
			if (!empty($nid[1])) {
				$query->condition('node_nces_id.nid', $nid[1], 'LIKE');

			} else {
				$query->condition('node_nces_id.title', '%' .$_GET['highschool_name']. '%', 'LIKE');

			}
		}


		// Condition depends on which field is used for search
		if ( !empty($_GET['district']) ) {
			preg_match ( '/([0-9]+)/', $_GET['district'], $nid);
			if (!empty($nid[1])) {
				$query->condition('node_district.nid', $nid[1], '=');
			} else {
				$query->condition('node_district.title', '%' .$_GET['district']. '%', 'LIKE');
			}
		}

		if (!empty($_GET['school_year'])) {
			$query->condition('school_year.field_hs_el_school_year_value', $_GET['hs_year']. '%', 'LIKE');
		}

		if ( !empty($_GET['country']) ) {
			preg_match ( '/([0-9]+)/', $_GET['country'], $nid);
			if (!empty($nid[1])) {
				$query->condition('country.tid', $nid[1], 'LIKE');

			} else {
				$query->condition('country.name', '%' .$_GET['country']. '%', 'LIKE');

			}
		}



		$query->orderByHeader($header);
		$results = $query->execute();


		foreach ($results as $result) {
			//dpm($result);
			$rows[] = array(
				l($result->node_nces_title, "node/$result->node_nces_nid", array('attributes' => array('target' => '_blank'))),
				l($result->node_district_title, "node/$result->node_district_nid", array('attributes' => array('target' => '_blank'))),
				$result->school_year_value,
				l($result->country_title, "node/$result->country_nid", array('attributes' => array('target' => '_blank'))),
			);

			$rows_csv[] = array(
				'"' . $result->node_nces_title . '"',
				'"' . $result->node_district_title . '"',
				'"' . $result->school_year_value . '"',
				'"' . $result->country_title . '"',
			);
		}

		if(!empty($_GET['export']) && $_GET['export'] == 1) {

			//dpm($rows_csv);

			hs_tools_print_csv($rows_csv, $header );

		} elseif (!empty($rows)) {


			$per_page = 20;
			// Initialize the pager
			$current_page = pager_default_initialize(count($rows), $per_page);
			// Split your list into page sized chunks
			$chunks = array_chunk($rows, $per_page, TRUE);
			// Show the appropriate items from the list
			$out .= theme('table', array('header' => $header, 'rows' => $chunks[ $current_page ]));
			// Show the pager
			$out .= theme('pager', array('quantity', count($rows)));

			$out .= '<div class="csv-export">' . l('Download CSV', 'hs-overview', array(
					'query' => array(
						'highschool_name'  => $_GET['highschool_name'],
						'hs_year' => $_GET['hs_year'],
						'country'  => $_GET['country'],
						'district' => $_GET['district'],
						'export' => 1)
				)) . '</div>';
		}
		return $out;
	} else {
		drupal_set_message(t('You must fill in at least one field for filtering.'), 'error');

		return '';

	}
}


	/**
	 * This function prints csv on career search page
	 * which is rendered by hs_tools_highschool_search_page_form_submit function(only custom search page)
	 * @param $rows_csv
	 * @param $header
	 */
function hs_tools_print_csv($rows_csv, $header ){

	$output ='';

	drupal_add_http_header('Content-Type', 'text/csv');
	drupal_add_http_header('Content-Disposition', 'attachment;filename = hs-overview_'.date("j-M-Y").' .csv');

	foreach ($header as $col) {
		$cols[] = $col['data'];
	}
	if ($cols) {
		$output .= implode(",", $cols) . PHP_EOL;
	}

	foreach ($rows_csv as $row) {
		$output .= implode(",", $row) . PHP_EOL;
	}

	print $output;
	exit;
}



function hs_tools_highschool_page() {


	$form = drupal_get_form('hs_tools_highschool_search_page_form');

	$out = drupal_render($form);


	$out .= hs_tools_highschool_search_page_form_submit();

	return $out;
}
