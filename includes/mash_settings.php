<?php 
// Register script
wp_register_script('mash_scripts', plugins_url('js/mash_scripts.js', dirname(__FILE__)), array( 'jquery' ), true);
wp_enqueue_script('mash_scripts');
wp_enqueue_style('google_font', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Poppins:wght@300;400;500;600&display=swap');
$mash_form_action = admin_url('admin.php?page=mash-request-handler');
$mash_boosts_form_action = admin_url('admin.php?page=mash-save-boosts');
$mash_pages       = get_pages();
$mash_posts       = get_posts();
?>

<div class="mash-page">
  <div class="mash-header">
    <div class="mash-logo">
      <img class="mash-logo-img" height="39px" width="180px"src="<?php echo plugin_dir_url( __FILE__ ) . "../images/mash-text-logo.svg" ?>" />
    </div>
    <div class="mash-earner-app-link">   <?php 
        echo  ($settings_earner_id == '' ? '<a class="mash-button cta" href="https://app.mash.com/earn" target="_blank">Sign-up here</a>' : '<a class="mash-button secondary" href="https://app.mash.com/earn" target="_blank">Open Earn With Mash App</a>');
      ?>
    </div>
  </div>

  <div class="mash-content">
    <div class="mash-card add-mash-banner" style="margin-bottom: 24px;">
        <div class="add-mash-img-wrapper">
          <img src="<?php echo plugin_dir_url( __FILE__ ) . "../images/add-mash.svg" ?>"/>
        </div>
        <div class="add-mash-info">
          <h5 class="header-five">
            Augment your site to grow your audience and earn more
          </h5>
          <p class="body-text">
            <a class="link" href="https://mash.com" target="_blank">Mash</a> 
            augments your site by letting your users donate to you, pay you for premium content access, interact with your 
            content with stickers, emojis, gifs and other multiplayer experiences. You can also reward users with 
            Bitcoin as they engage with your content to grow your site. You’ll increase your audiences engagement, 
            grow your audience, and add new revenue streams – all without requiring any developers or major changes to your site. 
          </p>
        </div>
    </div>

    <form id="settings_form" method="post" action=<?php echo esc_attr($mash_form_action) ?> >
      <div class="mash-card connect-mash">
        <div class="connect-mash-left">
          <h5 class="header-five">
            Connect your site to Mash
          </h5>
          <p class="body-text">
            To get your Earner ID login to the 
            <a class="link" href="https://wallet.getmash.com/earn" target="_blank">Earn with Mash App</a> 
            and from the Settings page of the app you can find and copy your Earner ID.
          </p>
        </div>
        <div class="connect-mash vertical-separator"></div>
        <div class="connect-mash-right">
          <div class="mash-input-container">
            <div class="mash-input-label">Earner ID</div>
            <input 
              required
              class="mash-input" 
              placeholder="Ex. b34dbb03-44cf-4dfc-a062-5ee7868f9d16" 
              name="data[earner_id]" 
              value="<?php echo (esc_attr($settings_earner_id)); ?>" 
              pattern="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}" />
          </div>
          <button type="submit" class="mash-button save-button">Save</button>
        </div>
      </div>
    </form>

    <div class="mash-card" style="margin: 16px 0;">
      <h5 class="header-five" >Setup your site with Mash</h5>
      <p class="body-text">
        From the the <a class="link" href="https://app.mash.com/earn" target="_blank">Earn with Mash App</a> you can discover all Mash products, 
        customize and setup Mash on your site and more.  
        Make sure that you’ve connected your site to Mash as outlined above. 
      </p>
      <p class="body-text" style="margin: 1rem 0;"><strong>You’ll be able to add the following to your site –</strong></p>
      <ul class="list body-text">
        <li class="list-item">
          Mash Hub – a small widget to augment your site and engage users with most on-site Mash products & solutions.
        </li>
        <li class="list-item">
          <a class="link" href="https://app.mash.com/earn/hub/donations" target="_blank">Donations</a> 
          – options from spicy memes, for a specific cause that you care about, 
          an item that you want to them to help you purchase, or to buy you a steak & coffee. 
          Unlike other donations that require inputting a credit anytime and a large minimum, 
          users can donate any amount, with one click – paying with Mash or any lightning wallet like CashApp.
        </li>
        <li class="list-item">
        <a class="link" href="https://app.mash.com/earn/page-revealers" target="_blank">Page Revealers</a> 
          – charge any amount for access to premium content. Unlike subscriptions, that require a 
          large purchase commitments and inputting a credit card – your users can easily pay you with one click. 
        </li>
        <li class="list-item">
        <a class="link" href="https://app.mash.com/earn/hub/reactions" target="_blank">Reactions</a> 
          – let users add emojis, stickers, memes and gifs to your content to increase engagement 
          and earn more when they purchase premium reactions.
        </li>
        <li class="list-item">
          <a class="link" href="https://app.mash.com/earn/reward-campaigns" target="_blank">Rewards</a> 
          - provide Bitcoin rewards to your audience automatically as they watch videos, read content, complete a quiz,
          or explore your site. You can do this as part of “treasure hunt” style 
          giveaway campaigns and other marketing efforts.
        </li>
        <li class="list-item">
          More Mash – we’re actively building lots of new solutions & products that might not be listed here. You can learn more at mash.com,  
          express interest in being an early partner or be notified when it is broadly released by 
          <a class="link" href="https://tally.so/r/wbjvLZ" target="_blank">joining the waitlist.</a> 
        </li>
      </ul>
    </div>

    <div class="mash-card resources">
      <h5 class="header-five">
        Connect with the Mash Team:
      </h5>
      <div class="contact-links">
        <a class="help-link" href="https://t.me/mashpartners" target="_blank">
          <img src="<?php echo plugin_dir_url( __FILE__ ) . "../images/telegram.svg" ?>" /> 
          Telegram
        </a>
        <div class="vertical-separator"></div>
        <a class="help-link" href="https://discord.gg/u4tQQXEEhg" target="_blank">
          <img src="<?php echo plugin_dir_url( __FILE__ ) . "../images/discord.svg" ?>"  /> 
          Discord
        </a>
        <div class="vertical-separator"></div>
        <a class="help-link" href="mailto:support@mash.com" target="_blank">
          <img src="<?php echo plugin_dir_url( __FILE__ ) . "../images/mail.svg" ?>" style="margin-right:0.375rem" /> 
          Email
        </a>
      </div>
    </div>
  </div>
</div>


