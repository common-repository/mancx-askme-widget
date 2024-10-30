<?php
    /*
        Plugin Name: Mancx AskMe widget
        Plugin URI: http://wordpress.org/extend/plugins/profile/mancx
        Description: The AskMe widget by Mancx is the ultimate monetizing tool for bloggers and experts.
        Version: 0.3
        Author: Mancx
        Author URI: http://wordpress.org/extend/plugins/profile/mancx
        License: GPL2
    */

    /*  Copyright 2011 mancx  (email : support@mancx.com)

        This program is free software; you can redistribute it and/or modify
        it under the terms of the GNU General Public License, version 2, as
        published by the Free Software Foundation.

        This program is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
        GNU General Public License for more details.

        You should have received a copy of the GNU General Public License
        along with this program; if not, write to the Free Software
        Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
     */

    global $options;
    $options = array(
        'mancx_email' => '',
        'mancx_token' => '',
        'mancx_username' => '',
        'mancx_widget_copy0' => '',
        'mancx_widget_copy1' => '',
        'mancx_widget_copy2' => '',
        'mancx_widget_copy3' => '',
        'mancx_widget_currency' => 'USD',
        'mancx_widget_size' => 'medium',
        'mancx_widget_host' => 'www.mancx.com',
        'mancx_widget_platform' => 'wp',
        'mancx_widget_theme' => '1',
        'mancx_widget_title' => '',
        'mancx_widget_version' => '0.3',
    );
    register_activation_hook(__FILE__, 'mancx_activate');
    function mancx_activate()
    {
        global $options;
        foreach($options as $option=>$value)
        {
            // This early in the widget story. Delete all the widget options (for simplicity)
            if(strpos($option, 'mancx_widget_') !== false)
            {
                delete_option($option);
            }

            // Add all the options that need adding.
            if(!get_option($options))
            {
                add_option($option, $value);
            }
        }
    }

    register_uninstall_hook(__FILE__, 'mancx_uninstall');
    function mancx_uninstall() {
        global $options;
        foreach($options as $option=>$value)
        {
            delete_option($option);
        }
    }

    add_action( 'admin_init', 'mancx_admin_init' );
    function mancx_admin_init() {
        /* Register our scripts */
        wp_register_script('mancxjquery', plugins_url().'/mancx-askme-widget/js/jquery-1.6.1.min.js');
        wp_enqueue_script('mancxjquery');

        wp_register_script('mancxblockui', plugins_url().'/mancx-askme-widget/js/jquery.blogckUI.js');
        wp_enqueue_script('mancxblockui');

        wp_register_script('mancxjs', plugins_url().'/mancx-askme-widget/js/mancx.js');
        wp_enqueue_script('mancxjs');

        wp_register_style('mancxcss', plugins_url().'/mancx-askme-widget/css/mancx.css');
        wp_enqueue_style('mancxcss');
    }

    add_action('admin_menu', 'mancx_menu');
    function mancx_menu() {
        add_menu_page(__('Mancx','mancx'), __('Mancx','mancx'), 'manage_options', 'mancx', 'mancx', plugins_url().'/mancx-askme-widget/img/mancx_icon_16x16.png');
    }

    // Get the main settings file
    require_once('settings.php');

    // Get the actual widget file
    require_once('widget.php');
?>
