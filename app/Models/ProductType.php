<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductType extends Model
{
    protected $fillable = ['name', 'image_path', 'image_alt'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
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
}
