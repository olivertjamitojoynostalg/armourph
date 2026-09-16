<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            /*
            Action::make('sync')
                ->label('Sync from AdvanceAutoPH')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(function (): void {
                    try {
                        $sync = app(CatalogSyncController::class);
                        Notification::make()->success()->title('Catalog synchronized')->body($sync->statusMessage($sync->synchronizeFromSource()))->send();
                    } catch (Throwable $exception) {
                        report($exception);
                        Notification::make()->danger()->title('Sync failed')->body('AdvanceAutoPH could not be reached. Please try again.')->send();
                    }
                }),
            */
            CreateAction::make(),
        ];
    }
}
