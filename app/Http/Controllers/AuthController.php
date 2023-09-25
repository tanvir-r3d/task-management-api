<?php

namespace App\Http\Controllers;

use App\Mail\VerificationMail;
use App\Models\User;
use App\Models\VerificationCode;
use App\Traits\ApiAble;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    use ApiAble;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function me()
    {
        return $this->successResponse($this->guard()->user());
    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ], Response::HTTP_CREATED);
    }

    public function guard()
    {
        return Auth::guard();
    }

    public function sendVerificationMail()
    {
        try {
            VerificationCode::where('user_id', auth()->user()->id)->delete();

            $code = rand(111111, 999999);
            $expiredAt = Carbon::now()->addMinutes(2)->format('Y-m-d h:i:s');
            VerificationCode::create([
                'code' => $code,
                'user_id' => auth()->user()->id,
                'expired_at' => $expiredAt,
            ]);

            Mail::to(auth()->user()->email)->send(new VerificationMail($code));

            return $this->successResponse(
                ['expired_at' => $expiredAt],
                'Successfully veirification email sent.',
                Response::HTTP_CREATED
            );
        } catch (Exception $exception) {
            Log::error("auth:sendVerificationMail --> {$exception->getMessage()}");
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function checkVerificationCode(Request $request)
    {
        try {
            $code = $request->get('code');
            $verified = VerificationCode::where('user_id', auth()->user()->id)
                ->where('code', $code)
                ->first();
            if ($verified) {
                $verified->delete();
                User::find(auth()->user()->id)->update(['email_verified_at' => now()]);
                return $this->successResponse(null, 'Successfully verified email.', Response::HTTP_OK);
            }

            return $this->errorResponse('Verification code is not correct', Response::HTTP_NOT_ACCEPTABLE);
        } catch (Exception $exception) {
            Log::error("auth:checkVerificationCode --> {$exception->getMessage()}");
            return response()->json($exception->getMessage());
        }
    }
}
