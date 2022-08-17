<?php 
// Register script
wp_register_script('mash_scripts', plugins_url('js/mash_scripts.js', dirname(__FILE__)), array( 'jquery' ));
wp_enqueue_script('mash_scripts');
$mash_form_action = admin_url('admin.php?page=mash-request-handler');
?>


<div class="wrap">
  <h1 class="mash-admin-page-title">Mash Wallet Settings</h1>

  <p style="margin-bottom:1.5rem;">
    <a href="https://getmash.com" rel="noopenenr,noreferrer">Mash</a> makes it easy for your users to pay-you-as-they-enjoy directly, for any amount. Your users can pay, donate and more for any action, 
    usage, event or experience – whether revealing content/information, voting, watching, listening, clicking a “thanks boost” or filling out a form and usage. If you haven't already
    setup an earner account, please visit the <a href="https://wallet.getmash.com/earn" rel="noopenenr,noreferrer">Earner Dashboard</a> to get started. You will need your <strong>earner_id</strong> from the dashboard.
  </p>

  <form method="post" action=<?php echo esc_attr($mash_form_action) ?>>
    <div class="form-content">
      <div class="form-field">
        <div class="form-field-label">Earner ID</div>
        <div class="form-field-input">
          <input type="text" name="data[earner_id]" value="<?php echo esc_attr($earner_id); ?>" pattern="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}" />
        </div>
      </div>

      <?php 
        $mash_display_array = array(
          'All' => 'All',
          's_pages' => 'Specific Pages/Posts' 
        );
      ?>

      <div class="form-field">
        <div class="form-field-label">Site Display</div>
        <div class="form-field-input">
          <select name="data[display_on]" onchange="mash_filter_form(this.value);">
            <?php
              foreach ( $mash_display_array as $dkey => $statusv) {
                if ($display_on === $dkey ) {
                  printf('<option value="%1$s" selected="selected">%2$s</option>', $dkey, $statusv);
                } else {
                  printf('<option value="%1$s">%2$s</option>', $dkey, $statusv);
                }
              }
            ?>
          </select>
        </div>
      </div>

      <?php
      $mash_pages       = get_pages();
      $mash_posts       = get_posts();
      $mash_ex_pages_style = ('All' === $display_on) ? '' : 'display:none;';
      $mash_ex_posts_style = ('All' === $display_on) ? '' : 'display:none;';
      ?>

      <div id="ex_pages" class="form-field" style="<?php echo esc_attr($mash_ex_pages_style); ?>">
        <div class="form-field-label">Exclude Pages</div>
        <div class="form-field-input">
        <select name="data[ex_pages][]" multiple>
              <?php
              foreach ( $mash_pages as $pdata ) {
                  if (in_array($pdata->ID, $ex_pages) ) {
                      printf('<option value="%1$s" selected="selected">%2$s</option>', esc_attr($pdata->ID), esc_attr($pdata->post_title));
                  } else {
                      printf('<option value="%1$s">%2$s</option>', esc_attr($pdata->ID), esc_attr($pdata->post_title));
                  }
              }
              ?>
          </select>
        </div>
      </div>

      <div id="ex_posts" class="form-field" style="<?php echo esc_attr($mash_ex_posts_style); ?>">
        <div class="form-field-label">Exclude Posts</div>
        <div class="form-field-input">
        <select name="data[ex_posts][]" multiple>
              <?php
              foreach ( $mash_posts as $pdata ) {
                  if (in_array($pdata->ID, $ex_posts) ) {
                      printf('<option value="%1$s" selected="selected">%2$s</option>', esc_attr($pdata->ID), esc_attr($pdata->post_title));
                  } else {
                      printf('<option value="%1$s">%2$s</option>', esc_attr($pdata->ID), esc_attr($pdata->post_title));
                  }
              }
              ?>
          </select>
        </div>
      </div>

      <?php
        $mash_pages       = get_pages();
        $mash_posts       = get_posts();
        $mash_pages_style = ('s_pages' === $display_on) ? '' : 'display:none;';
        $mash_posts_style = ('s_pages' === $display_on) ? '' : 'display:none;';
      ?>

      <div id="s_pages" class="form-field"  style="<?php echo esc_attr($mash_pages_style); ?>">
        <div class="form-field-label">Page List</div>
        <div class="form-field-input">
        <select name="data[s_pages][]" multiple>
              <?php
              foreach ( $mash_pages as $pdata ) {
                  if (in_array($pdata->ID, $s_pages) ) {
                      printf('<option value="%1$s" selected="selected">%2$s</option>', esc_attr($pdata->ID), esc_attr($pdata->post_title));
                  } else {
                      printf('<option value="%1$s">%2$s</option>', esc_attr($pdata->ID), esc_attr($pdata->post_title));
                  }
              }
              ?>
          </select>
        </div>
      </div>

      <div id="s_posts" class="form-field"  style="<?php echo esc_attr($mash_posts_style); ?>">
        <div class="form-field-label">Post List</div>
        <div class="form-field-input">
        <select name="data[s_posts][]" multiple>
              <?php
              foreach ( $mash_posts as $pdata ) {
                  if (in_array($pdata->ID, $s_posts) ) {
                      printf('<option value="%1$s" selected="selected">%2$s</option>', esc_attr($pdata->ID), esc_attr($pdata->post_title));
                  } else {
                      printf('<option value="%1$s">%2$s</option>', esc_attr($pdata->ID), esc_attr($pdata->post_title));
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
</div>

