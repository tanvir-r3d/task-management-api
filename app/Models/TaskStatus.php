<?php

namespace App\Models;

use App\Traits\AdditionalField;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskStatus extends Model
{
    use SoftDeletes, AdditionalField;

    protected $table = "task_statuses";
    protected $fillable = [
        "name",
        "color",
        "created_by",
        "updated_by",
        "deleted_by",
    ];
}
