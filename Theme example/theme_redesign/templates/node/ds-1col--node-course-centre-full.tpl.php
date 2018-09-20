<?php
/**
 * @file
 * Default theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: An array of node items. Use render($content) to print them all,
 *   or print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct URL of the current node.
 * - $display_submitted: Whether submission information should be displayed.
 * - $submitted: Submission information created from $name and $date during
 *   template_preprocess_node().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type; for example, "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type; for example, story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $view_mode: View mode; for example, "full", "teaser".
 * - $teaser: Flag for the teaser state (shortcut for $view_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each field instance attached to the node a corresponding
 * variable is defined; for example, $node->body becomes $body. When needing to
 * access a field's raw values, developers/themers are strongly encouraged to
 * use these variables. Otherwise they will have to explicitly specify the
 * desired field language; for example, $node->body['en'], thus overriding any
 * language negotiation rule that was previously applied.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see template_process()
 *
 * @ingroup templates
 */
?>
<article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
  <?php if ((!$page && !empty($title)) || !empty($title_prefix) || !empty($title_suffix) || $display_submitted): ?>
    <header>
      <?php print render($title_prefix); ?>
      <?php if (!$page && !empty($title)): ?>
        <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
      <?php endif; ?>
      <?php print render($title_suffix); ?>
      <?php if ($display_submitted): ?>
        <span class="submitted">
      <?php print $user_picture; ?>
      <?php print $submitted; ?>
    </span>
      <?php endif; ?>
    </header>
  <?php endif; ?>

  <?php if (!empty($content['ssl'])): ?>
  <?php print render($content['ssl']); ?>
    <?php endif; ?>
    <div class="wrap-location">

      <?php if (!empty($content['field_centre_details'])): ?>
      <?php print render($content['field_centre_details']); ?>
        <?php endif; ?>

      <?php if (!empty($content['field_country_page'])): ?>
      <?php print render($content['field_country_page']); ?>
      <?php endif; ?>
    </div>

    <div class="centre-details-wrap clearfix" >

      <?php if (!empty($content['field_top_image'])): ?>
      <?php print render($content['field_top_image']); ?>
      <?php endif; ?>

      <div class="centre-details" >

        <?php if (!empty($content['field_centre_details'])): ?>
        <?php print render($content['field_centre_details']); ?>
        <?php endif; ?>



        <?php if (!empty($content['field_telephone'])): ?>
            <div class="phone">
              <?php print render($content['field_telephone']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($content['field_area_manager_fax'])): ?>
            <div class="fax">
              <?php print render($content['field_area_manager_fax']); ?>
            </div>
        <?php endif; ?>


        <?php if (!empty($content['field_email'])): ?>
            <div class="email">
              <?php print render($content['field_email']); ?>
            </div>
        <?php endif; ?>
          

        <?php if (!empty($content['field_website'])): ?>
            <div class="website">
              <?php print render($content['field_website']); ?>
            </div>
        <?php endif; ?>



      </div>
    </div>

  <?php if (!empty($content['field_body'])): ?>
    <?php print render($content['field_body']); ?>

  <?php endif; ?>


    <?php if (!empty($content['field_courses_offered'])): ?>
    <div class="study-areas" >
      <?php print render($content['field_courses_offered']); ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($content['field_available_qualifications'])): ?>
    <div class="available-qualifications" >
    <?php print render($content['field_available_qualifications']); ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($content['field_related_testimonial'])): ?>
    <div class="related-testiomonial" >
      <?php print render($content['field_related_testimonial']); ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($content['field_news'])): ?>
    <div class="related-news" >
      <?php print render($content['field_news']); ?>
    </div>
  <?php endif; ?>

  <?php
  // Hide comments, tags, and links now so that we can render them later.
  hide($content['title']);
  hide($content['comments']);
  hide($content['links']);
  hide($content['field_tags']);

  print render($content);
  ?>


  <?php if (!empty($content['field_tags']) || !empty($content['links'])): ?>
    <footer>
      <?php print render($content['field_tags']); ?>
      <?php print render($content['links']); ?>
    </footer>
  <?php endif; ?>
  <?php print render($content['comments']); ?>
</article>
