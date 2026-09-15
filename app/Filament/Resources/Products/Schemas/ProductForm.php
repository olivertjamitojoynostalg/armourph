<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('source_id')
                    ->label('AdvanceAutoPH ID')
                    ->disabled()
                    ->dehydrated(false)
                    ->numeric(),
                Select::make('product_type_id')
                    ->relationship('productType', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Textarea::make('description')
                    ->rows(4)
                    ->columnSpanFull(),
                Textarea::make('specification')
                    ->label('Specifications')
                    ->helperText('Enter one specification per line. Each line appears as a bullet on the product page.')
                    ->rows(6)
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->numeric()
                    ->prefix('₱')
                    ->minValue(0),
                TextInput::make('badge')
                    ->maxLength(40),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                Toggle::make('is_published')
                    ->label('Published on website')
                    ->default(false),
                Toggle::make('is_featured')
                    ->label('Featured on homepage')
                    ->default(false),
                FileUpload::make('image_path')
                    ->label('Product image')
                    ->disk('public')
                    ->directory('catalog')
                    ->visibility('public')
                    ->image()
                    ->maxSize(5120),
                FileUpload::make('gallery_images')
                    ->label('Additional product images')
                    ->helperText('Upload up to 8 additional views. Drag images to set their display order.')
                    ->disk('public')
                    ->directory('catalog')
                    ->visibility('public')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->appendFiles()
                    ->maxFiles(8)
                    ->maxSize(5120)
                    ->columnSpanFull(),
                TextInput::make('image_alt')
                    ->label('Image description')
                    ->maxLength(255),
                Toggle::make('is_sample_image')
                    ->label('Sample image'),
                TextInput::make('image_source')
                    ->label('Image source URL')
                    ->url()
                    ->maxLength(2000)
                    ->columnSpanFull(),
            ]);
    }
}
