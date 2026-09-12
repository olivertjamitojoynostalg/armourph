<?php

namespace App\Filament\Resources\Branches\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BranchForm
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
                    ->maxLength(100),
                Textarea::make('address')
                    ->rows(3)
                    ->columnSpanFull(),
                TextInput::make('contact')
                    ->tel()
                    ->maxLength(100),
                TextInput::make('hours')
                    ->maxLength(150),
                TextInput::make('url')
                    ->label('Directions URL')
                    ->url()
                    ->maxLength(2000)
                    ->columnSpanFull(),
                TextInput::make('embed_url')
                    ->label('Google Maps embed URL')
                    ->helperText('In Google Maps, choose Share → Embed a map → Copy HTML, then paste only the URL inside src="…" here.')
                    ->url()
                    ->regex('/^https:\/\/(www\.)?google\.com\/maps\/embed(?:\/|\?)/i')
                    ->maxLength(5000)
                    ->columnSpanFull(),
                TextInput::make('latitude')
                    ->helperText('Optional. Used to suggest the nearest branch from a visitor’s browser location.')
                    ->numeric()
                    ->minValue(-90)
                    ->maxValue(90),
                TextInput::make('longitude')
                    ->helperText('Optional. Used together with latitude for nearest-branch suggestions.')
                    ->numeric()
                    ->minValue(-180)
                    ->maxValue(180),
                TextInput::make('sort_order')
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
