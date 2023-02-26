<?php

/*
Plugin Name:    Date Progress
Plugin URI:     http://valo.media/en-us/dateprogress
Description:    A shortcode for time-based progress bars.
Version:        0.0.1
Author:         valo.media GmbH
Author URI:     http://valo.media
License:        GPL3
License URI:    https://www.gnu.org/licenses/gpl-3.0.html
*/

$DATE_PROGRESS_PRODUCT_ID = 'eQWGX2AwQtdGU_fQIEGsgA==';
$DATE_PROGRESS_WATERMARK = '
	<p>
		Made using <a href="https://valo.media/en-us/dateprogress">DateProgress by valo.media</a>.
	</p>';

function date_progress_license()
{
	global $DATE_PROGRESS_PRODUCT_ID;
	$license_key = get_option('date_progress_license');
	if (!$license_key) { return false; }

	$request = curl_init('https://api.gumroad.com/v2/licenses/verify');
	curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($request, CURLOPT_POST, true);
	curl_setopt(
		$request,
		CURLOPT_POSTFIELDS,
		http_build_query(
			array(
				'product_id' => $DATE_PROGRESS_PRODUCT_ID,
				'license_key' => $license_key,
				'increment_uses_count' => false
			)
		)
	);
	$license = json_decode(curl_exec($request), true);
	curl_close($request);
	return $license;
}

function date_progress_check_license() {
	return date_progress_license()["success"];
}

function date_progress_shortcode($atts)
{
	global $DATE_PROGRESS_WATERMARK;

	$a = shortcode_atts(
		array(
			'start' => '',
			'end' => '',
			'duration' => '',
			'label' => '',
			'format' => '%a',
			'repeating' => 'false',
			'striped' => 'false',
			'animated' => 'false',
			'color' => '#007bff',
		),
		$atts
	);
	$start = DateTimeImmutable::createFromFormat('Y-m-d', $a['start']);
	$end = DateTimeImmutable::createFromFormat('Y-m-d', $a['end']);
	$duration = DateInterval::createFromDateString($a['duration']);
	$label = $a['label'];
	$format = $a['format'];
	$repeating = filter_var($a['repeating'], FILTER_VALIDATE_BOOL);
	$striped = filter_var($a['striped'], FILTER_VALIDATE_BOOL);
	$animated = filter_var($a['animated'], FILTER_VALIDATE_BOOL);
	$color = $a['color'];

	if (!$start) {
		return 'ERROR: Can\'t display progress bar – please provide a start date, formatted as YYYY-MM-DD.';
	}
	if ($a['duration'] && !$duration) {
		return 'ERROR: Can\'t display progress bar – the provided duration was invalid.';
	}
	if (!$end && !$duration || $end && $duration) {
		return 'ERROR: Can\'t display progress bar – please provide either an end date or a duration (but not both).';
	}
	if ($repeating && !$duration) {
		return 'ERROR: Can\'t display progress bar – repeating progress bars work only with a specified duration.';
	}

	$now = new DateTime();
	if (!$end) {
		while (true) {
			$end = $start->add($duration);
			if ($end > $now || !$repeating || $end <= $start) { break; }
			$start = $end;
		}
	}
	if ($end <= $start) {
		if (!$duration) {
			return 'ERROR: Can\'t display progress bar – the end date is before the start date.';
		} else {
			return 'ERROR: Can\'t display progress bar – please provide a positive, non-relative duration.';
		}
	}
	if ($start > $now || $end < $now) { return ''; }

	$elapsed = $start->diff($now);
	$remaining = $now->diff($end);
	$total = $start->diff($end);
	$text = preg_replace(
		array(
			'/\{\$elapsed}/',
			'/\{\$remaining}/',
			'/\{\$total}/',
		),
		array(
			$elapsed->format($format),
			$remaining->format($format),
			$total->format($format)
		),
		$label
	);
	$class = "progress-bar" . ($striped ? " progress-bar-striped" : "") . ($animated ? " progress-bar-animated" : "");
	$percentage = 100 * $elapsed->days / $total->days;
	$style = "width: {$percentage}%; background-color: {$color};";

	$left_text = $percentage >= 50 ? $text : '';
	$right_text = $percentage < 50 ? $text : '';

	$progress_bar = "
		<div class=\"progress position-relative\">
			<div 
					class=\"{$class}\"
					role=\"progressbar\" 
					style=\"{$style}\"
					aria-valuenow=\"{$percentage}\" 
					aria-valuemin=\"0\"
					aria-valuemax=\"100\">
				{$left_text}
			</div>
			<small class=\"text-right w-100 px-1\">{$right_text}</small>
		</div>";
	$watermark = date_progress_check_license() ? '' : "<div>{$DATE_PROGRESS_WATERMARK}</div>";

	wp_enqueue_style('date_progress_bootstrap', plugins_url('bootstrap.min.css', __FILE__));
	return $progress_bar . $watermark;
}

add_shortcode('date_progress', 'date_progress_shortcode' );

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

add_action('admin_init', 'date_progress_settings_init');

function date_progress_settings_section_callback()
{
	echo date_progress_check_license()
		? '<p>Your license is valid! You are good to go :-)</p>'
		: '
			<p>
				DateProgress is a paid plugin. You can buy a license 
				<a href="https://shop.valo.media/l/dateprogress">here</a>.
			</p>';
}

function date_progress_license_field_callback()
{
	$setting = get_option('date_progress_license');
	$value = isset($setting) ? esc_attr($setting) : '';
	echo "<input type=\"text\" name=\"date_progress_license\" value=\"{$value}\">";
}

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

add_action('admin_menu', 'date_progress_options_page');
