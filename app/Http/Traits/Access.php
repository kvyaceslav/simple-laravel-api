<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait Access
{
    /**
     * @param Model $model
     * @return bool
     */
    protected function canAccess(Model $model): bool
    {
        return !($model->user_id !== Auth::id());
    }
}
