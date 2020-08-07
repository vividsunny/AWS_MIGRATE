<div class="tg_settings_tabs_content">

  <p><?php _e( 'You can use a shortcode to embed the tag cloud directly in a post, page or widget or you call the function in the PHP code of your theme.', 'tag-groups' ) ?> <?php _e( 'Several tag clouds are also available as blocks for the Gutenberg editor.', 'tag-groups' ) ?></p>
  <form method="POST" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ) ?>">
    <input type="hidden" name="tag-groups-shortcode-nonce" id="tag-groups-shortcode-nonce" value="<?php echo wp_create_nonce( 'tag-groups-shortcode' ) ?>" />
    <p>
      <input type="checkbox" name="widget" autocomplete="off" id="tg_widget" value="1"<?php if ( $tag_group_shortcode_widget ) : ?> checked<?php endif; ?>>&nbsp;
      <label for="tg_widget"><?php _e( 'Enable shortcode in sidebar widgets (if not visible anyway).', 'tag-groups' ) ?></label>
    </p>
    <p>
      <input type="checkbox" name="enqueue" id="tg_enqueue" autocomplete="off" value="1"<?php if ( $tag_group_shortcode_enqueue_always ) : ?> checked<?php endif; ?>>&nbsp;
      <label for="tg_enqueue"><?php _e( 'Always load shortcode scripts.', 'tag-groups' ) ?></label>
      <span class="dashicons dashicons-editor-help chatty-mango-help-icon" data-topic="load-scripts"></span>
    </p>
    <div class="chatty-mango-help-container chatty-mango-help-container-load-scripts" style="display:none;">
      <p><?php _e( 'Turn off to load the scripts only on posts and pages where a shortcode appears.', 'tag-groups' ) ?></p>
      <p><span class="dashicons dashicons-warning"></span><?php _e( 'Turn on if you use these shortcodes in widgets or if you use Gutenberg blocks.', 'tag-groups' ) ?></p>
    </div>
    <input type="hidden" id="action" name="tg_action" value="shortcode">
    <input class="button-primary" type="submit" name="save" value="<?php _e( 'Save Settings', 'tag-groups' ) ?>" id="submitbutton" />
  </form>

  <p>&nbsp;</p>
  <p><?php _e('Click for more information.', 'tag-groups') ?></p>
  <h3><?php _e('Shortcodes', 'tag-groups') ?></h3>
  <div class="tg_admin_accordion" >
    <h4><?php _e( 'Tabbed Tag Cloud', 'tag-groups' ) ?></h4>
    <div>
      <h4>[tag_groups_cloud]</h4>
      <p><?php _e( 'Display the tags in a tabbed tag cloud.', 'tag-groups' ) ?></p>
      <h4><?php _e( 'Example', 'tag-groups' ) ?></h4>
      <p>[tag_groups_cloud smallest=9 largest=30 include=1,2,10]</p>
      <h4><?php _e( 'Parameters', 'tag-groups' ) ?></h4>
      <p><?php printf( __( 'Please find the parameters in the <a %s>documentation</a>.', 'tag-groups' ), 'href="https://documentation.chattymango.com/documentation/tag-groups-premium/tabbed-tag-cloud/tabbed-tag-cloud-parameters/?pk_campaign=tg&pk_kwd=documentation" target="_blank"' ) ?></p>
    </div>

    <h4><?php _e( 'Accordion', 'tag-groups' ) ?></h4>
    <div>
      <h4>[tag_groups_accordion]</h4>
      <p><?php _e( 'Display the tags in an accordion.', 'tag-groups' ) ?></p>
      <h4><?php _e( 'Example', 'tag-groups' ) ?></h4>
      <p>[tag_groups_accordion smallest=9 largest=30 include=1,2,10]</p>
      <h4><?php _e( 'Parameters', 'tag-groups' ) ?></h4>
      <p><?php printf( __( 'Please find the parameters in the <a %s>documentation</a>.', 'tag-groups' ), 'href="https://documentation.chattymango.com/documentation/tag-groups-premium/accordion-tag-cloud/accordion-tag-cloud-parameters/?pk_campaign=tg&pk_kwd=documentation" target="_blank"' ) ?></p>
    </div>

    <h4><?php _e( 'Alphabetical tabs', 'tag-groups' ) ?></h4>
    <div>
      <h4>[tag_groups_alphabet_tabs]</h4>
      <p><?php _e( 'Display the tags in tabbed tag cloud with first letters as tabs.', 'tag-groups' ) ?> <?php _e( '(Not tested with right-to-left languages.)', 'tag-groups' ) ?></p>
      <h4><?php _e( 'Example', 'tag-groups' ) ?></h4>
      <p>[tag_groups_alphabet_tabs exclude_letters="äöü"]</p>
      <h4><?php _e( 'Parameters', 'tag-groups' ) ?></h4>
      <p><?php printf( __( 'Please find the parameters in the <a %s>documentation</a>.', 'tag-groups' ), 'href="https://documentation.chattymango.com/documentation/tag-groups-premium/alphabetical-tag-cloud/alphabetical-tag-cloud-parameters/?pk_campaign=tg&pk_kwd=documentation" target="_blank"' ) ?></p>
    </div>

    <?php echo $premium_shortcode_info ?>

    <h4><?php _e( 'Group Information', 'tag-groups' ) ?></h4>
    <div>
      <h4>[tag_groups_info]</h4>
      <p><?php _e( 'Display information about tag groups.', 'tag-groups' ) ?></p>
      <h4><?php _e( 'Example', 'tag-groups' ) ?></h4>
      <p>[tag_groups_info group_id="all"]</p>
      <h4><?php _e( 'Parameters', 'tag-groups' ) ?></h4>
      <p><?php printf( __( 'Please find the parameters in the <a %s>documentation</a>.', 'tag-groups' ), 'href="https://documentation.chattymango.com/documentation/tag-groups-premium/tag-groups-info/tag-groups-info-parameters/?pk_campaign=tg&pk_kwd=documentation" target="_blank"' ) ?></p>
    </div>
  </div>

  <h3>PHP</h3>
  <div class="tg_admin_accordion">
    <h4>tag_groups_cloud()</h4>
    <div>
      <p><?php _e( 'The function <b>tag_groups_cloud</b> accepts the same parameters as the [tag_groups_cloud] shortcode, except for those that determine tabs and styling.', 'tag-groups' ) ?></p>
      <p><?php _e( 'By default it returns a string with the html for a tabbed tag cloud.', 'tag-groups' ) ?></p>
      <h4><?php _e( 'Example', 'tag-groups' ) ?></h4>

      <p><code><?php echo htmlentities( "<?php if ( function_exists( 'tag_groups_cloud' ) ) echo tag_groups_cloud( array( 'include' => '1,2,5,6' ) ) ?>" ) ?></code></p>
      <p>&nbsp;</p>
      <p><?php _e( 'If the optional second parameter is set to \'true\', the function returns a multidimensional array containing tag groups and tags.', 'tag-groups' ) ?></p>
      <h4><?php _e( 'Example', 'tag-groups' ) ?></h4>
      <p><code><?php echo htmlentities( "<?php if ( function_exists( 'tag_groups_cloud' ) ) print_r( tag_groups_cloud( array( 'orderby' => 'count', 'order' => 'DESC' ), true ) ) ?>" ) ?></code></p>
    </div>
  </div>

  <!-- begin Tag Groups plugin -->
  <script>
  jQuery(function() {
    var icons = {
      header: "dashicons dashicons-arrow-right",
      activeHeader: "dashicons dashicons-arrow-down"
    };
    jQuery( ".tg_admin_accordion" ).accordion({
      icons:icons,
      collapsible: true,
      active: false,
      heightStyle: "content"
    });
  });
  </script>
  <!-- end Tag Groups plugin -->

</div>
