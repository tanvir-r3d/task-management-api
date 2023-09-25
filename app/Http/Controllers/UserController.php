<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiAble;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    use ApiAble;

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $list = User::paginate();

            return $this->successResponse($list, "User fetched successfully.");
        } catch (Exception $exception) {
            Log::error("user:index --> {$exception->getMessage()}");
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function store(Request $request, User $user): JsonResponse
    {
        try {
            $request['password'] = Hash::make($request->get('password'));
            $user->fill($request->all())->save();

            return $this->successResponse($user, "User stored successfully.", Response::HTTP_CREATED);
        } catch (Exception $exception) {
            Log::error("user:store --> {$exception->getMessage()}");
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
            $user = User::findOrFail($id);

            return $this->successResponse($user, "User fetched successfully.");
        } catch (Exception $exception) {
            Log::error("user:show --> {$exception->getMessage()}");
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
            $user = User::findOrFail($id);
            $user->fill($request->all())->save();

            return $this->successResponse($user, "User updated successfully.");
        } catch (Exception $exception) {
            Log::error("user:update --> {$exception->getMessage()}");
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
            User::findOrFail($id)->delete();

            return $this->successResponse(null, "User deleted successfully.");
        } catch (Exception $exception) {
            Log::error("user:destroy --> {$exception->getMessage()}");
            return $this->errorResponse($exception->getMessage());
        }
    }
}
