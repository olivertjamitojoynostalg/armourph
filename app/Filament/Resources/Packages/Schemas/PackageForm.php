<?php

namespace App\Filament\Resources\Packages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PackageForm
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
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('category')
                    ->maxLength(255),
                Textarea::make('description')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
                TextInput::make('specification')
                    ->maxLength(255),
                TagsInput::make('inclusions')
                    ->helperText('Press Enter after each package inclusion.')
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
                    ->label('Package image')
                    ->disk('public')
                    ->directory('catalog')
                    ->visibility('public')
                    ->image()
                    ->maxSize(5120),
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
