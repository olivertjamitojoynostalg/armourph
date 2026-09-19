<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class AboutPageSettings extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $navigationLabel = 'About Page';

    protected static ?int $navigationSort = 6;

    protected string $view = 'filament.pages.about-page-settings';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $about = SiteSetting::content('about');

        $this->form->fill([
            'heading' => $about['heading'] ?? 'Technology made for the road ahead.',
            'body' => $about['body'] ?? 'Armour helps Filipino drivers build smarter, safer, and more enjoyable vehicles through dependable car technology, practical accessories, and professional installation.',
            'reviews_heading' => $about['reviews_heading'] ?? 'What our customers say',
            'review_images' => $about['review_images'] ?? [],
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('heading')
                    ->label('About page heading')
                    ->required()
                    ->maxLength(180)
                    ->columnSpanFull(),
                RichEditor::make('body')
                    ->label('About page content')
                    ->helperText('Select text and use the toolbar to apply bold, italic, or lists.')
                    ->toolbarButtons([
                        ['bold', 'italic'],
                        ['bulletList', 'orderedList'],
                        ['undo', 'redo'],
                    ])
                    ->required()
                    ->maxLength(3000)
                    ->columnSpanFull(),
                TextInput::make('reviews_heading')
                    ->label('Customer feedback heading')
                    ->required()
                    ->maxLength(150)
                    ->columnSpanFull(),
                FileUpload::make('review_images')
                    ->label('Customer feedback screenshots')
                    ->helperText('Drag screenshots to change their display order.')
                    ->disk('public')
                    ->directory('about-reviews')
                    ->visibility('public')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->maxFiles(50)
                    ->maxSize(5120)
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        SiteSetting::query()->updateOrCreate(['key' => 'about'], ['value' => [
            'heading' => $data['heading'],
            'body' => $data['body'],
            'reviews_heading' => $data['reviews_heading'],
            'review_images' => array_values($data['review_images'] ?? []),
        ]]);

        Notification::make()->success()->title('About page saved')->send();
    }
}
