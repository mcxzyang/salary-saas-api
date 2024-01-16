<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait RecordUserTrait
{
    protected static function bootRecordUserTrait(): void
    {
        static::creating(function (Model $model) {
            if ($guard = $model->getModelAuthGuard()) {
                $user = auth($guard)->user();
                if ($user) {
                    $model->created_by = $user->id;
                }
            }
        });

        static::updating(function (Model $model) {
            if ($guard = $model->getModelAuthGuard()) {
                $user = auth($guard)->user();
                if ($user) {
                    $model->updated_by = $user->id;
                }
            }
        });
    }

    protected function getModelAuthGuard()
    {
        return method_exists($this, 'getAuthGuard')
            ? $this->getAuthGuard()
            : null;
    }
}
