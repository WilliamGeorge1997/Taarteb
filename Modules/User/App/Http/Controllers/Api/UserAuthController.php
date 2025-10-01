<?php

namespace Modules\User\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\User\App\resources\UserResource;
use Modules\User\App\Http\Requests\UserLoginRequest;

class UserAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:user', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserLoginRequest $request)
    {
        try {
            $credentials = $request->validated();

            if (!$token = auth('user')->attempt($credentials)) {
                return returnValidationMessage(false, 'Unauthorized', ['password' => 'wrong credentials'], 'unauthorized');
            }
            $user = auth('user')->user();

            if ($user['is_active'] == 0) {
                return returnMessage(false, 'In-Active User Verification Required', null, 'temporary_redirect');
            }

            if ($request['fcm_token'] ?? null) {
                $user->update(['fcm_token' => $request->fcm_token]);
            }

            // if ($user->hasRole('Student')) {
            //     $student = $user->student;
            //     if (!$student->canLogin()['status']) {
            //         return returnMessage(false, $student->canLogin()['message'], null, 'temporary_redirect');
            //     }
            // }

            return $this->respondWithToken($token);

        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return returnMessage(true, 'User Data',  new UserResource(auth('user')->user()));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return returnMessage(true, 'Successfully logged out');
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return returnMessage(true, 'Successfully Logged in', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('user')->factory()->getTTL() * 60,
            'user' => new UserResource(auth('user')->user()),
        ]);
    }
}
