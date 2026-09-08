# Armour website — Laravel

The existing Armour page now runs through Laravel 13. Content remains static, and the original HTML, CSS, JavaScript, images, and interactions are preserved. No CMS or database is needed for this phase.

## Run locally

Requirements: PHP 8.4+ (for the locked dependencies) and Composer.

```sh
composer run setup
composer run dev
```

Open http://127.0.0.1:8000. On this checkout, dependencies and the local application key have already been installed.

Laravel Herd can also serve the project by linking this directory. For any PHP web server, set the document root to `public/`.

No Node, Vite build, or database migrations are required. Sessions and cache use files; queues run synchronously. `.env` contains local settings and must not be committed.

## Editing the site

- `routes/web.php` — homepage route
- `app/Http/Controllers/HomeController.php` — supplies static content to the homepage
- `config/armour.php` — editable package, product, branch, and store data
- `resources/views/layouts/app.blade.php` — shared page layout and assets
- `resources/views/partials/` — shared header and footer
- `resources/views/home.blade.php` — composes the homepage sections
- `resources/views/home/` — individual homepage sections
- `resources/views/components/` — reusable package, product, and branch cards
- `public/styles.css` — existing responsive styling
- `public/script.js` — existing navigation, animations, and placeholder branch actions
- `public/assets/` — original images
- `app/`, `config/`, `database/` — standard Laravel foundation for the future CMS

Search the Blade views and `config/armour.php` for `placeholder`, `sample`, or `to be added` to locate content awaiting official business details.

## Hosting

The old `index.html`, its redirect route, and static Sites hosting configuration have been removed. The remaining assets in `dist/` are retained only as migration test references; the application serves assets from `public/`.

The Laravel application must be deployed on PHP-capable hosting with `public/` as its document root. Static Sites hosting cannot execute Laravel. Use `APP_ENV=production`, `APP_DEBUG=false`, and the correct `APP_URL` when deploying; ensure `storage/` and `bootstrap/cache/` are writable.

## Verification

```sh
composer test
```

The feature tests verify that the Blade homepage renders, configured content is escaped correctly, public assets match the migration references, and the retired `/index.html` URL returns 404.
