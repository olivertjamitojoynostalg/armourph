<?php

namespace Tests\Feature;

use App\Filament\Pages\AboutPageSettings;
use App\Filament\Pages\WebsiteSettings;
use App\Filament\Resources\AuthorizedDealers\AuthorizedDealerResource;
use App\Filament\Resources\Branches\BranchResource;
use App\Filament\Resources\Branches\Pages\ListBranches;
use App\Filament\Resources\HeroSlides\HeroSlideResource;
use App\Filament\Resources\HeroSlides\Pages\ListHeroSlides;
use App\Filament\Resources\Packages\PackageResource;
use App\Filament\Resources\Packages\Pages\ListPackages;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\ProductTypes\Pages\ListProductTypes;
use App\Filament\Resources\ProductTypes\ProductTypeResource;
use App\Models\Branch;
use App\Models\HeroSlide;
use App\Models\Package;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\SiteSetting;
use App\Models\User;
use Database\Seeders\CatalogSeeder;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MaintenanceTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_catalog_seed_is_repeatable_and_preserves_edits(): void
    {
        $this->seed(CatalogSeeder::class);
        Package::query()->where('source_id', 1)->update(['name' => 'Edited']);
        $this->seed(CatalogSeeder::class);

        $this->assertDatabaseCount('packages', 5);
        $this->assertDatabaseCount('products', 5);
        $this->assertDatabaseCount('branches', 3);
        $this->assertDatabaseCount('hero_slides', 1);
        $this->assertDatabaseCount('site_settings', 3);
        $this->assertDatabaseHas('packages', ['source_id' => 1, 'name' => 'Edited', 'price' => 6999]);
    }

    public function test_only_administrators_can_access_the_filament_panel(): void
    {
        $this->get(ProductResource::getUrl())->assertRedirect('/admin/login');

        $this->actingAs(User::factory()->create(['is_admin' => false]));
        $this->get(ProductResource::getUrl())->assertForbidden();

        $this->actingAs($this->admin());
        $this->get(ProductResource::getUrl())->assertOk();
    }

    public function test_filament_resource_pages_render(): void
    {
        $this->get('/admin/login')->assertOk();
        $this->seed(CatalogSeeder::class);
        $this->actingAs($this->admin());

        foreach ([
            ProductResource::getUrl(),
            ProductResource::getUrl('create'),
            ProductResource::getUrl('edit', ['record' => Product::query()->firstOrFail()]),
            PackageResource::getUrl(),
            PackageResource::getUrl('create'),
            ProductTypeResource::getUrl(),
            ProductTypeResource::getUrl('create'),
            BranchResource::getUrl(),
            BranchResource::getUrl('create'),
            AuthorizedDealerResource::getUrl(),
            AuthorizedDealerResource::getUrl('create'),
            HeroSlideResource::getUrl(),
            HeroSlideResource::getUrl('create'),
            HeroSlideResource::getUrl('edit', ['record' => HeroSlide::query()->firstOrFail()]),
            AboutPageSettings::getUrl(),
            WebsiteSettings::getUrl(),
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_filament_tables_show_catalog_and_branch_records(): void
    {
        $this->seed(CatalogSeeder::class);
        $this->actingAs($this->admin());

        Livewire::test(ListProducts::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords(Product::query()->get())
            ->assertTableColumnExists('is_published', fn (ToggleColumn $column): bool => $column->getLabel() === 'Published')
            ->assertTableColumnExists('is_featured');

        Livewire::test(ListPackages::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords(Package::query()->get())
            ->assertTableColumnExists('is_published')
            ->assertTableColumnExists('is_featured');

        Livewire::test(ListProductTypes::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords(ProductType::query()->get());

        Livewire::test(ListBranches::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords(Branch::query()->get())
            ->assertTableColumnExists('is_published');

        Livewire::test(ListHeroSlides::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords(HeroSlide::query()->get())
            ->assertTableColumnExists('desktop_image_url')
            ->assertTableColumnExists('tablet_image_url')
            ->assertTableColumnExists('mobile_image_url')
            ->assertTableColumnExists('is_published');
    }

    public function test_published_branches_appear_on_the_website(): void
    {
        Branch::factory()->create(['name' => 'Visible branch', 'is_published' => true]);
        Branch::factory()->create(['name' => 'Hidden branch', 'is_published' => false]);

        $this->get('/')->assertOk()->assertSee('Visible branch')->assertDontSee('Hidden branch');
    }

    public function test_branch_contact_hours_and_map_actions_render_separately(): void
    {
        Branch::factory()->create([
            'name' => 'Armour Test Branch',
            'contact' => '0912 345 6789',
            'hours' => '9:00 AM to 6:00 PM',
            'url' => 'https://maps.google.com/?q=Armour+Test+Branch',
            'embed_url' => 'https://www.google.com/maps/embed?pb=test-map',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('class="branch-meta"', false)
            ->assertSee('<small><span>Contact</span>0912 345 6789</small>', false)
            ->assertSee('<small><span>Hours</span>9:00 AM to 6:00 PM</small>', false)
            ->assertSee('src="https://www.google.com/maps/embed?pb=test-map"', false)
            ->assertSee('data-branch-map', false)
            ->assertSee('Show on map')
            ->assertSee('Get directions');
    }

    public function test_website_settings_can_be_saved_in_filament(): void
    {
        $this->seed(CatalogSeeder::class);
        $this->actingAs($this->admin());

        Livewire::test(WebsiteSettings::class)
            ->fillForm(['title' => 'Updated homepage title'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('Updated homepage title', SiteSetting::content('hero')['title']);
    }
}
