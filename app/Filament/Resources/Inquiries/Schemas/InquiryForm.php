<?php

namespace App\Filament\Resources\Inquiries\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->disabled()
                    ->dehydrated(false),
                TextInput::make('contact')
                    ->tel()
                    ->disabled()
                    ->dehydrated(false),
                TextInput::make('branch_name')
                    ->label('Branch')
                    ->disabled()
                    ->dehydrated(false),
                TextInput::make('interest')
                    ->formatStateUsing(fn (?string $state): string => ucfirst($state ?? ''))
                    ->disabled()
                    ->dehydrated(false),
                Select::make('status')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'closed' => 'Closed',
                        'spam' => 'Spam',
                    ])
                    ->required(),
                Textarea::make('internal_notes')
                    ->label('Internal notes')
                    ->helperText('Visible only in website maintenance.')
                    ->maxLength(2000)
                    ->rows(5)
                    ->columnSpanFull(),
            ]);
    }
}
