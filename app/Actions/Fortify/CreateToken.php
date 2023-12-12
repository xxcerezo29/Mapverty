<?php
namespace App\Actions\Fortify;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class CreateToken implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse($request)
    {

        $token = Auth::user()->createToken('authToken')->plainTextToken;
        setcookie('authToken', $token, time() + (86400 * 30), "/");

        return $request->wantsJson()
            ? new JsonResponse('', 204)
            : redirect()->intended(config('fortify.home'));
    }
}
