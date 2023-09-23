<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiAble;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    use ApiAble;

    /**
     * Display a listing of the resource.
     */
    public function index()
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
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
     * Display the specified resource.
     */
    public function show(string $id)
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
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
