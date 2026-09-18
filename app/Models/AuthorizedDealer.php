<?php

namespace App\Models;

use Database\Factories\AuthorizedDealerFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuthorizedDealer extends Model
{
    /** @use HasFactory<AuthorizedDealerFactory> */
    use HasFactory;

    protected $fillable = ['name', 'image_path', 'sort_order', 'is_published'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort_order')->orderBy('id');
    }

    public function getImageUrlAttribute(): string
    {
        return Str::startsWith($this->image_path, ['assets/', 'storage/'])
            ? asset($this->image_path)
            : Storage::disk('public')->url($this->image_path);
    }
}
