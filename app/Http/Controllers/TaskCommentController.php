<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use App\Notifications\TaskCommentNotification;
use App\Traits\ApiAble;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TaskCommentController extends Controller
{
    use ApiAble;

    /**
     * @param $id
     * @return JsonResponse
     */
    public function index($id): JsonResponse
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

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function store(Request $request, $id): JsonResponse
    {
        try {
            $comment = $request->get('comment');
            $taskComment = TaskComment::create([
                'user_id' => auth()->user()->id,
                'comment' => $comment,
                'task_id' => $id,
                'created_by' => auth()->user()->id,
            ]);
            $task = Task::with(['assigneeUsers', 'createdBy'])->findOrFail($id);
            $notificationPayload = [
                'name' => $task->title,
                'comment_user_name' => auth()->user()->name,
                'comment' => $comment,
            ];

            $task->createdBy->notify(new TaskCommentNotification($notificationPayload));
            foreach ($task->assigneeUsers as $user) {
                $user->notify(new TaskCommentNotification($notificationPayload));
            }

            return $this->successResponse($taskComment, "Task comment stored successfully.", Response::HTTP_CREATED);
        } catch (Exception $exception) {
            Log::error("taskComment:store --> {$exception->getMessage()}");
            return $this->errorResponse($exception->getMessage());
        }
    }
}
