<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogItem extends Model
{
    use HasFactory;

    protected $fillable = ['kind', 'source_id', 'name', 'slug', 'category', 'description', 'specification', 'inclusions', 'price', 'badge', 'sort_order', 'is_published', 'image_path', 'image_alt', 'is_sample_image', 'image_source'];

    protected function casts(): array
    {
        return ['inclusions' => 'array', 'price' => 'decimal:2', 'is_published' => 'boolean', 'is_sample_image' => 'boolean'];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort_order')->orderBy('id');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? asset($this->image_path) : null;
    }

    public function getDisplayPriceAttribute(): string
    {
        return $this->price === null ? 'Price on inquiry' : '₱'.number_format((float) $this->price, fmod((float) $this->price, 1) == 0 ? 0 : 2);
    }
}
