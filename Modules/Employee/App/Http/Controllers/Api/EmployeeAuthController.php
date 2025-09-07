<?php

namespace Modules\Employee\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Employee\App\Http\Requests\EmployeeLoginRequest;

class EmployeeAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:employee', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(EmployeeLoginRequest $request)
    {
        try {
            $credentials = $request->validated();

            if (!$token = auth('employee')->attempt($credentials)) {
                return returnValidationMessage(false, 'Unauthorized', ['password' => 'wrong credentials'], 'unauthorized');
            }

            if (auth('employee')->user()['is_active'] == 0) {
                return returnMessage(false, 'In-Active User Verification Required', null, 'temporary_redirect');
            }

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
        return returnMessage(true, 'Employee Data', auth('employee')->user());
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
        $user = auth('employee')->user();
        $user->role = $user->roles()->value('name');
        return returnMessage(true, 'Successfully Logged in', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('employee')->factory()->getTTL() * 60,
            'employee' => $user,
        ]);
    }
}
