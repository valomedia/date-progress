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

//
//  plugin.php
//  date-progress
//
//  Created by:
//      * Jean-Pierre Höhmann
//

/*

Core implementation for date-progress.

This file contains most of the actual functionality.

*/

/*
 * Shortcode
 */

/*
 * Shortcode for date-progress.
 *
 * This implements all the actual functionality for the plugin.  Less than a hundred line.  Literally everything else is
 *  UI and bookkeeping. FML.
 */
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
		<div 
				class=\"progress position-relative d-block\" 
				role=\"progressbar\"
				aria-valuenow=\"{$percentage}\" 
				aria-valuemin=\"0\"
				aria-valuemax=\"100\">
			<div 
					class=\"{$class}\"
					style=\"{$style}\">
				{$left_text}
			</div>
			<small class=\"text-right w-100 px-1\">{$right_text}</small>
		</div>";
	$watermark = date_progress_check_license() ? '' : "<div>{$DATE_PROGRESS_WATERMARK}</div>";

	wp_enqueue_style('date_progress_bootstrap', plugins_url('bootstrap.min.css', __FILE__));
	return $progress_bar . $watermark;
}

add_shortcode('date_progress', 'date_progress_shortcode' );
