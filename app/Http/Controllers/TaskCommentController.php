<?php

namespace App\Http\Controllers;

use App\Models\TaskComment;
use App\Traits\ApiAble;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskCommentController extends Controller
{
    use ApiAble;

    public function index($id)
    {
        try {
            $taskCommentList = TaskComment::with('user')->where('task_id', $id)->get();
            $taskCommentList->map(function ($comment) {
                $comment['user']['name'] = auth()->user()->id == $comment->user_id ? 'You' : $comment->user->name;
                return $comment;
            });

            return $this->successResponse($taskCommentList);
        } catch (Exception $exception) {
            Log::error("taskComment:index --> {$exception->getMessage()}");
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function store(Request $request, $id)
    {
        try {
            $comment = $request->get('comment');
            $taskComment = TaskComment::create([
                'user_id' => auth()->user()->id,
                'comment' => $comment,
                'task_id' => $id,
                'created_by' => auth()->user()->id,
            ]);

            return $this->successResponse($taskComment, "Task comment stored successfully.");
        } catch (Exception $exception) {
            Log::error("taskComment:store --> {$exception->getMessage()}");
            return $this->errorResponse($exception->getMessage());
        }
    }
}