<?php

namespace App\Filament\Resources\HeroSlides\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class HeroSlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('desktop_image_path')
                    ->label('Desktop image')
                    ->helperText('Recommended: 1920 × 900 px, WebP or JPG, up to 5 MB.')
                    ->disk('public')
                    ->directory('hero-slides')
                    ->visibility('public')
                    ->image()
                    ->required()
                    ->maxSize(5120),
                FileUpload::make('tablet_image_path')
                    ->label('Tablet image')
                    ->helperText('Recommended: 1200 × 1200 px or 4:3 ratio, WebP or JPG, up to 5 MB.')
                    ->disk('public')
                    ->directory('hero-slides')
                    ->visibility('public')
                    ->image()
                    ->maxSize(5120),
                FileUpload::make('mobile_image_path')
                    ->label('Mobile image')
                    ->helperText('Recommended: 900 × 1200 px, WebP or JPG, up to 5 MB.')
                    ->disk('public')
                    ->directory('hero-slides')
                    ->visibility('public')
                    ->image()
                    ->required()
                    ->maxSize(5120),
            ])
            ->columns(3);
    }
}
