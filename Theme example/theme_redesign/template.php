<?php
/**
 * @file
 * template.php
 */

/**
 * Implements hook_preprocess_html()
 *
 * @param $variables
 * @see page.tpl.php
 */
function theme_redesign_preprocess_html(&$variables) {


  if (!empty($variables['page']['sidebar_first']) && !empty($variables['page']['sidebar_second'])) {
    $variables['classes_array'][] = 'layout-two-sidebars';
  }
  elseif (!empty($variables['page']['sidebar_first'])) {
    $variables['classes_array'][] = 'layout-one-sidebar';
    $variables['classes_array'][] = 'layout-sidebar-first';
  }
  elseif (!empty($variables['page']['sidebar_second'])) {
    $variables['classes_array'][] = 'layout-one-sidebar';
    $variables['classes_array'][] = 'layout-sidebar-second';
  }
  else {
    $variables['classes_array'][] = 'layout-no-sidebars';
  }
}

/**
 * Implements hook_preprocess_page().
 *
 * @see page.tpl.php
 */
function theme_redesign_preprocess_page(&$variables) {

  /**
   * !! IMORTANT !! Over ride of bootstrap default column setting classes.
   * Also introduces a class for first and second and allows for content first approach if needed.
   * This setup works on the 'push' and 'pull' column useage. It will always render the content
   * region first. If you want to swap this back - you will probably need to change the push and pull parts
   */
  //BOTH SIDEBARS
  if (!empty($variables['page']['sidebar_first']) && !empty($variables['page']['sidebar_second'])) {
    $variables['sidebar_first_class'] = ' class="sidebar sidebar-first"';
    $variables['sidebar_second_class'] = ' class="sidebar sidebar-second"';
  }
  //FIRST SIDEBAR ONLY
  elseif (!empty($variables['page']['sidebar_first']) && empty($variables['page']['sidebar_second'])) {
    $variables['sidebar_first_class'] = ' class="sidebar sidebar-first"';
  }
  //SECOND SIDEBAR ONLY
  elseif (empty($variables['page']['sidebar_first']) && !empty($variables['page']['sidebar_second'])) {
    $variables['sidebar_second_class'] = ' class="sidebar sidebar-second"';
  }
  //NO SIDEBARS
  else {
    $variables['content_column_class'] = ' class="main-content-column"';
  }


}




/* BOOTSTRAP MENU FIXES */

function theme_redesign_menu_tree(&$variables) {
  return '<div class="nav-collapse"><ul class="menu nav">' . $variables['tree'] . '</ul></div>'; // added the nav-collapse wrapper so you can hide the nav at small size
}


function theme_redesign_menu_link(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';
  //$element['#localized_options']['attributes']['data-toggle'] = 'dropdown';

  if ($element['#below']) {
    // Ad our own wrapper
    //unset($element['#below']['#theme_wrappers']);
    $sub_menu = '<ul>' . drupal_render($element['#below']) . '</ul>'; // removed flyout class in ul
    //unset($element['#localized_options']['attributes']['class']); // removed flydown class
    //    unset($element['#localized_options']['attributes']['data-toggle']); // removed data toggler

    // Check if this element is nested within another
    if ((!empty($element['#original_link']['depth'])) && ($element['#original_link']['depth'] > 1)) {

      //  unset($element['#attributes']['class']); // removed flyout class
    }
    else {
      //unset($element['#attributes']['class']); // unset flyout class
      $element['#localized_options']['html'] = TRUE;
      $element['#title'] .= ''; // removed carat spans flyout
    }

    // Set dropdown trigger element to # to prevent inadvertent page loading with submenu click
    $element['#localized_options']['attributes']['data-target'] = '#'; // You could unset this too as its no longer necessary.
  }

  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";

  if ($element['#below']) {
    $sub_menu = drupal_render($element['#below']);
  }

}

/*
 * Implements template_preprocess_facetapi_link_inactive().
 * Remove the count from Facet links.
 */
function theme_redesign_preprocess_facetapi_link_inactive(&$variables) {
  unset($variables['count']);
}


function theme_redesign_process_page(&$variables) {
  $menu_tree = menu_tree_all_data('main-menu');
  $variables['main_menu'] = menu_tree_output($menu_tree);
}

function theme_redesign_preprocess_entity(&$vars) {
  $type = $vars['elements']['#entity_type'];
  $bundle = $vars['elements']['#bundle'];
  //  kint($type);
  //  kint($bundle);
  //var_dump($vars);

  switch ($type) {
    // Bean template variables.
    case 'bean':
      switch ($bundle) {
        case 'download_block':
          $vars['cta_url'] = isset($vars['elements']['#entity']->field_file['und'][0]['uri']) ?
            $vars['elements']['#entity']->field_file['und'][0]['uri']
            : $vars['elements']['#entity']->field_button['und'][0]['url'];

          if(isset($vars['elements']['#entity']->field_file['und'][0]['uri'])){
            $vars['title'] = 'download';
          }elseif ($vars['elements']['#entity']->field_button['und'][0]['url']) {
            $vars['title'] = $vars['elements']['#entity']->field_button['und'][0]['title'];
          }

          $image_uri = $vars["elements"]["#entity"]->field_image["und"][0]["uri"];
          $image_url = file_create_url($image_uri);
          $vars['cta_url_image'] = ($image_url) ? $image_url : '';


          break;
        case 'how_can_we_help':
          if (isset($vars['elements']['#entity']->field_landing_page_url)) {
            $vars['landing_page_url'] = $vars['elements']['#entity']->field_landing_page_url['und'][0]['url'];
          }
          break;
      }
      break;
  }
}


function theme_redesign_preprocess_block(&$variables) {
  // For bean blocks.


  if ($variables['block']->subject === 'Centre Partners') {
    $variables['title_attributes_array'] = [
      'id' => 'centre-partners'
    ];

  }

  if ($variables['block']->module == 'bean') {
    // Get the bean elements.
    $beans = $variables['elements']['bean'];
    // There is only 1 bean per block.
    $bean_keys = element_children($beans);
    $bean = $beans[reset($bean_keys)];
    // Add bean type classes to the block.
    $variables['classes_array'][] = drupal_html_class('block-bean-' . $bean['#bundle']);
    // Add template suggestions for bean types.
    $variables['theme_hook_suggestions'][] = 'block__bean__' . $bean['#bundle'];

    if (!empty($bean['#bundle']) && $bean['#bundle'] == 'download_block') {
      $cta = (!empty($beans)) ? reset($beans) : '';
      $img_uri = ($cta['#entity']->field_image['und'][0]['uri']) ? $cta['#entity']->field_image['und'][0]['uri'] : '';
      $variables['cta_url_image'] = file_create_url($img_uri);
    }
    // Preprocess for how can we help bean block.
    if(!empty($bean['#bundle']) && $bean['#bundle'] == 'how_can_we_help') {
      $variables['landing_page_url'] = (isset($bean['field_landing_page_url']['#items'][0]['url'])) ? $bean['field_landing_page_url']['#items'][0]['url'] : '';
    }
  }



}

/**
 * Implements hook_form_alter().
 */
function theme_redesign_form_alter(&$form, &$form_state, $form_id) {

  switch ($form_id){

    case 'user_login':
      //Changes made on user login form
      $form['name']['#title'] = t('User name');
      $form['pass']['#title'] = t('Enter password');
      $form['name']['#description'] = FALSE;
      $form['pass']['#description'] = FALSE;
      $form['name']['#attributes']['autocomplete'] = 'off';
      $form['pass']['#attributes']['autocomplete'] = 'off';


      break;
    case 'views_exposed_form' :
      $form['search_api_views_fulltext']['#attributes']['placeholder'] = t('Search');
      break;
  }

}

function theme_redesign_preprocess_file_entity(&$variables) {
  if ($variables['type'] == 'image') {

    // Alt Text
    if (!empty($variables['field_media_alt_text'])) {
      $variables['content']['file']['#alt'] = $variables['field_media_alt_text']['und'][0]['safe_value'];
    }

    // Title
    if (!empty($variables['field_media_title'])) {
      $variables['content']['file']['#title'] = $variables['field_media_title']['und'][0]['safe_value'];
    }
  }
}


function theme_redesign_preprocess_field(&$variables, $hook) {
  if(empty($variables["element"]["#bundle"])) return;
  //dpm($variables["element"]["#bundle"]);

  switch ($variables["element"]["#bundle"]) {
    case 'download_block':
      $variables["theme_hook_suggestions"][] = 'bean--download-block';
      $cta_url_image = (!empty($variables["element"]["#object"]->field_image["und"][0]["uri"])) ? $variables["element"]["#object"]->field_image["und"][0]["uri"] : '';
      $variables["cta_url_image"] = (!empty($cta_url_image)) ? file_create_url($cta_url_image) : '';
      break;

    case '2_column_equal_widths':
      if (!isset($variables['element']['#items'][0]['moddelta'])) return;

      $variables["theme_hook_suggestions"][] = 'field--field-cta-block-reference--2-column-equal-widths';
      $bean_id = $variables['element']['#items'][0]['moddelta'];

      // Early escape if referenced entity isn't bean.
      if (strpos($bean_id, 'bean') === false) return;

      $bean_id = explode(':', $bean_id,2);
      $entity = entity_load('bean', array($bean_id[1]));
      $entity_fix = reset($entity);

      $variables['cta_url_image'] = isset($entity_fix->field_image['und'][0]['uri']) ?
        file_create_url($entity_fix->field_image['und'][0]['uri'])
        : '';
      //kint($variables['cta_url_image']);
      break;

  }
}




