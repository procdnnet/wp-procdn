<?php
/**
 * Plugin Name: ProCDN
 * Text Domain: cdn
 * Description: Simply integrate a Content Delivery Network (CDN) into your WordPress site.
 * Author: ProCDN
 * Author URI: http://www.procdn.net
 * License: GPLv2 or later
 * Version: 1.0
 */

/*
 * Copyright (C) 2014-2015 ProCDN
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

/**
 * Check & Quit
 */
defined('ABSPATH') OR exit;

/**
 * constants
 */
define('PROCDN_FILE', __FILE__);
define('PROCDN_DIR', dirname(__FILE__));
define('PROCDN_BASE', plugin_basename(__FILE__));
define('PROCDN_MIN_WP', '3.8');

/**
 * loader
 */
add_action('plugins_loaded', array('PROCDN', 'instance'));

/**
 * uninstall
 */
register_uninstall_hook(__FILE__, array('PROCDN', 'handle_uninstall_hook'));

/**
 * activation
 */
register_activation_hook(__FILE__, array('PROCDN', 'handle_activation_hook'));

/**
 * autoload init
 */
spl_autoload_register('PROCDN_autoload');

/**
 * autoload function
 */
function PROCDN_autoload($class) {
    if (in_array($class, array('PROCDN', 'PROCDN_Rewriter', 'PROCDN_Settings'))) {
        require_once(
            sprintf(
                '%s/inc/%s.class.php',
                PROCDN_DIR,
                strtolower($class)
            )
        );
    }
}
