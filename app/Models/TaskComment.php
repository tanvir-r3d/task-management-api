<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    protected $table = "task_comments";
    protected $fillable = [
        'task_id',
        'task_uuid',
        'user_id',
        'comment',
        'created_by',
    ];
}
