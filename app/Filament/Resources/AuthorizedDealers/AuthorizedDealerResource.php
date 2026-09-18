<?php

namespace App\Filament\Resources\AuthorizedDealers;

use App\Filament\Resources\AuthorizedDealers\Pages\CreateAuthorizedDealer;
use App\Filament\Resources\AuthorizedDealers\Pages\EditAuthorizedDealer;
use App\Filament\Resources\AuthorizedDealers\Pages\ListAuthorizedDealers;
use App\Filament\Resources\AuthorizedDealers\Schemas\AuthorizedDealerForm;
use App\Filament\Resources\AuthorizedDealers\Tables\AuthorizedDealersTable;
use App\Models\AuthorizedDealer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AuthorizedDealerResource extends Resource
{
    protected static ?string $model = AuthorizedDealer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return AuthorizedDealerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuthorizedDealersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAuthorizedDealers::route('/'),
            'create' => CreateAuthorizedDealer::route('/create'),
            'edit' => EditAuthorizedDealer::route('/{record}/edit'),
        ];
    }
}
