<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dotenv\Validator;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Rules\Password;

class UsersProfileController extends Controller
{
    public function index(){
        return view('pages.user-profile');
    }

    public function changePassword(Request $request){
        $validated = $request->validate([ 'current_password' => ['required', 'string', 'current_password:web'],
            'password' => ['required', 'string', new Password, 'confirmed']
        ], [
            'current_password.current_password' => __('The provided password does not match your current password.'),
        ]);

        try {
            $request->user()->forceFill([
                'password' => Hash::make($validated['password']),
            ]);

            return response()->json([
                'status'=> 'success',
                'title'=> 'Password changed',
                'messages' => 'Successfully changed.'
            ]);
        }catch (\Exception $e){
            return response()->json([
                'status'=> 'error',
                'title'=> 'Failed',
                'messages' => $e->getMessage()
            ]);
        }
    }

    public function updateInfo(Request $request){

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($request->user()->id),
            ],
        ]);

        try {
            if($validated['email'] !== $request->user()->email && $request->user() instanceof MustVerifyEmail){
                $this->updateVerifiedUser($request->user(), $validated);
            }else {
                $request->user()->forceFill([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                ])->save();
            }

            return response()->json([
                'status'=> 'success',
                'title'=> 'Info Updated',
                'messages' => 'Successfully changed.'
            ]);
        } catch (\Exception $e){
            response()->json([
               'status' => 'error',
                'message'=> $e->getMessage(),
            ]);
        }
    }

    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
