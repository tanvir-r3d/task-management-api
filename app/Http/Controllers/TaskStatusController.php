<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStatusRequest;
use App\Models\TaskStatus;
use App\Traits\ApiAble;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskStatusController extends Controller
{
    use ApiAble;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $list = TaskStatus::paginate();

            return $this->successResponse($list, "Task status fetched successfully.");
        } catch (Exception $exception) {
            Log::error("taskStatus:index --> {$exception->getMessage()}");
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStatusRequest $request, TaskStatus $taskStatus)
    {
        try {
            $taskStatus->fill($request->all())->save();

            return $this->successResponse($taskStatus, "Task Status stored successfully.");
        } catch (Exception $exception) {
            Log::error("taskStatus:store --> {$exception->getMessage()}");
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $taskStatus = TaskStatus::findOrFail($id);

            return $this->successResponse($taskStatus, "Task Status fetched successfully.");
        } catch (Exception $exception) {
            Log::error("taskStatus:show --> {$exception->getMessage()}");
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskStatusRequest $request, string $id)
    {
        try {
            $taskStatus = TaskStatus::findOrFail($id);
            $taskStatus->fill($request->all())->save();

            return $this->successResponse($taskStatus, "Task status updated successfully.");
        } catch (Exception $exception) {
            Log::error("taskStatus:update --> {$exception->getMessage()}");
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            TaskStatus::findOrFail($id)->delete();

            return $this->successResponse(null, "Task status deleted successfully.");
        } catch (Exception $exception) {
            Log::error("taskStatus:destroy --> {$exception->getMessage()}");
            return $this->errorResponse($exception->getMessage());
        }
    }
}
