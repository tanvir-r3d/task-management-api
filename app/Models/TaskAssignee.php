<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskAssignee extends Model
{
    protected $table = "task_assginees";
    protected $fillable = [
        'task_id',
        'task_uuid',
        'user_id',
        'created_by',
    ];
}
