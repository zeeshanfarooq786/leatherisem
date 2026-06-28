# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

WordPress MU-Plugin for Namecheap EasyWP hosting, built on the [WP Bones](https://wpbones.com) framework (~1.8). Provides caching integration (Redis, Varnish, OPcache), automatic updates, security monitoring (HackGuardian), and plugin compatibility checking.

## Common Commands

```bash
# Install dependencies
npm install
php bones install

# Development (watch mode for assets)
npm run start

# Production build
npm run build

# Version management
php bones version              # Interactive version prompt
php bones version --patch      # Auto-increment patch

# Update banned plugins list (after editing banned-plugins/*.json)
php bones banned:plugins

# Generate translations
npm run make-pot
npm run make-json

# Deploy/package
php bones deploy [path]
```

## Architecture

**Framework:** WP Bones — service-provider-based WordPress plugin framework. Docs at https://wpbones.com/docs.

**Namespace:** `WPNCEasyWP\` (PSR-4 autoloaded from `plugin/`)

**Entry flow:** `wpnceasywp.php` → `bootstrap/autoload.php` → `bootstrap/plugin.php` → WP Bones loads service providers from `config/plugin.php`

**Key directories:**
- `plugin/Providers/` — Service providers (one per feature module). Each is registered in `config/plugin.php` under `providers` array
- `plugin/Http/Controllers/` — MVC controllers for admin pages (Redis, Varnish, OPCache, Dashboard)
- `plugin/Traits/` — Shared behaviors: `LogTrait`, `AdminMenuableTrait`, `ThrottleTrait`
- `plugin/Functions/` — Global helper functions loaded via `functions.php`
- `config/` — All configuration: plugin settings, API, routes, menus, feature flags (`flags.yaml`), banned plugins
- `resources/assets/` — Source CSS (Sass/Less) and JS (TypeScript/React/TSX)
- `public/` — Compiled assets output (CSS, JS, React apps)

**Asset pipeline:** Gulp handles Sass/Less/TypeScript; `@wordpress/scripts` handles React apps. Both run in parallel via `npm run start`/`npm run build`.

## Coding Conventions

- Every PHP file must start with `if (!defined('ABSPATH')) { exit(); }` guard
- Service providers extend `ServiceProvider` and implement `register()` method
- Feature flags configured in `config/flags.yaml` (uses `wpbones/flags` package)
- Admin AJAX handlers registered in `config/plugin.php` under `ajax` array
- Use `$wpdb->prepare()` for ALL database queries — never interpolate variables into SQL
- Always escape output in views: `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`
- Prefer WordPress HTTP API (`wp_remote_post()`, `wp_remote_get()`) over raw cURL
- Always check `current_user_can()` alongside nonce verification for admin actions

## Known Technical Debt

Areas requiring attention when working nearby — do not fix proactively unless specifically asked:

- **SQL injection risk** in `EasyWPInternalModel.php` — uses string interpolation instead of `$wpdb->prepare()`
- **curl_error() after curl_close()** in `hack-guardian.php` and `monarx.php` — error reporting broken
- **Disabled SSL verification** in `WordPressVersionServiceProvider.php` and `WordPressLoginServiceProvider.php`
- **Missing output escaping** in `resources/views/varnish/index.php` and `blogname.php`
- **Information disclosure** in `resources/views/dashboard/index.php` — exposes JWT_TOKEN and env vars
- **Dead code**: commented-out blocks in `AutomaticUpdatesServiceProvider.php`, `EasyWPServiceProvider.php`, `HackGuardianServiceProvider.php`; unused filter in `blogname.php`; deprecated function in `hack-guardian.php`
- **Inconsistent patterns**: singleton via `boot()` vs `init()` across providers; mixed config access (`WPNCEasyWP()->config()` vs `wpbones_flags()` vs `WPNCEasyWP()->options`); inconsistent logging (`error_log()` vs `logger()`)
- **ThrottleTrait.php**: uses `$_SERVER['REMOTE_ADDR']` without proxy awareness; type mixing string/int
- **No JWT signature/expiration verification** in `plugin/Support/JWT.php`
- **Hardcoded cURL timeout 0** (infinite) in several function files

## Deployment

- Git tags must follow semver (e.g., `v2.1.6`, `v2.1.7-rc.1`)
- RC tags auto-deploy to Testing/Staging clusters
- Stable tags auto-deploy to Testing/Staging; Production requires manual PR in [NCCloud/infra-apps](https://github.com/NCCloud/infra-apps/) repository
- Production deploy: ask SRE team on Slack #cloud-sre to create PR updating `pluginVersion`

## Local Development

Requires a WordPress installation. Laravel Valet recommended for macOS. The plugin lives in `wp-content/mu-plugins/wp-nc-easywp/` and needs a loader file at `wp-content/mu-plugins/wp-nc-easywp.php`:

```php
<?php
require WPMU_PLUGIN_DIR . "/wp-nc-easywp/wpnceasywp.php";
```

Hidden debug page available at `/wp-admin/?page=easywp`.
