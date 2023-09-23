<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait AdditionalField
{
    /**
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }

    /**
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "updated_by", "id");
    }

    /**
     * @return BelongsTo
     */
    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "deleted_by", "id");
    }

    /**
     * created_by , updated_by , deleted_by column event
     */
    public static function bootAdditionalField()
    {
        if (app()->runningInConsole()) {
            return;
        }
        static::saving(function ($model) {
            if (!$model->isFillable('updated_by')) {
                return;
            }
            $model->created_by = auth()->user()->id;
        });

        static::updating(function ($model) {
            if (!$model->isFillable('updated_by')) {
                return;
            }
            $model->updated_by = auth()->user()->id;
        });

        static::deleting(function ($model) {
            if (!$model->isFillable('deleted_by')) {
                return;
            }
            $model->deleted_by = auth()->user()->id;
            $model->save();
        });
    }
}
