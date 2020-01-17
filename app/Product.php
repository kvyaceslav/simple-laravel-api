<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeGetListForUser(Builder $query): Builder
    {
        return $query->where('user_id', Auth::id());
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
