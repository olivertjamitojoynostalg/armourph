<?php

namespace App\Models;

use Database\Factories\PackageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Package extends Model
{
    /** @use HasFactory<PackageFactory> */
    use HasFactory;

    protected $fillable = ['source_id', 'name', 'slug', 'category', 'description', 'specification', 'inclusions', 'price', 'badge', 'sort_order', 'is_published', 'is_featured', 'image_path', 'image_alt', 'is_sample_image', 'image_source'];

    protected function casts(): array
    {
        return ['inclusions' => 'array', 'price' => 'decimal:2', 'is_published' => 'boolean', 'is_featured' => 'boolean', 'is_sample_image' => 'boolean'];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort_order')->orderBy('id');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        return Str::startsWith($this->image_path, ['assets/', 'storage/'])
            ? asset($this->image_path)
            : Storage::disk('public')->url($this->image_path);
    }

    public function getDisplayPriceAttribute(): string
    {
        return $this->price === null ? 'Price on inquiry' : '₱'.number_format((float) $this->price, fmod((float) $this->price, 1) == 0 ? 0 : 2);
    }
}
