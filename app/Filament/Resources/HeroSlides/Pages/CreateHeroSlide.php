<?php

namespace App\Filament\Resources\HeroSlides\Pages;

use App\Filament\Resources\HeroSlides\HeroSlideResource;
use App\Models\HeroSlide;
use Filament\Resources\Pages\CreateRecord;

class CreateHeroSlide extends CreateRecord
{
    protected static string $resource = HeroSlideResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $sortOrder = (int) HeroSlide::query()->max('sort_order') + 1;

        return [
            ...$data,
            'title' => "Hero slide {$sortOrder}",
            'image_alt' => "Armour promotional banner {$sortOrder}",
            'show_content' => false,
            'content_position' => 'left',
            'sort_order' => $sortOrder,
            'is_published' => true,
        ];
    }
}
