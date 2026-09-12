<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $fillable = ['source_id', 'product_type_id', 'name', 'slug', 'description', 'specification', 'price', 'badge', 'sort_order', 'is_published', 'is_featured', 'image_path', 'gallery_images', 'image_alt', 'is_sample_image', 'image_source'];

    protected function casts(): array
    {
        return ['price' => 'decimal:2', 'gallery_images' => 'array', 'is_published' => 'boolean', 'is_featured' => 'boolean', 'is_sample_image' => 'boolean'];
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort_order')->orderBy('id');
    }

    public function getDisplayPriceAttribute(): string
    {
        return $this->price === null ? 'Price on inquiry' : '₱'.number_format((float) $this->price, fmod((float) $this->price, 1) == 0 ? 0 : 2);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        return $this->resolveImageUrl($this->image_path);
    }

    /**
     * @return list<array{url: string, alt: string}>
     */
    public function getProductImagesAttribute(): array
    {
        $paths = collect([$this->image_path, ...($this->gallery_images ?? [])])
            ->filter(fn (mixed $path): bool => is_string($path) && $path !== '')
            ->unique()
            ->values();

        return $paths->map(fn (string $path, int $index): array => [
            'url' => $this->resolveImageUrl($path),
            'alt' => $index === 0 && $this->image_alt
                ? $this->image_alt
                : $this->name.' — image '.($index + 1),
        ])->all();
    }

    private function resolveImageUrl(string $path): string
    {
        return Str::startsWith($path, ['assets/', 'storage/'])
            ? asset($path)
            : Storage::disk('public')->url($path);
    }
}
