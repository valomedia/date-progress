# AGENTS.md – DateProgress

## Project Overview

- DateProgress is a WordPress plugin for rendering time-based progress bars through the `date_progress` shortcode.
- The plugin is procedural PHP with a small vanilla JavaScript shortcode generator for the WordPress admin page.
- Public usage and shortcode options are documented in `README.md`.
  Keep README examples aligned with shortcode behavior when changing user-visible options.

## Commands

- Run PHP syntax checks with `php -l <file>` for touched PHP files.
- Run `git diff --check` before handoff to catch whitespace errors.
- Run `make` only when you need to build a redistributable plugin zip.
  It downloads `bootstrap.min.css` and creates `date-progress.zip`.
- Run `make clean` to remove the generated zip and downloaded Bootstrap file.
- There are no Composer, npm, lint, or automated test scripts in this repository right now.
  Use syntax checks plus direct inspection as the minimum verification gate.

## Repository Layout

- `date-progress.php` is the WordPress plugin entry point.
  It defines plugin metadata, global constants, imports shared code, and conditionally loads admin code.
- `plugin.php` contains the front-end shortcode implementation and progress-bar rendering.
- `admin.php` contains settings-page registration, shortcode-generator markup, license handling, and custom plugin-update hooks.
- `lib.php` contains shared license-checking helpers.
- `script.js` powers the admin shortcode generator.
- `style.css` contains admin shortcode-generator styles.
- `makefile` packages the plugin for redistribution.
- `bootstrap.min.css` and `date-progress.zip` are generated artifacts and are ignored by git.

## Implementation Notes

- Keep PHP compatible with WordPress plugin conventions and the existing procedural style.
  Prefix new functions with `date_progress_`.
- Preserve existing copyright and GPL headers in source files.
- Match surrounding formatting in each file.
  Existing PHP mostly uses tabs for indentation and simple WordPress hook callbacks.
- Avoid modern PHP features that would break the current compatibility assumptions.
  `admin.php` explicitly avoids spread syntax for PHP 7.3 compatibility.
- Use WordPress APIs for sanitizing, escaping, options, transients, remote HTTP calls, scripts, styles, hooks, and plugin metadata.
- Be careful with shortcode output.
  Escape or sanitize user-controlled values where practical, especially labels, colors, dates, and generated attributes.
- Keep shortcode attributes backward-compatible unless the issue explicitly requests a breaking change.
- Keep admin JavaScript dependency-free.
  The current shortcode generator uses plain DOM APIs and should stay small.

## Safety and Privacy

- Do not commit license keys, product secrets, API tokens, or customer-specific settings.
- Treat license verification and update URLs as integration points.
  Do not change Gumroad or `cdn.valo.media` behavior without explicit product direction.
- Do not hand-edit generated artifacts.
  Rebuild them with `make` when a release artifact is actually needed.
