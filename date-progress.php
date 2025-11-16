<?php

/*
 * Copyright (c) 2025.
 * valo.media GmbH
 * All rights reserved.
 *
 * DateProgress is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later
 * version.
 *
 * DateProgress is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with DateProgress. If not, see
 * <https://www.gnu.org/licenses/>.
 */

/*

Plugin Name:    DateProgress
Plugin URI:     http://valo.media/en-us/dateprogress
Description:    A shortcode for time-based progress bars.
Version:        0.2.3
Author:         valo.media GmbH
Author URI:     http://valo.media
License:        GPL3
License URI:    https://www.gnu.org/licenses/gpl-3.0.html

*/

/*
 * Global constants
 */

$PLUGIN_FILE = __FILE__;
$DATE_PROGRESS_PRODUCT_ID = 'eQWGX2AwQtdGU_fQIEGsgA==';
$DATE_PROGRESS_WATERMARK = '
	<p>
		Made using <a href="https://valo.media/en-us/dateprogress">DateProgress by valo.media</a>.
	</p>';
$DATE_PROGRESS_PLUGIN_INFORMATION_URL = 'https://cdn.valo.media/date-progress/current/plugin-information.json';
$DATE_PROGRESS_PLUGIN_INFORMATION_TRANSIENT = 'date-progress-plugin-information';
$DATE_PROGRESS_PLUGIN_LICENSE_URL = 'https://api.gumroad.com/v2/licenses/verify';
$DATE_PROGRESS_PLUGIN_LICENSE_TRANSIENT = 'date-progress-license';

/*
 * Imports
 */

require_once(plugin_dir_path(__FILE__) . 'lib.php');
require_once(plugin_dir_path(__FILE__) . 'plugin.php');

if (is_admin()) {
	if (!function_exists('get_plugin_data')) {
		require_once(ABSPATH . 'wp-admin/includes/plugin.php');
	}
	require_once( plugin_dir_path( __FILE__ ) . 'admin.php' );
}
