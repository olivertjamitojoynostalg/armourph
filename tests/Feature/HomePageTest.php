<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_home_renders_the_blade_page(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertViewIs('home')
            ->assertSee('Armour | Upgrade Your Drive')
            ->assertSeeInOrder(['id="packages"', 'id="products"', 'id="branches"', 'id="stores"'], false);
    }

    public function test_home_renders_content_from_configuration(): void
    {
        config(['armour.packages.0.name' => 'Updated package <demo>']);

        $this->get('/')
            ->assertOk()
            ->assertSee('Updated package &lt;demo&gt;', false)
            ->assertDontSee('9” Smart Drive');
    }

    public function test_public_assets_match_the_original_preview(): void
    {
        foreach (['styles.css', 'script.js', 'assets/armour-logo.png'] as $asset) {
            $this->assertFileEquals(base_path('dist/'.$asset), public_path($asset));
        }
    }

    public function test_old_index_url_is_not_available(): void
    {
        $this->get('/index.html')->assertNotFound();
    }
}
