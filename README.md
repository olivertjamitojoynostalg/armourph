# Armour website — Laravel

The existing Armour page now runs through Laravel 13. The public catalog and maintenance area use SQLite. The existing black-and-red visual style is preserved.

## Run locally

Requirements: PHP 8.4+ (for the locked dependencies) and Composer.

```sh
composer run setup
composer run dev
```

Open http://127.0.0.1:8000. On this checkout, dependencies and the local application key have already been installed.

Laravel Herd can also serve the project by linking this directory. For any PHP web server, set the document root to `public/`.

No Node or Vite build is required. Setup runs migrations, seeds the initial catalog, and links image storage. Sessions and cache use files; queues run synchronously. `.env` contains local settings and must not be committed.

## Editing the site

- `routes/web.php` — homepage route
- `app/Http/Controllers/HomeController.php` — loads published content from SQLite
- `database/seeders/CatalogSeeder.php` — initial top-five packages and products; ranking from the supplied screenshots, prices from the supplied catalog dump
- `config/armour.php` — initial branch and store defaults only
- `app/Models/CatalogItem.php` — package/product records
- `app/Models/SiteSetting.php` — editable hero, branch details, and store links
- `resources/views/admin/` — password-protected maintenance forms
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

The old `index.html`, its redirect route, and static Sites hosting configuration have been removed. The remaining assets in `dist/` are legacy copies; the application serves assets from `public/`.

The Laravel application must be deployed on PHP-capable hosting with `public/` as its document root. Static Sites hosting cannot execute Laravel. Use `APP_ENV=production`, `APP_DEBUG=false`, and the correct `APP_URL` when deploying; ensure `storage/` and `bootstrap/cache/` are writable.

## Verification

```sh
composer test
```

The tests cover public catalog rendering, publish/order limits, escaped content, admin authorization, login throttling, password changes, CRUD, uploads, validation, settings, and repeatable seeds.

## Maintenance

Open https://armour-ph-html.test/admin and sign in with the separately supplied administrator credentials. There is no public registration. Change your temporary password under Website settings.

On a fresh installation, create an administrator with:

```sh
php artisan armour:admin your-email@example.com
```

This generates a random password and refuses to overwrite an existing account.

Manage package/product names, descriptions, specs, inclusions, PHP prices, badges, order, publication status, and uploaded images. The homepage shows the first five published entries of each type, sorted by display order. Other published items remain accessible at their detail URLs. Website settings edit hero copy/photo, existing branch slots and directions links, and store links.

The catalog is an initial snapshot, not a live sales ranking or inventory integration. Descriptions were expanded from the available names/specifications; confirm compatibility and installation details with the business. Only the selected catalog information was imported, not users, customers, costs, sales, or other internal records. Seeders preserve existing edits; deleted seed records are restored if you explicitly reseed.

Web-sourced images are temporary illustrations, labeled as samples; their source links are stored with the records. Upload official photos and update the source/sample fields when available.

Back up `database/database.sqlite` and `storage/app/public/` together. Both runtime data and admin passwords stay out of Git. Static hosting cannot run this application.
