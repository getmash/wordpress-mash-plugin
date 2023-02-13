=== Mash - Monetize, Earn, and Grow your Experiences w/ Bitcoin Lightning ===
 
Contributors: getmash
Tags: bitcoin, lightning, creators, mash, monetize, revenue, interaction, writers, bloggers, payments, developers, earn 
Requires at least: 6.0.0
Tested up to: 6.0.2
Stable tag: 2.1.0
Requires PHP: 7.3.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
  
Setup and configure Mash's Creator Monetization tools on your WordPress site. Earn more, learn what your customers love in a new and and interactive way!
  
== Overview of Creator Monetization Tools ==
  
[Mash](https://getmash.com) makes it easy to earn more for your quality experiences. It’s the fastest & easiest way for your users to donate & pay for your content – at any price, large or small. It works for articles, instant-one-click-donations, video content, downloading files and more.

**Boosts**, let your fans one-click-donate and say “thanks” at the same time. Each tap is a $0.05 contribution, and you get to learn what they love most – without them having to type in a credit card in every-time. 

**Content Monetization Widgets** allow you to earn more in new and interactive ways – no large purchase commitment barriers. You set pricing and they pay-as-they-enjoy to access an article, watch a great video, and even to get secret details like your email. You can have your users support you automatically, or with one-click for your amazing experiences. 

**Mash Wallet** enables your users have access to their money to use on your site, across the internet, and to send where they want.

With Mash, you’re adding payments built for creators – earning while building relationships with more of your fans, in an entirely new and interactive way! We’re here to help you unlock better business models that you and your users will love.

== Getting Started ==

= Set up your account =

Visit the [Earn with Mash Web App](https://wallet.getmash.com/earn) and set up an account, it should only take two minutes. Get your “Earner ID” from the settings tab on the left side. You’ll use it to configure the Mash plug-in to start earning for content and getting single-click donations.

= Add Mash Wallet =

Use the Mash Wordpress Plugin directly to add the Mash wallet to your site. Use the Mash Platform to configure how and where you want Mash to appear on your site.

= Add Floating Boosts to your site or specific pages =
Use the Wordpress plugin to add the Mash wallet to the pages and posts of your choosing. Once your "Earner ID" is configured in the plugin, navigate to [Boosts](https://wallet.getmash.com/earn/boosts) in the Mash Platform and configure how and where you want Boosts to show up on your site.

= Add Page Revealer to your site or specific pages =
Use the Wordpress plugin to add the Mash wallet to the pages and posts of your choosing. Once your "Earner ID" is configured in the plugin, navigate to [Page Revealers](https://wallet.getmash.com/earn/page-revealers) in the Mash Platform and configure how and where you want a Page Revealer to show up on your site. The Page Revealer allows you request payment to access a piece of content on your site.

= Embedded HTML Block Capabilities =
You can monetize anything using embedded HTML. More will be available with Blocks and short-codes. Reach out if you want to be a beta tester. Find out more at [Mash](https://getmash.com), [Guides](https://guides.getmash.com) + [Storybook](https://docs.getmash.com) for overviews and design canvases.

* Articles & Blog Posts – From a review of your favourite products, to a how-to guide or recipe. Hide some content in a section or an entire post.
* Downloads – Share high-quality assets for a fee. For cheat sheets, mods, image assets, filter packs and more.
* Videos – For premium deep-dives, behind the scenes content and more
* Links – Have a great resource to share but want users to contribute a small bit ask users to contribute for premium links to resources.
* Donate Buttons – Let them say thanks, but a bit more than the boost.
* Donations and support from everywhere online. Share your lightning address, lightning QR code and Mash Page with your users to support you directly from all your channels, including LinkTree, withKoji, Twitter, Reddit, Instagram, Facebook, Stackoverflow, Github… you name it!

= Shortcodes = 

* [mash_boost] - Loads the Mash Boost button for one-click donations. You can adjust the designs, floating locations and more. Here's a guide for currently available properties and how to adjust the(https://guides.getmash.com/wordpress-add-boosts) & [here's a design storybook canvas](https://docs.getmash.com/?path=/docs/donations-boost-button--boost-button).
* [mash_paywall] - Wraps content in a Mash-powered paywall.

= Adding more monetization =

With the consumer wallet, it also allows you to charge your users, and get paid for your amazing content with Mash’s monetization widgets – you can give freebies, and have different prices/tiers for each. Currently, you can add them easily with [Custom HTML blocks](https://wordpress.com/support/wordpress-editor/blocks/custom-html-block/). And the [Mash Guides](https://guides.getmash.com) + [Storybook](https://docs.getmash.com) documents have general overviews.

== Installation ==

1. Install the plugin through your main Wordpress Dashboard at wp-admin/plugin-install.php. There are [detailed instructions available for Wordpress at](https://guides.getmash.com/wordpress-add-consumer-wallet).

OR

1. Download the plugin from the link at the top right of this page
2. Upload the plugin folder to your /wp-content/plugins/ folder.
3. Go to the **Plugins** page and activate the plugin.

Once the plugin is installed, make sure you have a Mash Earner account. This account can be created here: https://wallet.getmash.com/earn

== Frequently Asked Questions ==
  
= How do I use this plugin? =
  
Add your Earner Id from your Mash account, and select which pages & posts that you want the wallet to appear. Do the same for Boosts or use blocks or short-codes. The plugin will automatically add the necessary code to load the Mash Wallet & Content Monetization on the selected pages.
  
= How to uninstall the plugin? =
  
Simply deactivate and delete the plugin. 
  
== Screenshots ==

1. Mash Boosts
2. Mash Written Content
3. Mash Video Content
4. Mash Wallet
  
== Changelog ==

= 2.1.0 =
* MODIFIED: Shortcodes to use updated web components
* MODIFIED: Renamed Mash Paywall block to Mash Content Revealer
* ADDED: mash_content_revealer shortcode, deprecated mash_paywall
* ADDED: Additional configuration options for Mash Boost shortcode
* ADDED: Additional configuration options for Mash Content Revealer

= 2.0.1 =
* FIXED: Dashboard menu icon for Mash shows up now.

= 2.0.0 =
* ADDED: New SDK initialization to allow Mash Platform configuration settings to take effect on wordpress site. This allow all configuration to be completed in the [Mash Platform](https://wallet.getmash.com/earn)
* MODIFIED: Revamped plugin settings page and simplified Mash Platform configuration.
* REMOVED: Ability to configure where the Mash Wallet appears on your site. You can now be configure the Mash Wallet in the [Mash Platform](https://wallet.getmash.com/earn/install)
* REMOVED: Ability to configure boosts in the Plugin Settings. You can add Boosts through the [Mash Platform](https://wallet.getmash.com/earn/boosts)

= 1.4.1 =
* FIXED: Issue with boost shortcode colliding with previous, stale version

= 1.4.0 =
* ADDED: Mash Paywall component shortcode and Gutenberg block

= 1.3.6 =
* FIXED: Logic around boosts rendering on certain pages

= 1.3.5 =
* FIXED: Radio button labels selecting correct option

= 1.3.4 =
* FIXED: Wallet + Boosts not showing on all pages/posts
* FIXED: Boosts showing on pages Wallet was not enabled

= 1.3.3 =
* FIXED: Language in mash settings page
* FIXED: Incorrect radio button labels for boost location

= 1.3.2 =
* ADDED: Improved screenshots to plugin page
* ADDED: Boosts descriptions to plugin settings page
* FIXED: Copy around plugin description

= 1.3.1 =
* FIXED: Drop default value from text columns since it is not supported in database

= 1.3.0 =
* ADDED: Global boost configuration

= 1.2.0 =
* ADDED: Mash boost button gutenberg block

= 1.1.0 =
* ADDED: Mash boost button shortcode

= 1.0.0 =
* Plugin released
* ADDED: Mash Wallet Plugin core implementation
