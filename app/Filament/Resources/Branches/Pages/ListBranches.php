<?php

namespace App\Filament\Resources\Branches\Pages;

use App\Filament\Resources\Branches\BranchResource;
use App\Http\Controllers\Admin\CatalogSyncController;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Throwable;

class ListBranches extends ListRecords
{
    protected static string $resource = BranchResource::class;

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
