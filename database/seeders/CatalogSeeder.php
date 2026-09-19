<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\HeroSlide;
use App\Models\Package;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'kind' => 'package',
                'source_id' => 1,
                'name' => '9” Smart Drive — 2GB / 32GB',
                'slug' => 'package-1',
                'category' => 'Multimedia package',
                'description' => 'Bring multimedia control and a reversing view to your dashboard with this practical Android upgrade.',
                'specification' => '9-inch display · 2GB RAM · 32GB storage',
                'inclusions' => [
                    '9-inch display Android head unit',
                    'Reverse camera',
                    'Vehicle-fit panel',
                    'Wiring',
                ],
                'price' => '6999.00',
                'badge' => '#1 best seller',
                'sort_order' => 1,
                'is_published' => true,
                'image_path' => 'assets/samples/head-unit.webp',
                'image_alt' => 'Illustrative Android head unit and installation accessories',
                'is_sample_image' => true,
                'image_source' => 'https://www.mercadolibre.com.co/pantalla-carro-tactil-9--radio-bluetooth-usb-android-gps/up/MCOU3172821582',
            ],
            [
                'kind' => 'package',
                'source_id' => 3,
                'name' => '9” Road Command — 4GB / 64GB',
                'slug' => 'package-3',
                'category' => 'Multimedia package',
                'description' => 'A 9-inch Android setup with more memory and storage, paired with a reverse camera for everyday driving.',
                'specification' => '9-inch display · 4GB RAM · 64GB storage',
                'inclusions' => [
                    '9-inch display Android head unit',
                    'Reverse camera',
                    'Vehicle-fit panel',
                    'Wiring',
                ],
                'price' => '9999.00',
                'badge' => '#2 best seller',
                'sort_order' => 2,
                'is_published' => true,
                'image_path' => 'assets/samples/head-unit.webp',
                'image_alt' => 'Illustrative Android head unit and installation accessories',
                'is_sample_image' => true,
                'image_source' => 'https://www.mercadolibre.com.co/pantalla-carro-tactil-9--radio-bluetooth-usb-android-gps/up/MCOU3172821582',
            ],
            [
                'kind' => 'package',
                'source_id' => 8,
                'name' => '9” Armour 360 Voice — 4GB / 64GB',
                'slug' => 'package-8',
                'category' => 'Multimedia package',
                'description' => 'An Armour head unit with voice command and a 360 camera setup. Ask our team to confirm the right fit for your vehicle.',
                'specification' => '9-inch display · 4GB / 64GB · Voice command',
                'inclusions' => [
                    'Armour Android head unit with voice command',
                    '360 camera setup',
                    'Vehicle-fit panel',
                    'Wiring',
                ],
                'price' => '17500.00',
                'badge' => '#3 best seller',
                'sort_order' => 3,
                'is_published' => true,
                'image_path' => 'assets/samples/head-unit.webp',
                'image_alt' => 'Illustrative Android head unit and installation accessories',
                'is_sample_image' => true,
                'image_source' => 'https://www.mercadolibre.com.co/pantalla-carro-tactil-9--radio-bluetooth-usb-android-gps/up/MCOU3172821582',
            ],
            [
                'kind' => 'package',
                'source_id' => 4,
                'name' => '9” 360 Vision — 4GB / 64GB',
                'slug' => 'package-4',
                'category' => 'Multimedia package',
                'description' => 'Upgrade your dashboard with a 9-inch Android head unit and 360 camera setup for added awareness around your vehicle.',
                'specification' => '9-inch display · 4GB / 64GB · 360 camera',
                'inclusions' => [
                    '9-inch display Android head unit',
                    '360 camera setup',
                    'Vehicle-fit panel',
                    'Wiring',
                ],
                'price' => '14999.00',
                'badge' => '#4 best seller',
                'sort_order' => 4,
                'is_published' => true,
                'image_path' => 'assets/samples/head-unit.webp',
                'image_alt' => 'Illustrative Android head unit and installation accessories',
                'is_sample_image' => true,
                'image_source' => 'https://www.mercadolibre.com.co/pantalla-carro-tactil-9--radio-bluetooth-usb-android-gps/up/MCOU3172821582',
            ],
            [
                'kind' => 'package',
                'source_id' => 7,
                'name' => '10” Smart Drive — 2GB / 32GB',
                'slug' => 'package-7',
                'category' => 'Multimedia package',
                'description' => 'A larger 10-inch Android display with a camera, panel, and wiring selected to suit your installation.',
                'specification' => '10-inch display · 2GB RAM · 32GB storage',
                'inclusions' => [
                    '10-inch display Android head unit',
                    'Camera — confirm type with branch',
                    'Vehicle-fit panel',
                    'Wiring',
                ],
                'price' => '8500.00',
                'badge' => '#5 best seller',
                'sort_order' => 5,
                'is_published' => true,
                'image_path' => 'assets/samples/head-unit.webp',
                'image_alt' => 'Illustrative Android head unit and installation accessories',
                'is_sample_image' => true,
                'image_source' => 'https://www.mercadolibre.com.co/pantalla-carro-tactil-9--radio-bluetooth-usb-android-gps/up/MCOU3172821582',
            ],
            [
                'kind' => 'product',
                'source_id' => 278,
                'name' => 'Reverse Camera',
                'slug' => 'product-278',
                'category' => 'Camera',
                'description' => 'Add a rear view to a compatible display to help when parking and reversing. This catalog model is for viewing only.',
                'specification' => 'Rear-view camera · Viewing only',
                'inclusions' => [
                ],
                'price' => '1500.00',
                'badge' => '#1 best seller',
                'sort_order' => 1,
                'is_published' => true,
                'image_path' => null,
                'image_alt' => null,
                'is_sample_image' => false,
                'image_source' => null,
            ],
            [
                'kind' => 'product',
                'source_id' => 277,
                'name' => '360 Camera',
                'slug' => 'product-277',
                'category' => 'Camera',
                'description' => 'A surround-view camera upgrade for compatible head units. Confirm the camera set, calibration, and vehicle fit with your branch.',
                'specification' => '360 camera · Compatible head unit required',
                'inclusions' => [
                ],
                'price' => '6000.00',
                'badge' => '#2 best seller',
                'sort_order' => 2,
                'is_published' => true,
                'image_path' => null,
                'image_alt' => null,
                'is_sample_image' => false,
                'image_source' => null,
            ],
            [
                'kind' => 'product',
                'source_id' => 287,
                'name' => '9” IPS HC 8227 — 2GB / 32GB',
                'slug' => 'product-287',
                'category' => 'LCD',
                'description' => 'An IPS head unit with the HC 8227 platform, 2GB RAM, 32GB storage, and cooling fan. Ask the branch about phone compatibility.',
                'specification' => '9-inch IPS · HC 8227 · 2GB / 32GB · Fan',
                'inclusions' => [
                ],
                'price' => '6000.00',
                'badge' => '#3 best seller',
                'sort_order' => 3,
                'is_published' => true,
                'image_path' => null,
                'image_alt' => null,
                'is_sample_image' => false,
                'image_source' => null,
            ],
            [
                'kind' => 'product',
                'source_id' => 290,
                'name' => '9” IPS RK3326 — 4GB / 64GB',
                'slug' => 'product-290',
                'category' => 'LCD',
                'description' => 'A 9-inch IPS head unit with the RK3326 platform, 4GB RAM, 64GB storage, and cooling fan for your multimedia setup.',
                'specification' => '9-inch IPS · RK3326 · 4GB / 64GB · Fan',
                'inclusions' => [
                ],
                'price' => '8500.00',
                'badge' => '#4 best seller',
                'sort_order' => 4,
                'is_published' => true,
                'image_path' => null,
                'image_alt' => null,
                'is_sample_image' => false,
                'image_source' => null,
            ],
            [
                'kind' => 'product',
                'source_id' => 216,
                'name' => 'DVR Frontcam',
                'slug' => 'product-216',
                'category' => 'Dashcam',
                'description' => 'Record the road ahead with a front-facing DVR camera. Confirm recording specifications, storage requirements, and compatibility with your branch.',
                'specification' => 'Front-facing DVR camera',
                'inclusions' => [
                ],
                'price' => '1500.00',
                'badge' => '#5 best seller',
                'sort_order' => 5,
                'is_published' => true,
                'image_path' => null,
                'image_alt' => null,
                'is_sample_image' => false,
                'image_source' => null,
            ],
        ];
        foreach ($items as $item) {
            $item['is_featured'] = true;
            if ($item['kind'] === 'package') {
                Package::firstOrCreate(['source_id' => $item['source_id']], collect($item)->except('kind')->all());

                continue;
            }

            $productType = ProductType::firstOrCreate(['name' => $item['category']]);
            Product::firstOrCreate(['source_id' => $item['source_id']], [
                'product_type_id' => $productType->id,
                'name' => $item['name'],
                'slug' => $item['slug'],
                'description' => $item['description'],
                'specification' => $item['specification'],
                'price' => $item['price'],
                'badge' => $item['badge'],
                'sort_order' => $item['sort_order'],
                'is_published' => $item['is_published'],
                'is_featured' => $item['is_featured'],
                'image_path' => $item['image_path'],
                'image_alt' => $item['image_alt'],
                'is_sample_image' => $item['is_sample_image'],
                'image_source' => $item['image_source'],
            ]);
        }
        foreach (['Camera' => 'assets/samples/reverse-camera.jpg', 'LCD' => 'assets/samples/head-unit.webp', 'Dashcam' => 'assets/samples/dashcam.jpg'] as $name => $imagePath) {
            ProductType::where('name', $name)->whereNull('image_path')->update([
                'image_path' => $imagePath,
                'image_alt' => $name.' products',
            ]);
        }
        SiteSetting::firstOrCreate(['key' => 'hero'], ['value' => [
            'eyebrow' => 'Drive smarter with Armour.',
            'title' => 'Built to protect.',
            'accent' => 'Made to last.',
            'description' => 'Upgrade your car with multimedia systems, cameras, panels, and complete packages installed by people who know your vehicle.',
            'image_path' => 'assets/samples/mini-hero-desktop.png',
            'mobile_image_path' => 'assets/samples/mini-hero-mobile.png',
            'banner_image_path' => 'assets/samples/banner-desktop.png',
            'banner_mobile_image_path' => 'assets/samples/banner-mobile.png',
            'image_alt' => 'ARMOUR AR30 PRO 3-Channel 4K Dashcam',
            'image_source' => 'https://www.tiktok.com/@armourheadunitph/photo/7681969803413687570',
            'is_sample_image' => true,
        ]]);
        HeroSlide::query()->firstOrCreate(['sort_order' => 1], [
            'eyebrow' => 'Drive smarter. Drive protected.',
            'title' => 'Built for the road.',
            'accent' => 'Ready for more.',
            'description' => 'Upgrade your car with multimedia systems, cameras, panels, and complete packages installed by people who know your vehicle.',
            'desktop_image_path' => 'assets/generated/armour-hero-desktop-v1.jpg',
            'tablet_image_path' => null,
            'mobile_image_path' => 'assets/generated/armour-hero-mobile-v1.jpg',
            'image_alt' => 'Premium car interior with infotainment, parking camera display, and red accent lighting',
            'show_content' => false,
            'button_label' => 'Explore products',
            'button_url' => '/products',
            'content_position' => 'left',
            'is_published' => true,
        ]);
        foreach (config('armour.branches') as $index => $branch) {
            Branch::query()->firstOrCreate(['name' => $branch['name']], [
                ...collect($branch)->except('number')->all(),
                'sort_order' => $index + 1,
                'is_published' => true,
            ]);
        }
        SiteSetting::firstOrCreate(['key' => 'stores'], ['value' => config('armour.stores')]);
        SiteSetting::firstOrCreate(['key' => 'about'], ['value' => [
            'heading' => 'Technology made for the road ahead.',
            'body' => 'Armour helps Filipino drivers build smarter, safer, and more enjoyable vehicles through dependable car technology, practical accessories, and professional installation. We focus on recommending the right equipment, installing it with care, and supporting every customer through a trusted local network.',
            'reviews_heading' => 'What our customers say',
            'review_images' => [],
        ]]);
    }
}
