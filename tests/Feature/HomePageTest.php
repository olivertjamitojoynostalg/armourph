<?php

namespace Tests\Feature;

use App\Models\HeroSlide;
use App\Models\Package;
use App\Models\Product;
use App\Models\ProductType;
use Database\Seeders\CatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_renders_seeded_catalog_and_assets(): void
    {
        $this->seed(CatalogSeeder::class);
        $this->get('/')->assertOk()->assertViewIs('home')->assertSee('9” Smart Drive')->assertSee('Reverse Camera')->assertDontSee('₱6,999')->assertSee('Sample image')->assertSee('Shopee ↗')->assertSee('Lazada ↗')->assertSee('TikTok ↗')->assertSee('Facebook ↗');
        foreach (Package::all() as $package) {
            $this->assertFileExists(public_path($package->image_path));
            $this->get(route('packages.show', $package))->assertOk()->assertSee($package->name);
        }
        foreach (Product::all() as $product) {
            $this->get(route('products.show', $product))->assertOk()->assertSee($product->name);
        }
    }

    public function test_home_shows_only_published_featured_items(): void
    {
        $this->seed(CatalogSeeder::class);
        Product::factory()->create(['name' => 'Hidden item', 'sort_order' => 0, 'is_published' => false]);
        Product::factory()->create(['name' => 'Not featured', 'sort_order' => 99, 'is_featured' => false]);
        Product::where('source_id', 278)->update(['name' => 'Camera <script>alert(1)</script>']);
        $this->get('/')->assertDontSee('Hidden item')->assertDontSee('Not featured')->assertSee('Camera &lt;script&gt;alert(1)&lt;/script&gt;', false)->assertSee('360 Camera')->assertSee('9” IPS HC 8227');
    }

    public function test_home_hero_carousel_shows_published_slides_in_order(): void
    {
        HeroSlide::factory()->create(['title' => 'Second promotion', 'sort_order' => 20, 'show_content' => true]);
        HeroSlide::factory()->create(['title' => 'First promotion', 'sort_order' => 10, 'show_content' => true]);
        HeroSlide::factory()->create(['title' => 'Hidden promotion', 'sort_order' => 5, 'is_published' => false]);

        $this->get('/')
            ->assertOk()
            ->assertSee('data-hero-carousel', false)
            ->assertSeeInOrder(['First promotion', 'Second promotion'])
            ->assertDontSee('Hidden promotion')
            ->assertSee('data-hero-progress', false)
            ->assertSee('data-hero-dot="0"', false)
            ->assertSee('data-hero-dot="1"', false)
            ->assertDontSee('data-hero-prev', false)
            ->assertDontSee('data-hero-next', false)
            ->assertSee('Explore our products');
    }

    public function test_home_hero_carousel_renders_tablet_image_source_when_present(): void
    {
        $slideWithTablet = HeroSlide::factory()->create([
            'desktop_image_path' => 'assets/samples/banner-desktop.png',
            'tablet_image_path' => 'assets/samples/banner-tablet.png',
            'mobile_image_path' => 'assets/samples/banner-mobile.png',
        ]);
        $slideWithoutTablet = HeroSlide::factory()->create([
            'desktop_image_path' => 'assets/samples/banner-desktop.png',
            'tablet_image_path' => null,
            'mobile_image_path' => 'assets/samples/banner-mobile.png',
        ]);

        $response = $this->get('/');
        $response->assertOk();

        $this->assertStringContainsString(
            '<source media="(max-width: 1024px)" srcset="'.$slideWithTablet->tablet_image_url.'">',
            $response->getContent()
        );
        $this->assertStringContainsString(
            '<source media="(max-width: 760px)" srcset="'.$slideWithTablet->mobile_image_url.'">',
            $response->getContent()
        );
    }

    public function test_home_limits_random_featured_products_and_packages(): void
    {
        $this->seed(CatalogSeeder::class);
        Product::factory()->count(10)->create(['is_published' => true, 'is_featured' => true]);
        Package::factory()->count(5)->create(['is_published' => true, 'is_featured' => true]);
        Product::factory()->create(['is_published' => false, 'is_featured' => true]);
        Package::factory()->create(['is_published' => false, 'is_featured' => true]);

        $this->get('/')
            ->assertOk()
            ->assertViewHas('products', fn ($products) => $products->count() === 8 && $products->every(fn (Product $product) => $product->is_published && $product->is_featured))
            ->assertViewHas('packages', fn ($packages) => $packages->count() === 6 && $packages->every(fn (Package $package) => $package->is_published && $package->is_featured));
    }

    public function test_home_prioritizes_featured_products_with_images(): void
    {
        Product::factory()->count(8)->create([
            'image_path' => 'storage/products/example.jpg',
            'is_published' => true,
            'is_featured' => true,
        ]);
        Product::factory()->count(8)->create([
            'image_path' => null,
            'is_published' => true,
            'is_featured' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertViewHas('products', fn ($products) => $products->count() === 8
                && $products->every(fn (Product $product) => $product->image_path !== null));
    }

    public function test_home_prioritizes_featured_packages_with_images(): void
    {
        Package::factory()->count(6)->create([
            'image_path' => 'storage/packages/example.jpg',
            'is_published' => true,
            'is_featured' => true,
        ]);
        Package::factory()->count(6)->create([
            'image_path' => null,
            'is_published' => true,
            'is_featured' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertViewHas('packages', fn ($packages) => $packages->count() === 6
                && $packages->every(fn (Package $package) => $package->image_path !== null));
    }

    public function test_home_balances_four_product_categories_on_desktop(): void
    {
        foreach (range(1, 4) as $number) {
            $productType = ProductType::query()->create(['name' => "Category {$number}"]);
            Product::factory()->create([
                'product_type_id' => $productType->id,
                'is_published' => true,
            ]);
        }

        $this->get('/')
            ->assertOk()
            ->assertSee('product-explorer-carousel--four-items', false);
    }

    public function test_old_index_url_is_not_available(): void
    {
        $this->get('/index.html')->assertNotFound();
    }

    public function test_product_catalog_filters_published_products_by_type(): void
    {
        $this->seed(CatalogSeeder::class);
        $camera = ProductType::where('name', 'Camera')->firstOrFail();
        Product::factory()->create(['name' => 'Unpublished camera', 'product_type_id' => $camera->id, 'is_published' => false]);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('Back to home')
            ->assertSee('href="'.route('home').'"', false)
            ->assertSee('All products')
            ->assertSee('Reverse Camera')
            ->assertDontSee('Unpublished camera');

        $this->get(route('products.index', ['type' => $camera]))
            ->assertOk()
            ->assertSee('Reverse Camera')
            ->assertDontSee('DVR Frontcam');

        $this->get('/')->assertSee(route('products.index'))->assertSee('Camera')->assertSee('2 products');
    }

    public function test_product_detail_displays_primary_and_gallery_images(): void
    {
        $product = Product::factory()->create([
            'image_path' => 'catalog/primary.jpg',
            'gallery_images' => ['catalog/side.jpg', 'catalog/detail.jpg', 'catalog/primary.jpg'],
            'image_alt' => 'Front view of product',
            'is_published' => true,
        ]);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('data-product-gallery', false)
            ->assertSee('data-gallery-main', false)
            ->assertSee('Front view of product')
            ->assertSee('/storage/catalog/primary.jpg', false)
            ->assertSee('/storage/catalog/side.jpg', false)
            ->assertSee('/storage/catalog/detail.jpg', false)
            ->assertSee('Show image 3 of 3')
            ->assertDontSee('Show image 4 of 4');
    }

    public function test_product_detail_links_back_to_the_product_catalog(): void
    {
        $product = Product::factory()->create(['is_published' => true]);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('href="'.route('products.index').'"', false)
            ->assertDontSee('href="'.route('home').'#products"', false);
    }

    public function test_product_catalog_supports_search_and_sorting(): void
    {
        Product::factory()->create(['name' => 'Zulu Catalog Marker', 'price' => 100]);
        Product::factory()->create(['name' => 'Alpha Catalog Marker', 'price' => 200]);
        Product::factory()->create(['name' => 'Hidden Catalog Marker', 'is_published' => false]);

        $this->get(route('products.index', ['search' => 'Catalog Marker', 'sort' => 'name']))
            ->assertOk()
            ->assertSeeInOrder(['Alpha Catalog Marker', 'Zulu Catalog Marker'])
            ->assertDontSee('Hidden Catalog Marker')
            ->assertSee('Search products')
            ->assertSee('Categories');
    }

    public function test_package_catalog_filters_searches_and_hides_unpublished_packages(): void
    {
        Package::factory()->create(['name' => 'Premium Audio Package', 'category' => 'Audio']);
        Package::factory()->create(['name' => 'Basic Audio Package', 'category' => 'Audio']);
        Package::factory()->create(['name' => 'Hidden Audio Package', 'category' => 'Audio', 'is_published' => false]);

        $this->get(route('packages.index', ['category' => 'Audio', 'search' => 'Premium']))
            ->assertOk()
            ->assertSee('Back to home')
            ->assertSee('href="'.route('home').'"', false)
            ->assertSee('Premium Audio Package')
            ->assertDontSee('Basic Audio Package')
            ->assertDontSee('Hidden Audio Package')
            ->assertSee('Search packages')
            ->assertSee('Categories');

        $this->get('/')->assertSee(route('packages.index'))->assertSee('See all packages');
    }

    public function test_catalogs_prioritize_items_with_images_when_recommended(): void
    {
        Product::factory()->count(18)->create(['image_path' => null, 'sort_order' => 1]);
        $productWithImage = Product::factory()->create(['image_path' => 'storage/products/example.jpg', 'sort_order' => 999]);
        Package::factory()->count(15)->create(['image_path' => null, 'sort_order' => 1]);
        $packageWithImage = Package::factory()->create(['image_path' => 'storage/packages/example.jpg', 'sort_order' => 999]);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertViewHas('products', fn ($products) => $products->first()->is($productWithImage));

        $this->get(route('packages.index'))
            ->assertOk()
            ->assertViewHas('packages', fn ($packages) => $packages->first()->is($packageWithImage));
    }
}
