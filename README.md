=== Lightning Simple Social Share ===

Contributors: mohkatz, jojo256
Tags: social media, share buttons, facebook, twitter, linkedin
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Ultra-fast, lightweight social media share buttons with zero dependencies. Privacy-focused and GDPR compliant social sharing for WordPress.

== Description ==

Lightning Simple Social Share is a fasr light-weight social sharing plugin for WordPress. With zero external dependencies and lightning-fast performance, it adds beautiful social media share buttons without slowing down your site.

**Lightning Fast Features:**

* **8 Popular Social Networks**: Facebook, Twitter/X, LinkedIn, Pinterest, WhatsApp, Telegram, Reddit, and Email
* **Zero External Dependencies**: No external JavaScript or CSS libraries - blazing fast loading
* **Ultra Lightweight**: Less than 2KB total footprint
* **GDPR Compliant**: No tracking, no cookies, no personal data collection
* **Privacy First**: Share URLs generated client-side only when clicked
* **Customizable Design**: Choose between rounded or square button styles
* **Flexible Display**: Show on posts, pages, or use shortcodes anywhere
* **Translation Ready**: Fully internationalized and ready for translation
* **Developer Friendly**: Clean, well-documented code following WordPress standards

** Why Choose Lightning Simple Social Share?**

* **Performance First**: No external dependencies mean faster page loads
* **Privacy Focused**: No tracking scripts or data collection
* **Clean Code**: Follows WordPress coding standards and best practices
* **Accessible**: Proper HTML structure and keyboard navigation
* **Responsive**: Looks great on all devices
* **Lightning Fast & Simple**: Easy setup with blazing-fast performance

**Usage:**

1. Configure settings under Settings > Lightning Share
2. Buttons automatically appear on posts/pages (configurable)
3. Use `[lightning_simple_share]` shortcode anywhere
4. Developers can use `do_shortcode('[lightning_simple_share]')` in templates

== Installation ==

1. Upload the plugin file and install through WordPress admin
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Configure settings under Settings > Social Share
4. Share buttons will appear automatically based on your settings

== Frequently Asked Questions ==

= Does this plugin slow down my website? =

No! This plugin is designed for speed. It has zero external dependencies and uses minimal, optimized CSS. The total footprint is less than 2KB.

= Is this plugin GDPR compliant? =

Yes! The plugin doesn't track users, set cookies, or collect any personal data. Share URLs are generated client-side only when clicked.

= Can I customize the button appearance? =

Yes! You can choose between rounded and square button styles. The CSS is also easy to customize if you want to make further changes.

= Which social networks are supported? =

Currently: Facebook, Twitter/X, LinkedIn, Pinterest, WhatsApp, Telegram, Reddit, and Email. More networks can be added in future versions.

= Can I use this with any theme? =

Yes! Lightning Social Share is designed to work with any properly coded WordPress theme.

= How do I add buttons to custom locations? =

Use the `[lightning_simple_share]` shortcode anywhere in your content, or `<?php echo do_shortcode('[lightning_simple_share]'); ?>` in your theme files.

== Screenshots ==

1. Social share buttons in action on a blog post
2. Admin settings panel for easy configuration
3. Responsive design works on all devices
4. Clean, professional button styles

== Changelog ==

= 1.0.0 =
* Initial release of Lightning Simple Social Share
* 8 social sharing networks supported
* Ultra-fast performance with zero external dependencies
* Customizable button styles
* Admin settings panel
* Shortcode support with [lightning_simple_share]
* Translation ready
* GDPR compliant
* Privacy-focused design

== Upgrade Notice ==

= 1.0.0 =
Initial release of Lightning Simple Social Share - the fastest, most lightweight social sharing plugin for WordPress.

== Developer Notes ==

Lightning Simple Social Share follows WordPress coding standards and best practices:

* All output is properly escaped
* User input is sanitized and validated
* Follows WordPress naming conventions
* Uses WordPress APIs exclusively
* Translation ready with proper text domains
* Hooks and filters for extensibility

**Hooks Available:**
* `lsss_share_networks` - Filter available social networks
* `lsss_button_html` - Filter individual button HTML
* `lsss_container_class` - Filter container CSS classes

For support, feature requests, or contributions, please visit the plugin's GitHub repository.
