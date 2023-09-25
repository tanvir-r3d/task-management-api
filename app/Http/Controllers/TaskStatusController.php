<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStatusRequest;
use App\Models\TaskStatus;
use App\Traits\ApiAble;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TaskStatusController extends Controller
{
    use ApiAble;

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
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
     * @param TaskStatusRequest $request
     * @param TaskStatus $taskStatus
     * @return JsonResponse
     */
    public function store(TaskStatusRequest $request, TaskStatus $taskStatus): JsonResponse
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
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
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
     * @param TaskStatusRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(TaskStatusRequest $request, string $id): JsonResponse
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
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
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
