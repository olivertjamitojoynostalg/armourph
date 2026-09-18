<?php

namespace App\Filament\Resources\AuthorizedDealers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AuthorizedDealerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(150),
                FileUpload::make('image_path')
                    ->label('Dealer picture or logo')
                    ->helperText('Recommended: square image, WebP, PNG, or JPG, up to 5 MB.')
                    ->disk('public')
                    ->directory('dealers')
                    ->visibility('public')
                    ->image()
                    ->maxSize(5120)
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->label('Display order')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                Toggle::make('is_published')
                    ->label('Published on website')
                    ->default(true),
            ]);
    }
}
