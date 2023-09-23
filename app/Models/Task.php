<?php

namespace App\Models;

use App\Traits\AdditionalField;
use App\Traits\UUIDAble;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes, AdditionalField, UUIDAble;

    protected $table = "tasks";
    protected $fillable = [
        'uuid',
        'title',
        'description',
        'status_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_assginees', 'task_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class, 'task_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(TaskStatus::class, 'status_id')->withDefault();
    }
}
