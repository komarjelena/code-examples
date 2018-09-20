<?php
/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * The doctype, html, head and body tags are not in this template. Instead they
 * can be found in the html.tpl.php template in this directory.
 *
 * Available variables: see https://api.drupal.org/api/drupal/modules!system!page.tpl.php/7
 *
 * @ingroup themeable
 */
?>
<div id="page" class="body-inner">

    <header role="banner" id="header">
        <div class="container">
            <div class="wrapper">
                <div class="logo">
                  <?php if ($logo): ?>
                      <a class="navbar-btn" href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>">
                          <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
                      </a>
                  <?php endif; ?>
                </div><!-- /.header-elements -->
                <div class="header-elements">
                  <?php if (!empty($page['header'])): ?>
                        <?php print render($page['header']); ?>

                  <?php endif; ?>
                    <div class="nav-controller">
                        <div class="navbar-toggle" >
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </div><!-- /.navbar-toggle -->
                    </div><!-- /.nav-controller -->
                </div><!-- /.header-elements -->

                <div id="mobile-menu">
                    <div class="inner">
                      <?php if (!empty($page['navigation'])): ?>
                          <div class="mobile-nav">
                            <?php print render($page['navigation']); ?>
                          </div><!-- /.mobile-nav -->
                      <?php endif; ?>
                      <?php if (!empty($page['header'])): ?>
                          <div class="mobile-top-nav">
                            <?php print render($page['header']); ?>
                          </div><!-- /#mobile-top-nav -->
                      <?php endif; ?>
                    </div><!-- /.inner -->
                </div><!-- /#mobile-menu-->


            </div><!-- /.wrapper-->
        </div><!-- /.container -->

    </header> <!-- /#page-header -->
    <header id="navbar" role="banner" >
        <div class="<?php print $navbar_classes; ?>">
          <?php if (!empty($page['navigation'])): ?>
              <nav role="navigation">
                <?php print render($page['navigation']); ?>
              </nav>
          <?php endif; ?>
        </div>
    </header> <!-- /#navbar -->



    <div class="main-container clearfix">

        <div class="main">

            <section class="page-info">

              <?php if (!empty($breadcrumb)): print $breadcrumb; endif;?>
                <a id="main-content"></a>
              <?php print render($title_prefix); ?>
              <?php if (!empty($title)): ?>
                  <h1 class="page-header"><?php print $title; ?></h1>
              <?php endif; ?>
              <?php print render($title_suffix); ?>
              <?php print $messages; ?>
              <?php if (!empty($tabs)): ?>
                <?php print render($tabs); ?>
              <?php endif; ?>
              <?php if (!empty($page['help'])): ?>
                <?php print render($page['help']); ?>
              <?php endif; ?>
              <?php if (!empty($action_links)): ?>
                  <ul class="action-links"><?php print render($action_links); ?></ul>
              <?php endif; ?>

              <?php print render($page['content']); ?>


            </section>

          <?php if (!empty($page['sidebar_first'])): ?>
              <aside<?php print $sidebar_first_class; ?> role="complementary">
                <?php print render($page['sidebar_first']); ?>
              </aside>  <!-- /#sidebar-first -->
          <?php endif; ?>

          <?php if (!empty($page['sidebar_second'])): ?>
              <aside<?php print $sidebar_second_class; ?> role="complementary">
                <?php print render($page['sidebar_second']); ?>
              </aside>  <!-- /#sidebar-second -->
          <?php endif; ?>
        </div>

    </div>

    <div id="footer-wrapper">
        <footer class="footer">
            <div class="container">
              <?php print render($page['footer']); ?>
            </div>
        </footer>
      <?php if (!empty($page['footer_bottom'])): ?>
          <footer class="footer-bottom">
              <div class="container">
                <?php print render($page['footer_bottom']); ?>
              </div>
          </footer>
      <?php endif; ?>
    </div>

</div>
