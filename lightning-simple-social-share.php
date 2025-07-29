<?php
/**
 * Plugin Name: Lightning Simple Social Share
 * Plugin URI: https://github.com/mohkatz/lightning-simple-social-share
 * Description: Ultra-fast, zero-dependency social media share buttons. Privacy-focused and lightweight social sharing for WordPress.
 * Version: 1.0.0
 * Author: Mohammed Kateregga
 * Author URI: https://profiles.wordpress.org/mohkatz/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: lightning-simple-social-share
 * Requires at least: 5.0
 * Tested up to: 6.8
 * Requires PHP: 7.4
 *
 * Lightning Simple Social Share is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('LSSS_VERSION', '1.0.0');
define('LSSS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('LSSS_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Main plugin class
 */
class LightningSimpleSocialShare {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    public function init() {
        // Initialize admin
        if (is_admin()) {
            add_action('admin_menu', array($this, 'add_admin_menu'));
            add_action('admin_init', array($this, 'admin_init'));
        }
        
        // Frontend hooks
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_filter('the_content', array($this, 'add_share_buttons'));
        add_shortcode('lightning_simple_share', array($this, 'shortcode'));
    }
    
    public function activate() {
        // Set default options
        $default_options = array(
            'enabled_networks' => array('facebook', 'twitter', 'linkedin'),
            'button_style' => 'rounded',
            'show_on_posts' => 1,
            'show_on_pages' => 0,
            'position' => 'after_content'
        );
        
        if (!get_option('lsss_options')) {
            add_option('lsss_options', $default_options);
        }
    }
    
    public function deactivate() {
        // Clean up if needed
    }
    
    public function enqueue_scripts() {
        // Register a dummy style handle and add inline CSS to it
        wp_register_style('lsss-dummy-handle', false, array(), LSSS_VERSION);
        wp_enqueue_style('lsss-dummy-handle');
        
        $css = "
        .lsss-share-buttons {
            margin: 20px 0;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .lsss-share-button {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            text-decoration: none;
            color: white;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            transition: opacity 0.2s ease;
        }
        .lsss-share-button:hover {
            opacity: 0.8;
            color: white;
        }
        .lsss-facebook { background-color: #1877f2; }
        .lsss-twitter { background-color: #1da1f2; }
        .lsss-linkedin { background-color: #0077b5; }
        .lsss-pinterest { background-color: #bd081c; }
        .lsss-whatsapp { background-color: #25d366; }
        .lsss-telegram { background-color: #0088cc; }
        .lsss-reddit { background-color: #ff4500; }
        .lsss-email { background-color: #34495e; }
        .lsss-share-buttons.rounded .lsss-share-button {
            border-radius: 25px;
        }
        .lsss-share-buttons.square .lsss-share-button {
            border-radius: 0;
        }
        ";
        
        wp_add_inline_style('lsss-dummy-handle', $css);
    }
    
    public function add_admin_menu() {
        add_options_page(
            esc_html__('Lightning Simple Social Share Settings', 'lightning-simple-social-share'),
            esc_html__('Lightning Share', 'lightning-simple-social-share'),
            'manage_options',
            'lightning-simple-social-share',
            array($this, 'admin_page')
        );
    }
    
    public function admin_init() {
        register_setting('lsss_options_group', 'lsss_options', array($this, 'sanitize_options'));
        
        add_settings_section(
            'lsss_main_section',
            esc_html__('Share Button Settings', 'lightning-simple-social-share'),
            null,
            'lightning-simple-social-share'
        );
        
        add_settings_field(
            'enabled_networks',
            esc_html__('Enabled Networks', 'lightning-simple-social-share'),
            array($this, 'enabled_networks_callback'),
            'lightning-simple-social-share',
            'lsss_main_section'
        );
        
        add_settings_field(
            'display_options',
            esc_html__('Display Options', 'lightning-simple-social-share'),
            array($this, 'display_options_callback'),
            'lightning-simple-social-share',
            'lsss_main_section'
        );
    }
    
    public function sanitize_options($input) {
        $sanitized = array();
        
        if (isset($input['enabled_networks']) && is_array($input['enabled_networks'])) {
            $sanitized['enabled_networks'] = array_map('sanitize_text_field', $input['enabled_networks']);
        } else {
            $sanitized['enabled_networks'] = array();
        }
        
        $sanitized['button_style'] = sanitize_text_field($input['button_style'] ?? 'rounded');
        $sanitized['show_on_posts'] = isset($input['show_on_posts']) ? 1 : 0;
        $sanitized['show_on_pages'] = isset($input['show_on_pages']) ? 1 : 0;
        $sanitized['position'] = sanitize_text_field($input['position'] ?? 'after_content');
        
        return $sanitized;
    }
    
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Lightning Simple Social Share Settings', 'lightning-simple-social-share'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('lsss_options_group');
                do_settings_sections('lightning-simple-social-share');
                submit_button();
                ?>
            </form>
            
            <div style="margin-top: 30px; padding: 15px; background: #f1f1f1; border-radius: 5px;">
                <h3><?php esc_html_e('Usage', 'lightning-simple-social-share'); ?></h3>
                <p><?php esc_html_e('Use the shortcode:', 'lightning-simple-social-share'); ?> <code>[lightning_simple_share]</code></p>
                <p><?php esc_html_e('Or add buttons programmatically:', 'lightning-simple-social-share'); ?> <code>&lt;?php echo do_shortcode('[lightning_simple_share]'); ?&gt;</code></p>
            </div>
        </div>
        <?php
    }
    
    public function enabled_networks_callback() {
        $options = get_option('lsss_options', array());
        $enabled = $options['enabled_networks'] ?? array('facebook', 'twitter', 'linkedin');
        
        $networks = array(
            'facebook' => 'Facebook',
            'twitter' => 'Twitter/X',
            'linkedin' => 'LinkedIn',
            'pinterest' => 'Pinterest',
            'whatsapp' => 'WhatsApp',
            'telegram' => 'Telegram',
            'reddit' => 'Reddit',
            'email' => 'Email'
        );
        
        foreach ($networks as $key => $label) {
            $checked = in_array($key, $enabled) ? 'checked' : '';
            echo "<label><input type='checkbox' name='lsss_options[enabled_networks][]' value='" . esc_attr($key) . "' " . esc_attr($checked) . "> " . esc_html($label) . "</label><br>";
        }
    }
    
    public function display_options_callback() {
        $options = get_option('lsss_options', array());
        ?>
        <p>
            <label><?php esc_html_e('Button Style:', 'lightning-simple-social-share'); ?></label><br>
            <select name="lsss_options[button_style]">
                <option value="rounded" <?php selected($options['button_style'] ?? 'rounded', 'rounded'); ?>><?php esc_html_e('Rounded', 'lightning-simple-social-share'); ?></option>
                <option value="square" <?php selected($options['button_style'] ?? 'rounded', 'square'); ?>><?php esc_html_e('Square', 'lightning-simple-social-share'); ?></option>
            </select>
        </p>
        
        <p>
            <label><input type="checkbox" name="lsss_options[show_on_posts]" value="1" <?php checked($options['show_on_posts'] ?? 1); ?>> <?php esc_html_e('Show on Posts', 'lightning-simple-social-share'); ?></label><br>
            <label><input type="checkbox" name="lsss_options[show_on_pages]" value="1" <?php checked($options['show_on_pages'] ?? 0); ?>> <?php esc_html_e('Show on Pages', 'lightning-simple-social-share'); ?></label>
        </p>
        
        <p>
            <label><?php esc_html_e('Position:', 'lightning-simple-social-share'); ?></label><br>
            <select name="lsss_options[position]">
                <option value="before_content" <?php selected($options['position'] ?? 'after_content', 'before_content'); ?>><?php esc_html_e('Before Content', 'lightning-simple-social-share'); ?></option>
                <option value="after_content" <?php selected($options['position'] ?? 'after_content', 'after_content'); ?>><?php esc_html_e('After Content', 'lightning-simple-social-share'); ?></option>
            </select>
        </p>
        <?php
    }
    
    public function add_share_buttons($content) {
        // Prevent infinite loops and multiple executions
        static $processing = false;
        if ($processing) {
            return $content;
        }
        
        // Only process in main query and singular posts/pages
        if (!is_singular() || !in_the_loop() || !is_main_query()) {
            return $content;
        }
        
        // Additional check to prevent duplication
        if (strpos($content, 'lsss-share-buttons') !== false) {
            return $content;
        }
        
        $processing = true;
        
        $options = get_option('lsss_options', array());
        
        // Check if we should show buttons
        $show = false;
        if (is_single() && ($options['show_on_posts'] ?? 1)) {
            $show = true;
        }
        if (is_page() && ($options['show_on_pages'] ?? 0)) {
            $show = true;
        }
        
        if (!$show) {
            $processing = false;
            return $content;
        }
        
        $buttons = $this->generate_share_buttons();
        $position = $options['position'] ?? 'after_content';
        
        if ($position === 'before_content') {
            $result = $buttons . $content;
        } else {
            $result = $content . $buttons;
        }
        
        $processing = false;
        return $result;
    }
    
    public function shortcode($atts = array()) {
        return $this->generate_share_buttons($atts);
    }
    
    private function generate_share_buttons($atts = array()) {
        $options = get_option('lsss_options', array());
        $enabled_networks = $options['enabled_networks'] ?? array('facebook', 'twitter', 'linkedin');
        $button_style = $options['button_style'] ?? 'rounded';
        
        // Get current post data safely
        global $post;
        if (!$post || !is_object($post)) {
            return '';
        }
        
        $url = urlencode(get_permalink($post->ID));
        $title = urlencode(get_the_title($post->ID));
        
        // Get excerpt safely without triggering content filters
        $excerpt_text = '';
        if (!empty($post->post_excerpt)) {
            $excerpt_text = $post->post_excerpt;
        } else {
            $excerpt_text = wp_strip_all_tags($post->post_content);
        }
        $excerpt = urlencode(wp_trim_words($excerpt_text, 20));
        
        $share_urls = array(
            'facebook' => "https://www.facebook.com/sharer/sharer.php?u={$url}",
            'twitter' => "https://twitter.com/intent/tweet?url={$url}&text={$title}",
            'linkedin' => "https://www.linkedin.com/sharing/share-offsite/?url={$url}",
            'pinterest' => "https://pinterest.com/pin/create/button/?url={$url}&description={$title}",
            'whatsapp' => "https://wa.me/?text={$title}%20{$url}",
            'telegram' => "https://t.me/share/url?url={$url}&text={$title}",
            'reddit' => "https://reddit.com/submit?url={$url}&title={$title}",
            'email' => "mailto:?subject={$title}&body={$excerpt}%20{$url}"
        );
        
        $network_labels = array(
            'facebook' => esc_html__('Facebook', 'lightning-simple-social-share'),
            'twitter' => esc_html__('Twitter', 'lightning-simple-social-share'),
            'linkedin' => esc_html__('LinkedIn', 'lightning-simple-social-share'),
            'pinterest' => esc_html__('Pinterest', 'lightning-simple-social-share'),
            'whatsapp' => esc_html__('WhatsApp', 'lightning-simple-social-share'),
            'telegram' => esc_html__('Telegram', 'lightning-simple-social-share'),
            'reddit' => esc_html__('Reddit', 'lightning-simple-social-share'),
            'email' => esc_html__('Email', 'lightning-simple-social-share')
        );
        
        $output = '<div class="lsss-share-buttons ' . esc_attr($button_style) . '">';
        
        foreach ($enabled_networks as $network) {
            if (isset($share_urls[$network])) {
                $target = $network === 'email' ? '' : ' target="_blank"';
                $rel = $network === 'email' ? '' : ' rel="noopener noreferrer"';
                
                $output .= sprintf(
                    '<a href="%s" class="lsss-share-button lsss-%s"%s%s>%s</a>',
                    esc_url($share_urls[$network]),
                    esc_attr($network),
                    $target,
                    $rel,
                    esc_html($network_labels[$network])
                );
            }
        }
        
        $output .= '</div>';
        
        return $output;
    }
}

// Initialize the plugin
new LightningSimpleSocialShare();