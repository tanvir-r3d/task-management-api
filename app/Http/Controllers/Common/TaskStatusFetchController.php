<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\TaskStatus;
use App\Traits\ApiAble;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TaskStatusFetchController extends Controller
{
    use ApiAble;

    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        try {
            $taskStatuses = TaskStatus::get(['id as value', 'name as label']);

            return $this->successResponse($taskStatuses, "Task status fetched successfully.");
        } catch (Exception $exception) {
            Log::error("taskStatusFetch --> {$exception->getMessage()}");
            return $this->errorResponse($exception->getMessage());
        }
    }
}
