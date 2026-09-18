<?php

namespace App\Filament\Resources\AuthorizedDealers\Pages;

use App\Filament\Resources\AuthorizedDealers\AuthorizedDealerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAuthorizedDealer extends EditRecord
{
    protected static string $resource = AuthorizedDealerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
