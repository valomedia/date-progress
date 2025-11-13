# date-progress

A WordPress plugin for creating time-based progress bars.

## Introduction

DateProgress is a plugin for WordPress that allows you to create time-based progress bars. Often when making a 
website, you want to give the user information about the time that is remaining until a particular thing happens. 
Whether you're a store with a limited time sale, or an author wanting to count down to a book launch, or a 
university wanting to tell applicants how much time is left to sign up: With DateProgress you can add some visual 
interest to the information, making it both stand out more. The progress bar also adds a visual component to the 
information, which makes it both easier to take in at a glance, and more intuitive, enhancing your call to action.

## Features

* Custom labels
* Custom date formats
* Custom colors
* Striped progress bars
* Progress bars with animated stripes
* Schedule a progress bar to appear in the future
* Progress bars automatically disappear after running out
* Repeating intervals can be specified
* Repetition can make use of complex calendar-based rules

## Installation

Prebuilt versions of this plugin can be found on the
[releases page](https://github.com/valomedia/date-progress/releases). After downloading it can be installed just 
like any other WordPress plugin.

## Development

If you want to develop this plugin, clone your fork of this repository inside the `/wp-content/plugins`-folder of a 
working WordPress installation.  Once you are happy with your changes, you can run `make`, to build a version of the 
plugin suitable for redistribution.  If you make any changes to the plugin that might be useful for other users, 
we'd appreciate a pull-request to this project.

## Quick Start Guide

DateProgress is built to be simple and intuitive to use. Here is a short guide to help you get started.

### How to Add a Progress Bar to Your Site

In order to add a progress bar to your site, you will need to create a shortcode. The shortcode tells date progress 
how exactly you want your progress bar to look.

### How to Generate a Shortcode

For those who aren't comfortable writing shortcodes by hand, DateProgress includes a handy tool for generating 
shortcodes with all the correct options. Once you have installed and enabled DateProgress, a new page called 
DateProgress will be available under Tools in your WordPress backend. There you can find a little form that allows 
you to specify all the options you need for your progress bar.

At the very minimum you will need to pick a start date for you progress bar (the date the bar counts down from), and 
an end date (the date the bar counts down to). All other options are optional. You can specify them if you like, but 
it is safe to ignore them, if you're not sure what they do.

This is all you need to know to create great progress bars with DateProgress. If you would like to dive deeper into 
the available options though, you can read on to the next section, which explains each option in detail.

## Reference Manual

The `date_progress` shortcode has various parameters, that allow you to customize your shortcode to your heart's 
content. The reference section explains each option in detail. Don't worry, if there is an option you don't 
understand, however. The only options you need to have, are `start`, and either `end` or `duration`. All other 
options will default to sensible values, if you do not provide anything.

### Start

This specifies the start date for the progress bar. This attribute must be specified. The format is `YYYY-MM-DD`.

The date given is the date on which the progress bar will be completely empty. You can specify a date which is in 
the future. In this case the progress bar will be scheduled to appear on that date.

### End

This specifies the end date for the progress bar. This attribute must be specified, if `duration` is not specified. 
This attribute must not be specified, if `duration` is specified. The format is `YYYY-MM-DD`.

The date given is the date on which the progress bar will be completely filled. If this date is in the past, the 
progress bar will automatically be hidden, so you do not have to worry about manually removing your progress bars, once 
they expire.

### Duration

This specifies the duration the progress bar will fill up in. This attribute must be specified, if `end` is not 
specified. This attribute must not be specified, if `end` is specified.

This attribute is quite smart and will support many complex duration specifications. This allows you to not only 
specify a fixed duration, but also to make the duration dependent on the calendar. This is intended for use with the 
`repeating` attribute in order to allow the creation of repeating progress bars with uneven lengths. For example, 
the duration may be specified as `1year`, allowing the progress bar to automatically adjust for the additional day 
on leap years.

Here is some examples of Strings you can specify for `duration`:

* `1 day`
* `2 weeks`
* `3 months`
* `4 years`
* `1 year + 1 day`
* `62 weeks + 1 day`
* `1 year -  10 days`

The duration must not be negative, the duration must not be relative (such as `last thursday`), the duration should 
be a number of full days (durations such as `1 day + 12 hours` may work to an extent, but are unsupported).

### Repeating

Whether to start the progress bar over after it has completed, instead of hiding it. Defaults to `no`. If you set 
this to `yes`, you must specify `duration`.

This can be used together with `duration`, in order to make a progress bar for something that repeats itself 
regularly. Once the progress bar has filled completely, a new progress bar will start, with the same duration, but 
starting where the last progress bar ended. This will repeat indefinitely.

### Label

The label to show on the progress bar. This attribute is optional. When this attribute is empty, or not provided, no 
label will be shown.

You can use this attribute to specify a text to show on the progress bar. You can use the placeholders `{$elapsed}`, 
`{$remaining}`, and `{$total}` to show the elapsed, remaining, and total number of days in the current progress bar, 
respectively. If you want these placeholders to show something other than the number of days, you can use the 
`format` attribute described below.

### Format

The format to use when expanding the placeholders in the `label`. This attribute is optional and will default to 
`%a` (the total number of days).

Setting this attribute may look a bit daunting at first, but it is actually really simple. Everything you put in
by a letter, which will be replaced by a number. For example `%a days` will output something like “32 days”. Whereas
here will be output as is, as part of the replacement in the `label`, except for a percent-sign immediately followed 
like `%m month, %d days` might format the same as something like “1 month, 2 days”, or “1 month, 4 days”, depending 
on the length of the month. Please refer to the following table for the format characters you can use.

| Format Character | Description                   |
|------------------|-------------------------------|
| %Y               | Years with leading 0          |
| %y               | Years                         |
| %M               | Months of year with leading 0 |
| %m               | Months of year                |
| %D               | Days of month with leading 0  |
| %d               | Days of month                 |
| %a               | Total number of days          |

### Color

The color for the progress bar. This can accept a color name, a hexadecimal color code, or values for RGB or HSL, 
optionally with an alpha component. See below for examples. If not specified, this will default to `#007bff`.

Examples for valid colors:

* `brown`
* `#74992e`
* `rgb(255, 255, 128)`
* `rgba(255, 255, 128, .5)`
* `hsl(50, 33%, 25%)`
* `hsla(50, 33%, 25%, .75)`

### Striped

Whether to stripe the progress bar. Defaults to `no`. If this is set to `yes`, a lighter and a darker version of the 
`color` will be generated and applied to the progress bar as stripes.

### Animated

Whether to animate the stripes on the progress bar. Defaults to `no`. If this is set to `yes`, the stripes on the 
progress bar will be animated. This does nothing unless `striped` is also set to `yes`.

## Reporting Bugs

If you think you have found a bug in DateProgress, you can contact us by the above means, or write us an issue on 
the [GitHub issue tracker](https://github.com/valomedia/date-progress/issues).

## License

DateProgress is free software: you can redistribute it and/or modify it under the terms of the [GNU General Public 
License](https://www.gnu.org/licenses/gpl-3.0.txt) as published by the Free Software Foundation, either version 3 of 
the License, or (at your option) any later version.

DateProgress is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied 
warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

## Source Code

The source code for this project can be found over on [GitHub](https://github.com/valomedia/date-progress)
