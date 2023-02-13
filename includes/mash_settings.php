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
      <img src="<?php echo plugin_dir_url( __FILE__ ) . "../images/mash-text-logo.svg" ?>"/>
    </div>
    <div class="mash-earner-app-link">   <?php 
        echo  ($settings_earner_id == '' ? '<a class="mash-button cta" href="https://wallet.getmash.com/earn" target="_blank">New? Create a New Account</a>' : '<a class="mash-button secondary" href="https://wallet.getmash.com/earn" target="_blank">Open Earn With Mash App</a>');
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
            Add Mash to start earning and learn what your users love.
          </h5>
          <p class="body-text">
            <a class="link" href="https://getmash.com" target="_blank">Mash</a> makes it easy for creators like you to earn more for your quality experiences. It’s the fastest & easiest way for your users to donate & pay you for your experiences – no matter the price, large or small. Your users can pay, donate and more for any action, usage, event or experience.
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
            To get your Earner ID login to the <a class="link" href="https://wallet.getmash.com/earn" target="_blank">Earn with Mash App</a> and from the Settings page of the app you can find and copy your Earner ID.
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

    <div class="mash-things flex-row flex-row-equal-children gap-16" style="margin: 16px 0;">
        <div class="mash-card vertical-card donations">
          <div>
            <div>
              <img src="<?php echo plugin_dir_url( __FILE__ ) . "../images/tips.svg" ?>"/>
            </div>
            <h5 class="header-five" style="margin: 40px 0 1rem 0;">
              Allow users to one-click-tip and donate
            </h5>
            <p class="body-text" style="margin-bottom: 2rem;">
              Users can say “thanks” with $0.05 contribution each click, and you get to learn what they love most – without them having to type in a credit card in every-time.
            </p>
          </div>
          <a class="mash-button" style="width:100%;" href="https://wallet.getmash.com/earn/boosts" target="_blank">Add a Floating Boost Button</a>
        </div>
        <div class="mash-card vertical-card paywall">
          <div>
            <div>
              <img src="<?php echo plugin_dir_url( __FILE__ ) . "../images/paywall.svg" ?>"/>
            </div>
            <h5 class="header-five" style="margin: 40px 0 1rem 0;">
              Add a paywall with lightning payments in just a few clicks
            </h5>
            <p class="body-text" style="margin-bottom: 2rem;">
            Set up a full page paywall that allows users to unlock the content behind it. Can be used for articles on news sites, and pre-released content for artists.
            </p>
          </div>
          <a class="mash-button" style="width:100%;" href="https://wallet.getmash.com/earn/page-revealers" target="_blank">Add a Page Revealer</a>
        </div>
        <div class="mash-card vertical-card widgets">
          <div>
            <div>
              <img src="<?php echo plugin_dir_url( __FILE__ ) . "../images/rocketship.svg" ?>"/>
            </div>
            <h5 class="header-five" style="margin: 40px 0 1rem 0;">
              Check Out Our Widget Gallery
            </h5>
            <p class="body-text" style="margin-bottom: 2rem;">
            Lots more monetization & donation options at your fingertips.
            </p>
          </div>
          <a class="mash-button" style="width:100%;" href="https://wallet.getmash.com/earn/widgets" target="_blank">See All Widgets</a>
        </div>
    </div>

    <div class="mash-customize-blocks flex-row flex-row-equal-children gap-16" style="margin-bottom: 24px;">
        <div class="mash-card customize">
          <h5 class="header-five">
            Customize
          </h5>
          <a class="mash-button secondary" style="width:100%;margin: 1rem 0 1.5rem 0;">
          <svg  style="margin-right:0.5rem" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11 7.00006L11.5 6.00004L12.5 5.50003L11.5 5.00002L11 4L10.5 5.00002L9.5 5.50003L10.5 6.00004L11 7.00006ZM6.5 9.0001L7.33313 7.3335L9 6.50005L7.33313 5.6666L6.5 4L5.66688 5.6666L4 6.50005L5.66688 7.3335L6.5 9.0001ZM17.5 13.0002L16.6669 14.6668L15 15.5002L16.6669 16.3337L17.5 18.0003L18.3331 16.3337L20 15.5002L18.3331 14.6668L17.5 13.0002ZM19.7069 6.94475L17.0553 4.29313C16.8603 4.0975 16.6044 4 16.3484 4C16.0925 4 15.8366 4.0975 15.6412 4.29313L4.29313 15.6415C3.9025 16.0321 3.9025 16.6652 4.29313 17.0556L6.94469 19.7072C7.14 19.9025 7.39594 20 7.65156 20C7.9075 20 8.16344 19.9025 8.35875 19.7072L19.7069 8.35852C20.0975 7.96852 20.0975 7.33507 19.7069 6.94475ZM15.2328 10.3582L13.6419 8.76728L16.3481 6.06098L17.9391 7.65195L15.2328 10.3582Z" fill="currentColor"/>
          </svg>
            Set a Global Theme</a>
          <a class="mash-button secondary" style="width:100%;">
            <svg style="margin-right:0.5rem" width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M18.0047 10.4921L17.7485 10.939C17.6547 11.1046 17.4547 11.1734 17.2766 11.1078C16.9078 10.9703 16.5702 10.7734 16.2733 10.5265C16.1296 10.4077 16.0921 10.1983 16.1858 10.0358L16.4421 9.5889C16.2265 9.33888 16.0577 9.04823 15.9452 8.73258H15.4295C15.242 8.73258 15.0795 8.59819 15.0482 8.41068C14.9857 8.03565 14.9826 7.64186 15.0482 7.25121C15.0795 7.06369 15.242 6.92618 15.4295 6.92618H15.9452C16.0577 6.61053 16.2265 6.31988 16.4421 6.06986L16.1858 5.62295C16.0921 5.46043 16.1265 5.25104 16.2733 5.13228C16.5702 4.88538 16.9109 4.68849 17.2766 4.55098C17.4547 4.48535 17.6547 4.55411 17.7485 4.71975L18.0047 5.16666C18.3329 5.10728 18.6673 5.10728 18.9954 5.16666L19.2517 4.71975C19.3455 4.55411 19.5455 4.48535 19.7236 4.55098C20.0924 4.68849 20.4299 4.88538 20.7268 5.13228C20.8706 5.25104 20.9081 5.46043 20.8143 5.62295L20.5581 6.06986C20.7737 6.31988 20.9425 6.61053 21.055 6.92618H21.5707C21.7582 6.92618 21.9207 7.06056 21.9519 7.24808C22.0144 7.62311 22.0176 8.01689 21.9519 8.40755C21.9207 8.59507 21.7582 8.73258 21.5707 8.73258H21.055C20.9425 9.04823 20.7737 9.33888 20.5581 9.5889L20.8143 10.0358C20.9081 10.1983 20.8737 10.4077 20.7268 10.5265C20.4299 10.7734 20.0893 10.9703 19.7236 11.1078C19.5455 11.1734 19.3455 11.1046 19.2517 10.939L18.9954 10.4921C18.6704 10.5515 18.3329 10.5515 18.0047 10.4921ZM17.6766 8.65445C18.8798 9.57952 20.2518 8.20753 19.3267 7.00431C18.1235 6.07611 16.7515 7.45122 17.6766 8.65445ZM14.0732 13.4642L15.1264 13.9893C15.442 14.1705 15.5795 14.5549 15.4545 14.8987C15.1764 15.655 14.6295 16.3488 14.1232 16.9551C13.8919 17.2333 13.4919 17.302 13.1762 17.1208L12.2668 16.5957C11.7667 17.0239 11.1854 17.3645 10.551 17.5864V18.6365C10.551 18.9991 10.2916 19.3116 9.93532 19.3741C9.16651 19.5053 8.36019 19.5116 7.56325 19.3741C7.20385 19.3116 6.9382 19.0022 6.9382 18.6365V17.5864C6.30377 17.3614 5.72248 17.0239 5.22243 16.5957L4.31298 17.1176C4.00046 17.2989 3.5973 17.2302 3.36603 16.952C2.85974 16.3457 2.32532 15.6519 2.04717 14.8987C1.92216 14.5581 2.05967 14.1737 2.37532 13.9893L3.41604 13.4642C3.29415 12.811 3.29415 12.1391 3.41604 11.4828L2.37532 10.9546C2.05967 10.7734 1.91904 10.389 2.04717 10.0483C2.32532 9.292 2.85974 8.59819 3.36603 7.99189C3.5973 7.71374 3.99733 7.64499 4.31298 7.82625L5.22243 8.3513C5.72248 7.92314 6.30377 7.58248 6.9382 7.36059V6.30738C6.9382 5.94797 7.19447 5.63545 7.55075 5.57294C8.31957 5.44168 9.12901 5.43543 9.92595 5.56982C10.2854 5.63232 10.551 5.94172 10.551 6.30738V7.35746C11.1854 7.58248 11.7667 7.92001 12.2668 8.34817L13.1762 7.82313C13.4887 7.64186 13.8919 7.71062 14.1232 7.98877C14.6295 8.59507 15.1608 9.28887 15.4389 10.0452C15.5639 10.3858 15.442 10.7702 15.1264 10.9546L14.0732 11.4797C14.1951 12.136 14.1951 12.8079 14.0732 13.4642ZM10.3979 14.1236C12.248 11.7172 9.50091 8.9701 7.09446 10.8203C5.24431 13.2267 7.99141 15.9738 10.3979 14.1236ZM18.0047 19.8335L17.7485 20.2804C17.6547 20.446 17.4547 20.5148 17.2766 20.4492C16.9078 20.3117 16.5702 20.1148 16.2733 19.8679C16.1296 19.7491 16.0921 19.5397 16.1858 19.3772L16.4421 18.9303C16.2265 18.6803 16.0577 18.3896 15.9452 18.074H15.4295C15.242 18.074 15.0795 17.9396 15.0482 17.7521C14.9857 17.377 14.9826 16.9833 15.0482 16.5926C15.0795 16.4051 15.242 16.2676 15.4295 16.2676H15.9452C16.0577 15.9519 16.2265 15.6613 16.4421 15.4113L16.1858 14.9643C16.0921 14.8018 16.1265 14.5924 16.2733 14.4737C16.5702 14.2268 16.9109 14.0299 17.2766 13.8924C17.4547 13.8267 17.6547 13.8955 17.7485 14.0611L18.0047 14.5081C18.3329 14.4487 18.6673 14.4487 18.9954 14.5081L19.2517 14.0611C19.3455 13.8955 19.5455 13.8267 19.7236 13.8924C20.0924 14.0299 20.4299 14.2268 20.7268 14.4737C20.8706 14.5924 20.9081 14.8018 20.8143 14.9643L20.5581 15.4113C20.7737 15.6613 20.9425 15.9519 21.055 16.2676H21.5707C21.7582 16.2676 21.9207 16.402 21.9519 16.5895C22.0144 16.9645 22.0176 17.3583 21.9519 17.7489C21.9207 17.9365 21.7582 18.074 21.5707 18.074H21.055C20.9425 18.3896 20.7737 18.6803 20.5581 18.9303L20.8143 19.3772C20.9081 19.5397 20.8737 19.7491 20.7268 19.8679C20.4299 20.1148 20.0893 20.3117 19.7236 20.4492C19.5455 20.5148 19.3455 20.446 19.2517 20.2804L18.9954 19.8335C18.6704 19.8929 18.3329 19.8929 18.0047 19.8335ZM17.6766 17.9927C18.8798 18.9178 20.2518 17.5458 19.3267 16.3426C18.1235 15.4175 16.7515 16.7895 17.6766 17.9927Z" fill="currentColor"/>
            </svg>
            Adjust Mash Button Position
          </a>
        </div>
        <div class="mash-card blocks">
          <div>
            <img src="<?php echo plugin_dir_url( __FILE__ ) . "../images/blocks.svg" ?>"/>
          </div>
          <div>
            <h5 class="header-five" style="margin-bottom: 1rem;">
              Use Shortcodes and blocks
            </h5>
            <p class="body-text">
              Add a inline Boost Button or an Accordion Revealer with Shortcodes and blocks.
            </p>
          </div>
        </div>
    </div>
    <div class="mash-card resources">
      <h5 class="header-five" style="margin-bottom: 1.5rem;">
        Resources
      </h5>
      <div class="resource-columns">
        <div class="resource-column">
          <h6 class="resource-header">Guides</h6>
          <a class="resource-link" href="https://guides.getmash.com/share-your-mash-page" target="_blank">
            <div>
              <div class="resource-link-icon">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . "../images/cogs.svg" ?>"/>
              </div>
              <div class="resource-link-text">
                Add Mash on your social media accounts, streams and more
              </div>
            </div>
            <div class="resource-link-angle">
              <img src="<?php echo plugin_dir_url( __FILE__ ) . "../images/right.svg" ?>"/>
            </div>
          </a>
          <a class="resource-link" href="https://wallet.getmash.com/earn/send" target="_blank">
            <div>
              <div class="resource-link-icon">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . "../images/send.svg" ?>"/>
              </div>
              <div class="resource-link-text">
                Send money out of Mash to another wallet
              </div>
            </div>
            <div class="resource-link-angle">
              <img src="<?php echo plugin_dir_url( __FILE__ ) . "../images/right.svg" ?>"/>
            </div>
          </a>
          <a class="resource-link" href="https://wallet.getmash.com/earn/pricing" target="_blank">
            <div>
              <div class="resource-link-icon">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . "../images/coins.svg" ?>"/>
              </div>
              <div class="resource-link-text">
               Set up your pricing, freebies and max spend
              </div>
            </div>
            <div class="resource-link-angle">
              <img src="<?php echo plugin_dir_url( __FILE__ ) . "../images/right.svg" ?>"/> 
            </div>
          </a>
        </div>
        <div class="resource-column">
          <h6 class="resource-header">Developer Resources</h6>
          <a class="resource-link" href="https://www.npmjs.com/package/@getmash/client-sdk" target="_blank">
            <div>
              <div class="resource-link-icon">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . "../images/code.svg" ?>"/>
              </div>
              <div class="resource-link-text">
                NPM Package
              </div>
            </div>
            <div class="resource-link-angle">
              <img src="<?php echo plugin_dir_url( __FILE__ ) . "../images/right.svg" ?>"/>
            </div>
          </a>
          <a class="resource-link" href="https://docs.getmash.com" target="_blank">
            <div>
              <div class="resource-link-icon">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . "../images/code.svg" ?>"/>
              </div>
              <div class="resource-link-text">
                UI Component Builder
              </div>
            </div>
            <div class="resource-link-angle">
              <img src="<?php echo plugin_dir_url( __FILE__ ) . "../images/right.svg" ?>"/>
            </div>
          </a>
          <a class="resource-link" href="https://replit.com/@getmash?tab=repls" target="_blank">
            <div>
              <div class="resource-link-icon">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . "../images/replit.svg" ?>"/>
              </div>
              <div class="resource-link-text">
                Templates on Replit
              </div>
            </div>
            <div class="resource-link-angle">
              <img src="<?php echo plugin_dir_url( __FILE__ ) . "../images/right.svg" ?>"/>
            </div>
          </a>
        </div>
      </div>

      <div class="horizontal-separator"></div>
      <h5 class="header-five" style="margin-bottom:2rem;">
        Get Help or Contact Us Via:
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
        <a class="help-link" href="mailto:support@getmash.com" target="_blank">
          <img src="<?php echo plugin_dir_url( __FILE__ ) . "../images/mail.svg" ?>" style="margin-right:0.375rem" /> 
          Email
        </a>
      </div>
    </div>
  </div>
</div>


