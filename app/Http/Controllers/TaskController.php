<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskAssignee;
use App\Traits\ApiAble;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    use ApiAble;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $list = Task::with(['assignees', 'status'])->paginate();

            return $this->successResponse($list, "Task fetched successfully");
        } catch (Exception $exception) {
            Log::error("task:index --> {$exception->getMessage()}");
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Task $task)
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $task = Task::query()
                ->with(['assignees:id as value,name as label', 'status'])
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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

    private function updateAssignee($assignees, $task)
    {
        foreach ($assignees as $assignee) {
            TaskAssignee::updateOrCreate([
                'id' => $assignee['id'] ?? null,
                'user_id' => $assignee['value'],
                'task_id' => $task->id,
                'task_uuid' => $task->uuid,
            ], [
                'created_by' => auth()->user()->id,
            ]);
        }
        $users = collect($assignees)->pluck('user_id');
        TaskAssignee::where('task_id', $task->id)->whereNotIn('user_id', $users)->delete();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
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
