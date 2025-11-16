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

Shortcode Generator

This as a little UI-tool for generating the shortcodes for date-progress, so users don't have to write them by hand.
Currently, this only generates the shortcode, but does not check whether the options are actually reasonable.  This
means, that the user can generate a shortcode only to get an error when later adding it to a page, which is not great.
Ideally the shortcode wouldn't even be generated without a valid set of options, but this would require either
duplicating the logic for most of the plugin in JS, or changing the shortcode generator to run server-side.

 */

document.getElementById('date_progress_shortcode_generator').addEventListener('submit', (event) => {
    event.preventDefault();
    let radio = document.querySelector('input[name="date_progress_radio"]:checked').value
    let attributes = [
        ['label', document.getElementById('date_progress_label').value],
        ['format', document.getElementById('date_progress_format').value],
        ['color', document.getElementById('date_progress_color').value],
        ['striped', document.getElementById('date_progress_striped').checked],
        ['animated', document.getElementById('date_progress_animated').checked],
        ['start', document.getElementById('date_progress_start').value],
        [radio, document.getElementById('date_progress_' + radio).value],
        ['repeating', document.getElementById('date_progress_repeating').checked && radio === 'duration']
    ]
    document.getElementById('date_progress_shortcode').value
        = `[date_progress ${attributes.filter(([_, v]) => v).map(([k, v]) => `${k}="${v}"`).join(' ')}]`
});
