<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function ($product) {
            $product->user_id = Auth::id();
        });
    }

    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeForUser(Builder $query): Builder
    {
        return $query->where('user_id', Auth::id());
    }

    /**
     * @param Builder $query
     * @param array $ids
     * @return Collection
     */
    public function scopeForUserByIds(Builder $query, array $ids): Collection
    {
        return $query->find($ids)->where('user_id', Auth::id());
    }
}
