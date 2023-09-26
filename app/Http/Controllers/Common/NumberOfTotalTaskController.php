<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Traits\ApiAble;
use Illuminate\Support\Facades\Log;

class NumberOfTotalTaskController extends Controller
{
    use ApiAble;

    public function __invoke()
    {
        try {
            $totalTask = Task::count();

            return $this->successResponse(['total_task' => $totalTask], "Successfully fetched.");
        } catch (\Exception $exception) {
            Log::error("numberOfTotalTaskController --> {$exception->getMessage()}");
            return $this->errorResponse($exception->getMessage());
        }
    }
}
