<?php 
// Register script
wp_register_script('mash_scripts', plugins_url('js/mash_scripts.js', dirname(__FILE__)), array( 'jquery' ), true);
wp_enqueue_script('mash_scripts');
$mash_form_action = admin_url('admin.php?page=mash-request-handler');
$mash_boosts_form_action = admin_url('admin.php?page=mash-save-boosts');
$mash_pages       = get_pages();
$mash_posts       = get_posts();
?>


<div class="wrap mash-settings">
  <h1 class="mash-admin-page-title">Mash</h1>

  <p style="margin-bottom:1.5rem;">
    <a href="https://getmash.com" target="_blank" rel="noopener,noreferrer">Mash</a> makes it easy for your users to pay-you-as-they-enjoy directly, for any amount. Your users can pay, donate and more for any action, 
    usage, event or experience – whether revealing content/information, voting, watching, listening, clicking a “thanks boost” or filling out a form and usage. If you haven't already
    setup an earner account, please visit the <a href="https://wallet.getmash.com/earn" target="_blank" rel="noopener,noreferrer">Earner Dashboard</a> to get started. You will need your <strong>earner_id</strong> from the dashboard.
  </p>

  <form id="settings_form" method="post" action=<?php echo esc_attr($mash_form_action) ?>>
    <div class="form-content">

      <h2 class="form-content-title"> Wallet Settings</h2>

      <div class="form-field">
        <div class="form-field-label">Earner ID</div>
        <div class="form-field-input">
          <input type="text" name="data[earner_id]" value="<?php echo esc_attr($settings_earner_id); ?>" pattern="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}" />
        </div>
      </div>

      <?php 
        $mash_display_array = array(
          'All' => 'All',
          's_pages' => 'Specific Pages/Posts' 
        );
        $mash_settings_show_ex_style = ('All' === $settings_display_on) ? '' : 'display:none;';
        $mash_settings_show_s_style  = ('s_pages' === $settings_display_on) ? '' : 'display:none;';
      ?>

      <div class="form-field">
        <div class="form-field-label">Site Display</div>
        <div class="form-field-input">
          <select name="data[display_on]" onchange="mash_filter_form(this.value);">
            <?php
              foreach ( $mash_display_array as $dkey => $statusv) {
                if ($settings_display_on === $dkey ) {
                  printf('<option value="%1$s" selected="selected">%2$s</option>', esc_attr($dkey), esc_html($statusv));
                } else {
                  printf('<option value="%1$s">%2$s</option>', esc_attr($dkey), esc_html($statusv));
                }
              }
            ?>
          </select>
        </div>
      </div>

      <div id="ex_pages" class="form-field" style="<?php echo esc_attr($mash_settings_show_ex_style); ?>">
        <div class="form-field-label">Exclude Pages</div>
        <div class="form-field-input">
        <select name="data[ex_pages][]" multiple>
              <?php
              foreach ( $mash_pages as $pdata ) {
                  if (in_array($pdata->ID, $settings_ex_pages) ) {
                      printf('<option value="%1$s" selected="selected">%2$s</option>', esc_attr($pdata->ID), esc_html($pdata->post_title));
                  } else {
                      printf('<option value="%1$s">%2$s</option>', esc_attr($pdata->ID), esc_html($pdata->post_title));
                  }
              }
              ?>
          </select>
        </div>
      </div>

      <div id="ex_posts" class="form-field" style="<?php echo esc_attr($mash_settings_show_ex_style); ?>">
        <div class="form-field-label">Exclude Posts</div>
        <div class="form-field-input">
        <select name="data[ex_posts][]" multiple>
              <?php
              foreach ( $mash_posts as $pdata ) {
                  if (in_array($pdata->ID, $settings_ex_posts) ) {
                      printf('<option value="%1$s" selected="selected">%2$s</option>', esc_attr($pdata->ID), esc_html($pdata->post_title));
                  } else {
                      printf('<option value="%1$s">%2$s</option>', esc_attr($pdata->ID), esc_html($pdata->post_title));
                  }
              }
              ?>
          </select>
        </div>
      </div>

      <div id="s_pages" class="form-field"  style="<?php echo esc_attr($mash_settings_show_s_style); ?>">
        <div class="form-field-label">Page List</div>
        <div class="form-field-input">
        <select name="data[s_pages][]" multiple>
              <?php
              foreach ( $mash_pages as $pdata ) {
                  if (in_array($pdata->ID, $settings_s_pages) ) {
                      printf('<option value="%1$s" selected="selected">%2$s</option>', esc_attr($pdata->ID), esc_html($pdata->post_title));
                  } else {
                      printf('<option value="%1$s">%2$s</option>', esc_attr($pdata->ID), esc_html($pdata->post_title));
                  }
              }
              ?>
          </select>
        </div>
      </div>

      <div id="s_posts" class="form-field"  style="<?php echo esc_attr($mash_settings_show_s_style); ?>">
        <div class="form-field-label">Post List</div>
        <div class="form-field-input">
        <select name="data[s_posts][]" multiple>
              <?php
              foreach ( $mash_posts as $pdata ) {
                  if (in_array($pdata->ID, $settings_s_posts) ) {
                      printf('<option value="%1$s" selected="selected">%2$s</option>', esc_attr($pdata->ID), esc_html($pdata->post_title));
                  } else {
                      printf('<option value="%1$s">%2$s</option>', esc_attr($pdata->ID), esc_html($pdata->post_title));
                  }
              }
              ?>
          </select>
        </div>
      </div>

      <div>
        <input class="button button-primary button-large" type="submit" value="Save"/>
      </div>

    </div>
  </form>

  <form id="boosts_form" class="boost-form" method="post" action=<?php echo esc_attr($mash_boosts_form_action) ?>>
    <div class="form-content">
      <h2 class="form-content-title">Boosts - Single-click-donations!</h2>
      <h3 class="form-content-boosts-subtitle">Every time a user clicks or taps boosts, their tipping you $0.05 USD</h3>
      
      <div>
        <h4 class="boosts-section-header">Page & Post Placement</h4>

        <h5 class="boosts-placement-callout">Mash Boost Button will only show up on the page if the Wallet has been enabled on it. Only pages that are enabled are selectable in the form below. </h4>

        <?php
          $mash_boosts_display_on = array(
            'None' => 'None',
            'All' => 'All',
            's_pages' => 'Specific Pages/Posts'
          );
          $mash_boosts_show_ex_style = ('All' === $boosts_display_on) ? '' : 'display:none;';
          $mash_boosts_show_s_style  = ('s_pages' === $boosts_display_on) ? '' : 'display:none;';

          function mash_collect_ids($obj) {
            return $obj->ID;
          }


          // Only show pages where the wallet is enabled
          $mash_allowed_boost_pages = get_pages();
          $mash_allowed_boost_posts = get_posts();

          $mash_post_ids = array_map('mash_collect_ids', $mash_posts);
          $mash_page_ids = array_map('mash_collect_ids', $mash_pages);

          $mash_filtered_pages = array();
          $mash_filtered_posts = array();

          if ($settings_display_on === 'All') {
            $mash_filtered_pages = array_diff($mash_page_ids, $settings_ex_pages);
            $mash_filtered_posts = array_diff($mash_post_ids, $settings_ex_posts);
          } else {
            $mash_filtered_pages = array_intersect($mash_page_ids, $settings_s_pages);
            $mash_filtered_posts = array_intersect($mash_post_ids, $settings_s_posts);
          }

          $mash_allowed_boost_pages = array_filter($mash_allowed_boost_pages, function($p) use($mash_filtered_pages) {
            return in_array($p->ID, $mash_filtered_pages);
          });

          $mash_allowed_boost_posts = array_filter($mash_allowed_boost_posts, function($p) use($mash_filtered_posts) {
            return in_array($p->ID, $mash_filtered_posts);
          });
        ?> 

        <div class="form-field">
          <div class="form-field-label">Site Display</div>
          <div class="form-field-input">
            <select name="data[display_on]" onchange="mash_boosts_form_display_control(this.value);">
              <?php
                foreach ( $mash_boosts_display_on as $dkey => $statusv ) {
                  if ($boosts_display_on === $dkey ) {
                    printf('<option value="%1$s" selected="selected">%2$s</option>', esc_attr($dkey), esc_html($statusv));
                  } else {
                    printf('<option value="%1$s">%2$s</option>', esc_attr($dkey), esc_html($statusv));
                  }
                }
              ?>
            </select>
          </div>
        </div>

        <div class="form-field boosts_exclude_picker" style="<?php echo esc_attr($mash_boosts_show_ex_style); ?>">
          <div class="form-field-label">Exclude Pages</div>
          <div class="form-field-input">
            <select name="data[ex_pages][]" multiple>
                  <?php
                  foreach ( $mash_allowed_boost_pages as $pdata ) {
                      if (in_array($pdata->ID, $boosts_ex_pages) ) {
                          printf('<option value="%1$s" selected="selected">%2$s</option>', esc_attr($pdata->ID), esc_html($pdata->post_title));
                      } else {
                          printf('<option value="%1$s">%2$s</option>', esc_attr($pdata->ID), esc_html($pdata->post_title));
                      }
                  }
                  ?>
              </select>
          </div>
        </div>

        <div class="form-field boosts_exclude_picker" style="<?php echo esc_attr($mash_boosts_show_ex_style); ?>">
          <div class="form-field-label">Exclude Posts</div>
          <div class="form-field-input">
            <select name="data[ex_posts][]" multiple>
                  <?php
                  foreach ( $mash_allowed_boost_posts as $pdata ) {
                      if (in_array($pdata->ID, $boosts_ex_posts) ) {
                          printf('<option value="%1$s" selected="selected">%2$s</option>', esc_attr($pdata->ID), esc_html($pdata->post_title));
                      } else {
                          printf('<option value="%1$s">%2$s</option>', esc_attr($pdata->ID), esc_html($pdata->post_title));
                      }
                  }
                  ?>
            </select>
          </div>
        </div>


        <div class="form-field boosts_includes_picker"  style="<?php echo esc_attr($mash_boosts_show_s_style); ?>">
        <div class="form-field-label">Page List</div>
        <div class="form-field-input">
          <select name="data[s_pages][]" multiple>
                <?php
                foreach ( $mash_allowed_boost_pages as $pdata ) {
                    if (in_array($pdata->ID, $boosts_s_pages) ) {
                        printf('<option value="%1$s" selected="selected">%2$s</option>', esc_attr($pdata->ID), esc_html($pdata->post_title));
                    } else {
                        printf('<option value="%1$s">%2$s</option>', esc_attr($pdata->ID), esc_html($pdata->post_title));
                    }
                }
                ?>
            </select>
          </div>
        </div>

        <div class="form-field boosts_includes_picker"  style="<?php echo esc_attr($mash_boosts_show_s_style); ?>">
          <div class="form-field-label">Post List</div>
          <div class="form-field-input">
            <select name="data[s_posts][]" multiple>
                <?php
                foreach ( $mash_allowed_boost_posts as $pdata ) {
                    if (in_array($pdata->ID, $boosts_s_posts) ) {
                        printf('<option value="%1$s" selected="selected">%2$s</option>', esc_attr($pdata->ID), esc_html($pdata->post_title));
                    } else {
                        printf('<option value="%1$s">%2$s</option>', esc_attr($pdata->ID), esc_html($pdata->post_title));
                    }
                }
                ?>
            </select>
          </div>
        </div>

      </div>
      
      <div class="boosts-customization-container">
        <div class="boosts-customization-left-panel">
          <div class="form-section">
            <h4 class="boosts-section-header">Location</h4>
              <?php 
                $mash_location_options = array(
                  'bottom-center' => 'bottom-center  (recommended)',
                  'bottom-left'   => 'bottom-left',
                  'top-center'    => 'top-center',
                  'top-middle'    => 'bottom-middle',
                  'top-right'     => 'bottom-right',
                );
              ?>
              <div class="boosts-radio-group">
                <?php
                  foreach ( $mash_location_options as $lkey => $loc) {
                  
                    $input = '<input type="radio" id="location" name="data[location]" value="' . esc_attr($lkey) . '"';
                    if ($boosts_location === $lkey) {
                      $input .= ' checked="checked"';
                    }
                    $input .= " />";

                    printf('
                      <div class="boosts-radio-button">
                        %1$s
                        <label for="location">%2$s</label>
                      </div>',
                        $input,
                        esc_attr($loc)
                    );            
                  }
                ?>
              </div>
          </div>

          <div class="form-section">
            <h4 class="boosts-section-header">Button Design</h4>
              <?php 
                $mash_variant_options = array(
                  'colorized' => 'colorized',
                  'light'     => 'light',
                  'dark'      => 'dark',
                );
              ?>
              <div class="boosts-radio-group">
                <?php
                  foreach ( $mash_variant_options as $vkey => $variant) {
                  
                    $input = '<input type="radio" id="variant" name="data[variant]" value="' . esc_attr($vkey) . '"';
                    if ($boosts_variant === $vkey) {
                      $input .= ' checked="checked"';
                    }
                    $input .= " />";

                    printf('
                      <div class="boosts-radio-button">
                        %1$s
                        <label for="variant">%2$s</label>
                      </div>',
                        $input,
                        esc_attr($variant)
                    );            
                  }
                ?>
              </div>
          </div>

          <div class="form-section">
            <h4 class="boosts-section-header">Button Theme</h4> 
              <?php 
                $mash_icon_options = array(
                  'lightning' => 'lightning',
                  'heart'     => 'heart',
                  'fire'      => 'fire',
                );
              ?>
              <div class="boosts-radio-group">
                <?php
                  foreach ( $mash_icon_options as $ikey => $icon) {
                  
                    $input = '<input type="radio" id="icon" name="data[icon]" value="' . esc_attr($ikey) . '"';
                    if ($boosts_icon === $ikey) {
                      $input .= ' checked="checked"';
                    }
                    $input .= " />";

                    printf('
                      <div class="boosts-radio-button">
                        %1$s
                        <label for="icon">%2$s</label>
                      </div>',
                        $input,
                        esc_attr($icon)
                    );            
                  }
                ?>
              </div>
          </div>
        </div>
        <div class="boosts-customization-right-panel">
          <image src="<?php echo plugin_dir_url( __FILE__ ) . "../images/boosts.svg" ?>" class="boost-img" style="max-width:600px;"/>
        </div>
      </div>

      <div style="margin-top:1.25rem;">
        <input class="button button-primary button-large" type="submit" value="Save"/>
      </div>

    </div>
  </form>
</div>

