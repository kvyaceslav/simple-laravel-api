<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    /**
     * @return HasMany
     */
    public function categories(): HasMany
    {
        return $this->hasMany(__CLASS__);
    }

    /**
     * @return HasMany
     */
    public function childrenCategories(): HasMany
    {
        return $this->hasMany(__CLASS__)->with('categories');
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
    public function scopeGetListForUser(Builder $query): Builder
    {
        return $query
            ->where('user_id', Auth::id())
            ->whereNull('category_id')
            ->with('childrenCategories');
    }

    /**
     * @param Builder $query
     * @param string $name
     * @return Builder
     */
    public function scopeGetByNameForUser(Builder $query, string $name): Builder
    {
        return $query
            ->where('user_id', Auth::id())
            ->where('name', $name);
    }
}
