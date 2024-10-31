<?php
/*
  Plugin Name: Orbisius Theme Fixer
  Plugin URI: http://club.orbisius.com/products/wordpress-plugins/orbisius-theme-fixer/
  Description: This plugin overrides current theme's settings (no permanent changes to database). Your site will use one of the default WordPress themes until this plugin is deactivated/removed. It is part of <strong>Swiss Army Knife for WordPress (<a href='http://sak4wp.com' target='_blank'>SAK4WP</a>)</strong> tool. The plugin doesn't have settings page.
  Version: 1.0.0
  Author: Orbisius.com
  Author URI: http://orbisius.com
 */

$sak4wp_theme_troubleshooter_obj = new SAK4WP_Theme_Troubleshooter();

if ($sak4wp_theme_troubleshooter_obj->is_enabled()) {
    add_filter('stylesheet', array($sak4wp_theme_troubleshooter_obj, 'get_stylesheet'));
    add_filter('template', array($sak4wp_theme_troubleshooter_obj, 'get_template'));
	
	add_action('admin_notices', array($sak4wp_theme_troubleshooter_obj, 'admin_notice_message'));
}

/**
 * @package SAK4WP
 * @site http://sakwp.com
 */
class SAK4WP_Theme_Troubleshooter {
    /**
     * We'll override theme files only if !sak4wp.php file is in the root location.
     * @return bool if the SAK4WP is there this plugin will be enabled.
     */
    public function is_enabled() {
        $enabled = true; // always enabled. This check makes sense when this addon is used with SAK4WP
        //$enabled = file_exists(ABSPATH . '!sak4wp.php');
        return $enabled;
    }
	
	/**
     * We'll override theme files only if !sak4wp.php file is in the root location.
     * @return bool if the SAK4WP is there this plugin will be enabled.
     */
    public function admin_notice_message() {
        echo "<div class='updated'><p>Orbisius Theme Fixer is running. 
		Your site will use be using one of the default WordPress themes until this plugin is deactivated/removed.
		<br/>The plugin will try to find a good default theme starting from 2013...2010.
		This plugin is a potential solution when a site becomes broken due to a child theme (mis)configuration. <br/>
		Usage: Go to the Appearance > Themes and activate a theme that you know that works with your site.
		Then deactivate this plugin and your site should be using the newly selected theme.
		</p></div>";
    }

    /**
     * Loops and checks if one of the default themes is there and sets it.
     * This can be used for troubleshooting.
     *
     * @return mixed false/object
     */
    public function get_working_theme() {
        $default_themes = array('twentythirteen', 'twentytwelve', 'twentyeleven', 'twentyten');

        foreach ($default_themes as $theme_dir) {
            $theme = wp_get_theme($theme_dir);

            if ($theme->exists()) {
                return $theme;
            }
        }

        return false;
    }

    /**
     * Returns the directory of the theme e.g. twentytwelve
     * @param string $stylesheet
     * @return string
     */
    public function get_stylesheet($stylesheet = '') {
        $theme = $this->get_working_theme();
        $stylesheet = empty($theme) ? $stylesheet : $theme['Stylesheet'];

        return $stylesheet;
    }

    /**
     * Returns the directory of the theme e.g. twentytwelve, not sure why
     * @param type $template
     * @return type
     */
    public function get_template($template) {
        $theme = $this->get_working_theme();
        $template = empty($theme) ? $template : $theme['Template'];

        return $template;
    }
}
