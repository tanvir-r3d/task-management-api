<?php

namespace App\Models;

use App\Traits\AdditionalField;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes, AdditionalField;

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
}
