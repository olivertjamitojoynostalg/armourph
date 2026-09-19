<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class WebsiteSettings extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static ?int $navigationSort = 7;

    protected string $view = 'filament.pages.website-settings';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $hero = SiteSetting::content('hero');
        foreach (['image_path', 'mobile_image_path'] as $key) {
            $hero[$key] = Str::startsWith($hero[$key] ?? '', 'storage/') ? Str::after($hero[$key], 'storage/') : null;
        }

        $this->form->fill([
            ...$hero,
            'stores' => SiteSetting::content('stores'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('eyebrow')->required()->maxLength(150),
                TextInput::make('title')->required()->maxLength(150),
                TextInput::make('accent')->required()->maxLength(150),
                Textarea::make('description')->required()->rows(4)->columnSpanFull(),
                FileUpload::make('image_path')
                    ->label('Desktop mini hero')
                    ->helperText('Recommended: 1200 × 1200 px, WebP or JPG, up to 5 MB.')
                    ->disk('public')
                    ->directory('hero')
                    ->visibility('public')
                    ->image()
                    ->maxSize(5120),
                FileUpload::make('mobile_image_path')
                    ->label('Mobile mini hero')
                    ->helperText('Recommended: 1200 × 900 px, WebP or JPG, up to 5 MB.')
                    ->disk('public')
                    ->directory('hero')
                    ->visibility('public')
                    ->image()
                    ->maxSize(5120),
                TextInput::make('image_alt')->label('Image description')->required()->maxLength(255)->columnSpanFull(),
                TextInput::make('image_source')->label('Image source URL')->url()->maxLength(2000)->columnSpanFull(),
                Toggle::make('is_sample_image')->label('Sample image'),
                Repeater::make('stores')
                    ->label('Online stores')
                    ->schema([
                        TextInput::make('name')->required()->maxLength(100),
                        TextInput::make('label')->required()->maxLength(100),
                        TextInput::make('url')->required()->url()->maxLength(2000),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ])
            ->columns(2)
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $hero = SiteSetting::content('hero');
        foreach (['eyebrow', 'title', 'accent', 'description', 'image_alt', 'image_source', 'is_sample_image'] as $key) {
            $hero[$key] = $data[$key] ?? null;
        }
        foreach (['image_path', 'mobile_image_path'] as $key) {
            if (! empty($data[$key])) {
                $hero[$key] = 'storage/'.$data[$key];
            }
        }

        SiteSetting::query()->updateOrCreate(['key' => 'hero'], ['value' => $hero]);
        SiteSetting::query()->updateOrCreate(['key' => 'stores'], ['value' => $data['stores']]);

        Notification::make()->success()->title('Website settings saved')->send();
    }
}
