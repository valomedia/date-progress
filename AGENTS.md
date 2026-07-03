# AGENTS.md – DateProgress

## Project

- DateProgress is a WordPress plugin
  that renders time-based progress bars through the `date_progress` shortcode.
- The codebase is small and procedural:
  PHP files provide the plugin entry point, shortcode rendering, admin UI, shared helpers, and custom update hooks.
- There is no package manager configuration or automated test suite in the repository.

## Commands

- `php -l admin.php && php -l date-progress.php && php -l lib.php && php -l plugin.php` - run PHP syntax checks for all plugin PHP files.
- `make` - build `date-progress.zip` for distribution.
  This downloads `bootstrap.min.css` from jsDelivr when it is missing.
- `make clean` - remove generated build artifacts.
  It deletes `date-progress.zip` and `bootstrap.min.css`.

## Repository layout

- `date-progress.php` - WordPress plugin header, global constants, and file imports.
- `plugin.php` - front-end shortcode implementation for `date_progress`.
- `admin.php` - WordPress admin settings page, shortcode generator page, and plugin update hooks.
- `lib.php` - shared helper functions used by the plugin and admin code.
- `script.js` - vanilla JavaScript for the admin shortcode generator.
- `style.css` - admin shortcode generator styles.
- `makefile` - release packaging rule for the WordPress plugin zip.
- `README.md` - user-facing plugin documentation and shortcode reference.

## Working rules

- Keep the plugin compatible with the PHP version constraints implied by the existing code.
  For example, `admin.php` avoids the spread operator because it needs PHP 7.3 compatibility.
- Treat `bootstrap.min.css` and `date-progress.zip` as generated artifacts.
  Do not hand-edit or commit them.
- Do not add secrets, license keys, or environment-specific configuration to the repository.
- Be careful around licensing, attribution, and custom update logic.
  They affect externally visible product behavior and should only change when the issue explicitly asks for it.
- Follow the existing procedural WordPress style:
  global plugin constants live in `date-progress.php`, hooks are registered near the code they wire up,
  and functions use the `date_progress_` prefix.

## Verification

- Run the PHP syntax check command before finishing PHP changes.
- For UI or shortcode behavior changes, verify in a local WordPress installation when available.
  If WordPress is not available, state that limitation and include the PHP syntax check results.
- Do not run `make` merely to verify unrelated code changes unless release packaging is part of the task,
  because it may perform a network download for Bootstrap.
