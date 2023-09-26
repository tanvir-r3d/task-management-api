<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\TaskAssignee;
use App\Traits\ApiAble;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    use ApiAble;

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $list = Task::with(['assigneeUsers', 'status'])->paginate();
            $list->getCollection()->transform(function ($collection) {
                $collection['can_edit'] = $collection->created_by == auth()->user()->id;
                if (!$collection['can_edit']) {
                    $collection['can_edit'] = !!$collection->assignees->where('user_id', auth()->user()->id)->first();
                }
                return $collection;
            });

            return $this->successResponse($list, "Task fetched successfully");
        } catch (Exception $exception) {
            Log::error("task:index --> {$exception->getMessage()}");
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * @param TaskRequest $request
     * @param Task $task
     * @return JsonResponse
     */
    public function store(TaskRequest $request, Task $task): JsonResponse
    {
        try {
            DB::beginTransaction();
            $taskFill = $request->only('title', 'description', 'status_id');
            $task->fill($taskFill)->save();

            $assignees = [];
            foreach ($request->get('assignees') as $assignee) {
                $assignees[] = [
                    'task_uuid' => $task->uuid,
                    'user_id' => $assignee['value'],
                ];
            }
            $task->assignees()->createMany($assignees);

            DB::commit();
            return $this->successResponse($task, "Task stored successfully.", Response::HTTP_CREATED);
        } catch (Exception $exception) {
            Log::error("task:store --> {$exception->getMessage()}");
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $task = Task::query()
                ->with(['assigneeUsers:id as value,name as label', 'status'])
                ->findOrFail($id);

            $canEdit = $task->created_by == auth()->user()->id;
            if (!$canEdit) {
                $canEdit = $task->assignees->where('user_id', auth()->user()->id)->count() > 0;
            }
            $task['can_edit'] = $canEdit;

            return $this->successResponse($task, "Task fetched successfully.");
        } catch (Exception $exception) {
            Log::error("task:show --> {$exception->getMessage()}");
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $taskFill = $request->only('title', 'description', 'status_id');
            $task = Task::findOrFail($id);
            $task->fill($taskFill)->save();

            $this->updateAssignee($request->get('assignees'), $task);

            DB::commit();
            return $this->successResponse($task, "Task stored successfully.", Response::HTTP_CREATED);
        } catch (Exception $exception) {
            Log::error("task:store --> {$exception->getMessage()}");
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * @param $assignees
     * @param $task
     * @return void
     */
    private function updateAssignee($assignees, $task)
    {
        foreach ($assignees as $assignee) {
            TaskAssignee::updateOrCreate([
                'user_id' => $assignee['value'],
                'task_id' => $task->id,
                'task_uuid' => $task->uuid,
            ], [
                'created_by' => auth()->user()->id,
            ]);
        }
        $users = collect($assignees)->pluck('value');
        TaskAssignee::where('task_id', $task->id)->whereNotIn('user_id', $users)->delete();
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $task = Task::findOrFail($id);
            $task->assignees()->delete();
            $task->comments()->delete();
            $task->delete();

            return $this->successResponse(null, "Task deleted successfully.");
        } catch (Exception $exception) {
            Log::error("task:destroy --> {$exception->getMessage()}");
            return $this->errorResponse($exception->getMessage());
        }
    }
}
