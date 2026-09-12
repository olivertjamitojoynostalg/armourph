<?php

namespace App\Models;

use Database\Factories\HeroSlideFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HeroSlide extends Model
{
    /** @use HasFactory<HeroSlideFactory> */
    use HasFactory;

    protected $fillable = ['eyebrow', 'title', 'accent', 'description', 'desktop_image_path', 'tablet_image_path', 'mobile_image_path', 'image_alt', 'show_content', 'button_label', 'button_url', 'content_position', 'sort_order', 'is_published'];

    protected function casts(): array
    {
        return [
            'show_content' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort_order')->orderBy('id');
    }

    public function getDesktopImageUrlAttribute(): string
    {
        return $this->imageUrl($this->desktop_image_path);
    }

    public function getTabletImageUrlAttribute(): ?string
    {
        return $this->tablet_image_path ? $this->imageUrl($this->tablet_image_path) : null;
    }

    public function getMobileImageUrlAttribute(): ?string
    {
        return $this->mobile_image_path ? $this->imageUrl($this->mobile_image_path) : null;
    }

    public function getSafeButtonUrlAttribute(): ?string
    {
        if (! $this->button_url) {
            return null;
        }

        if (Str::startsWith($this->button_url, ['#', 'https://', 'http://'])) {
            return $this->button_url;
        }

        return Str::startsWith($this->button_url, '/') && ! Str::startsWith($this->button_url, '//')
            ? $this->button_url
            : null;
    }

    private function imageUrl(string $path): string
    {
        return Str::startsWith($path, ['assets/', 'storage/']) ? asset($path) : Storage::disk('public')->url($path);
    }
}
