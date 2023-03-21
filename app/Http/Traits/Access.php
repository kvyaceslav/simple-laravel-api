<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

trait Access
{
    use HttpResponses;

    /**
     * @param Model $model
     * @return boolean
     */
    protected function canAccess(Model $model): bool
    {
        return $model->user_id !== Auth::id() ? False : True;
    }
}
