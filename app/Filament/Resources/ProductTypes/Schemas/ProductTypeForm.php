<?php

namespace App\Filament\Resources\ProductTypes\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                FileUpload::make('image_path')
                    ->label('Category image')
                    ->disk('public')
                    ->directory('product-types')
                    ->visibility('public')
                    ->image()
                    ->maxSize(5120),
                TextInput::make('image_alt')
                    ->label('Image description')
                    ->maxLength(255),
            ]);
    }
}
