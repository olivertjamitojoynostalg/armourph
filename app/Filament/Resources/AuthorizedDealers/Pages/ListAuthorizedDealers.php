<?php

namespace App\Filament\Resources\AuthorizedDealers\Pages;

use App\Filament\Resources\AuthorizedDealers\AuthorizedDealerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAuthorizedDealers extends ListRecords
{
    protected static string $resource = AuthorizedDealerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
