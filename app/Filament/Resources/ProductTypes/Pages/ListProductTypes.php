<?php

namespace App\Filament\Resources\ProductTypes\Pages;

use App\Filament\Resources\ProductTypes\ProductTypeResource;
use App\Http\Controllers\Admin\CatalogSyncController;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Throwable;

class ListProductTypes extends ListRecords
{
    protected static string $resource = ProductTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
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
            CreateAction::make(),
        ];
    }
}
