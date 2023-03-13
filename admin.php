<?php

/*
 * This file is part of DateProgress by valo.media.
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

Backoffice functionality for date-progress.

This file implements all the functionality available in the admin panel.

*/

/*
 * Settings
 */

/*
 * Configure the settings for the plugin.
 *
 * This will add all settings for date-progress, along with registering the necessary callbacks.  Currently, there is
 * only one setting, which is the license key.
 */
function date_progress_settings_init()
{
	register_setting('date_progress', 'date_progress_license');
	add_settings_section(
		'date_progress_settings_section',
		'DateProgress Settings',
		'date_progress_settings_section_callback',
		'date_progress'
	);
	add_settings_field(
		'date_progress_license_field',
		'DateProgress License',
		'date_progress_license_field_callback',
		'date_progress',
		'date_progress_settings_section'
	);
}

/*
 * Callback for generating the settings section.
 *
 * This will output the markup for the additional content (other than the heading and the actual settings) of the
 * settings section for date progress.  Currently, this is either a link to our shop, if the user hasn't yet entered
 * a license key, or information about whether the license key is valid otherwise.
 */
function date_progress_settings_section_callback()
{
	if (get_option( 'date_progress_license' )) {
		echo date_progress_check_license()
			? '<p>Your license key is valid! You are good to go :-)</p>'
			: '
				<p>
					Your license key seems to be invalid :-(
				</p>
				<p>
					Please <a href="https://valo.media/en-us/contact">contact us</a>, if you believe this is a mistake.
				</p>';
	} else {
		echo '
			<p>
				DateProgress is a paid plugin. You can buy a license 
				<a href="https://shop.valo.media/l/dateprogress">here</a>.
			</p>';
	}
}

/*
 * Callback for generating the license field.
 *
 * This will output the markup for the input field for the license key.
 */
function date_progress_license_field_callback()
{
	$setting = get_option('date_progress_license');
	$value = $setting ? esc_attr($setting) : '';
	echo "<input type=\"text\" name=\"date_progress_license\" value=\"{$value}\">";
}

/*
 * Callback for generating the shortcode generator.
 *
 * This will output the markup for the shortcode generator.
 */
function date_progress_shortcode_generator_callback()
{
	echo '
		<p>
			Use this form to generate your shortcode. For detailed instructions, see
			<a href="https://valo.media/en-us/dateprogress">here</a>.
		</p>
		<form id="date_progress_shortcode_generator">
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row">
							<label for="date_progress_label" class="form-label">Label</label>
						</th>
						<td>
							<input 
									type="text"
									name="date_progress_label"
									id="date_progress_label"
									placeholder="{$remaining} of {$total} days remaining">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="date_progress_format">Format</label>
						</th>	
						<td>
							<input type="text" name="date_progress_format" id="date_progress_format" placeholder="%a">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="date_progress_color">Color</label>
						</th>
						<td>
							<input type="color" name="date_progress_color" id="date_progress_color" value="#007bff">
						</td>
						<td>
							<input type="checkbox" name="date_progress_striped" id="date_progress_striped">
							<label for="date_progress_striped">Striped</label>
						</td>
						<td>
							<input type="checkbox" name="date_progress_animated" id="date_progress_animated">
							<label for="date_progress_animated">Animated</label>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="date_progress_start">Start date</label>
						</th>		
						<td>
							<input type="date" name="date_progress_start" id="date_progress_start">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<input 
									type="radio"
									id="date_progress_radio_end"
									name="date_progress_radio"
									value="end"
									checked>
							<label for="date_progress_radio_end">End date</label>
						</th>	
						<td>
							<input type="date" name="date_progress_end" id="date_progress_end">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<input
									type="radio"
									id="date_progress_radio_duration"
									name="date_progress_radio"
									value="duration">
							<label for="date_progress_radio_duration">Duration</label>
						</th>
						<td>
							<input 
									type="text"
									name="date_progress_duration"
									id="date_progress_duration"
									placeholder="1 week + 3 days">
						</td>
						<td>
							<input 
									type="checkbox"
									name="date_progress_repeating" 
									id="date_progress_repeating"
									value="date_progress_repeating">
							<label for="date_progress_repeating">Repeating</label>
						</td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<input
						type="submit"
						id="date_progress_generate_shortcode"
						class="button button-primary"
						value="Generate Shortcode">
			</p>
			<input type="text" name="date_progress_shortcode" id="date_progress_shortcode" placeholder="Shortcode">
		</form>';
}

/*
 * Callback for generating the options page.
 *
 * This function will generate the options page and add the necessary stylesheet and script for the shortcode
 * generator to work.
 */
function date_progress_options_page_html()
{
	wp_enqueue_style('date_progress_admin_style', plugins_url('style.css', __FILE__));
	wp_enqueue_script('date_progress_admin_script', plugins_url('script.js', __FILE__));
	if (isset($_POST['date_progress_license'])) {
		add_option('date_progress_license', $_POST['date_progress_license']);
	}
	echo '<div class="wrap">';
	echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';
	if (current_user_can('manage_options')) {
		/** @noinspection HtmlUnknownTarget */
		echo '<form action="tools.php?page=date_progress" method="post">';
		do_settings_sections( 'date_progress' );
		submit_button( 'Save Settings' );
		echo '</form>';
	}
	echo '<h2>Shortcode Generator</h2>';
	date_progress_shortcode_generator_callback();
	echo '</div>';
}

/*
 * Register the options page.
 *
 * This will register the actual options page for date-progress with WordPress.
 */
function date_progress_options_page()
{
	add_submenu_page(
		'tools.php',
		'DateProgress Options',
		'DateProgress',
		'manage_options',
		'date_progress',
		'date_progress_options_page_html'
	);
}

add_action('admin_init', 'date_progress_settings_init');
add_action('admin_menu', 'date_progress_options_page');

/*
 * Auto-Update
 */

/*
 * Fetch the plugin information for the current version.
 *
 * This will check for the current version of the plugin, as well as fetching some metadata for display in the admin
 * panel.  The results will be cached for one day.
 */
function date_progress_plugin_information() {
	global $DATE_PROGRESS_PLUGIN_INFORMATION_URL;
	global $DATE_PROGRESS_PLUGIN_INFORMATION_TRANSIENT;
	$transient = get_transient($DATE_PROGRESS_PLUGIN_INFORMATION_TRANSIENT);
	if ($transient) {
		$result = json_decode($transient);
	} else {
		$remote = wp_remote_get($DATE_PROGRESS_PLUGIN_INFORMATION_URL);
		if (is_wp_error($remote) || wp_remote_retrieve_response_code($remote) !== 200) { return false; }
		$body = wp_remote_retrieve_body($remote);
		if (empty($body)) { return false; }
		set_transient($DATE_PROGRESS_PLUGIN_INFORMATION_TRANSIENT, $body, DAY_IN_SECONDS);
		$result = json_decode($body);
	}
	return $result;
}

/*
 * Hook for the plugins API.
 *
 * This as a hook for the plugins API, that will fetch the plugin information about the current version from
 * cdn.valo.media instead of wordpress.org when querying for the plugin information for date-progress.
 */
function date_progress_plugins_api($result, $action, $args)
{
	// Only proceed if getting plugin information for this plugin.
	if ('plugin_information' !== $action || plugin_basename(__DIR__) !== $args->slug) { return $result; }

	// Check for updates.
	$plugin_information = date_progress_plugin_information();

	if ($plugin_information) {
		// Need to rebuild the object, since it needs to contain arrays where jsons_decode creates objects. Can't
		// make use of the spread operator, since this would break PHP 7.3 compatibility.
		return (object) array(
			'author' => $plugin_information->author,
			'banners' => array(
				'high' => $plugin_information->banners->high,
				'low' => $plugin_information->banners->low
			),
			'download_link' => $plugin_information->download_link,
			'name' => $plugin_information->name,
			'sections' => array(
				'description' => $plugin_information->sections->description,
				'installation' => $plugin_information->sections->installation,
				'screenshots' => $plugin_information->sections->screenshots
			),
			'slug' => $args->slug,
			'requires' => $plugin_information->requires,
			'requires_php' => $plugin_information->requires_php,
			'tested' => $plugin_information->tested,
			'trunk' => $plugin_information->download_link,
			'version' => $plugin_information->version,
		);
	} else {
		return  $result;
	}
}

/*
 * Hook for updating date-progress.
 *
 * This is a hook for the update system, that will use cdn.valo.media instead of wordpress.org when checking for or
 * performing updates for date-progress.
 */
function date_progress_update_plugins($transient) {
	global $PLUGIN_FILE;

	if (empty($transient->checked)) { return $transient; }

	$plugin_information = date_progress_plugin_information();

	if (
		$plugin_information
		&& version_compare($plugin_information->version, get_plugin_data($PLUGIN_FILE)['Version'], '>')
		&& version_compare($plugin_information->requires, get_bloginfo('version'), '<=')
		&& version_compare($plugin_information->requires_php, PHP_VERSION, '<')
	) {
		$transient->response[plugin_basename($PLUGIN_FILE)] = (object) array(
			'slug' => plugin_basename(__DIR__),
			'plugin' => plugin_basename($PLUGIN_FILE),
			'new_version' => $plugin_information->version,
			'tested' => $plugin_information->tested,
			'package' => $plugin_information->trunk
		);
	}
	return $transient;
}

/*
 * Hook for upgrade completion.
 *
 * This is a hook for the plugin upgrade system, that will clear the cache after the plugin gets upgraded.
 */
function date_progress_upgrader_process_complete($upgrader, $hook_extra)
{
	global $DATE_PROGRESS_PLUGIN_INFORMATION_TRANSIENT;
	if ($hook_extra['action'] === 'update' && $hook_extra['type'] === 'plugin') {
		delete_transient($DATE_PROGRESS_PLUGIN_INFORMATION_TRANSIENT);
	}
}

add_filter('plugins_api', 'date_progress_plugins_api', 20, 3);
add_filter('site_transient_update_plugins', 'date_progress_update_plugins');
add_action('upgrader_process_complete', 'date_progress_upgrader_process_complete', 10, 2);
