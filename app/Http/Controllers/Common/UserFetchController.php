<?php

namespace App\Http\Controllers\Common;

use App\Models\User;
use App\Traits\ApiAble;
use Exception;
use Illuminate\Support\Facades\Log;

class UserFetchController
{
    use ApiAble;

    public function __invoke()
    {
        try {
            $users = User::query()
                // ->whereNotNull('email_verified_at')
                ->where('id', '!=', auth()->user()->id)
                ->get(["id as value", "name as label"]);

            return $this->successResponse($users, "Successfully user fetched");
        } catch (Exception $exception) {
            Log::error("userFetch --> {$exception->getMessage()}");
            return $this->errorResponse($exception->getMessage());
        }
    }
}
