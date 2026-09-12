<?php

namespace App\Filament\Resources\HeroSlides\Tables;

use App\Models\HeroSlide;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class HeroSlidesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('desktop_image_url')
                    ->label('Desktop')
                    ->state(fn (HeroSlide $record): string => $record->desktop_image_url)
                    ->square(),
                ImageColumn::make('tablet_image_url')
                    ->label('Tablet')
                    ->state(fn (HeroSlide $record): ?string => $record->tablet_image_url)
                    ->square(),
                ImageColumn::make('mobile_image_url')
                    ->label('Mobile')
                    ->state(fn (HeroSlide $record): ?string => $record->mobile_image_url)
                    ->square(),
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->numeric()
                    ->sortable(),
                ToggleColumn::make('is_published')
                    ->label('Published'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }
}
