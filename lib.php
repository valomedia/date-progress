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

Library for date-progress.

This file contains all functions used in multiple other files.

*/

/*
 * Licensing
 */

/*
 * Get the license.
 *
 * This will query Gumroad for the license. The return value is an object decoded from the JSON-response
 *
 * FIXME: This should be cached.
 */
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
	$license = json_decode(curl_exec($request));
	curl_close($request);
	return $license;
}

/*
 * Check the license.
 *
 * This will check whether the user has a valid license.
 */
function date_progress_check_license() {
	$license = date_progress_license();
	return $license
	       && $license->success
	       && $license->purchase
	       && !$license->purchase->refunded
	       && !$license->purchase->chargebacked;
}
