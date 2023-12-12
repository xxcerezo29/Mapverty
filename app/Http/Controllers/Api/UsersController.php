<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Nette\Utils\Random;

class UsersController extends Controller
{
    public function getUsers(){
        $users = [];
        if(auth()->user()->hasRole('Developer')){
            $users = User::role(['Developer', 'Super Admin', 'Teacher'])->get();
        }else{
            $users = User::role(['Super Admin', 'Teacher'])->get();
        }
        return datatables()->of($users)
            ->addIndexColumn()
            ->addColumn('role-display', function($row){
                $role = $row->getRoleNames();
                $role = $role[0];
                return $role;
            })->addColumn('Actions', function($row){
                $btn = auth()->user()->id != $row->id ? '<div data-id="'.$row->id.'"> <button onclick="remove('.$row->id.')" class="delete btn btn-danger btn-sm">Delete</button></div>' : 'My Account';

                return $btn;
            })->rawColumns(['Actions'])
            ->toJson();

    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'role' => 'required'
        ]);

        try {

            $random_password = Random::generate(8);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($random_password),
            ]);

            //send to email

            Mail::to($user->email)->send(new WelcomeEmail($user, $random_password));

            $user->assignRole($request->role);

            return response()->json([
                'message' => 'User successfully created!',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$id,
            'role' => 'required'
        ]);

        try {
            $user = User::find($id);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $user->syncRoles([$request->role]);

            return response()->json([
                'message' => 'User successfully updated!',
                'user' => $user
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }


    }

    public function delete($id){


        try {
            if(Auth::user()->hasRole('Developer|Super Admin')){
                $user = User::find($id);
                if($user == null){
                    return response()->json([
                        'message' => 'User not found!',
                    ]);
                }

                $user->delete();
                return response()->json([
                    'message' => 'User successfully deleted!',
                ]);
            }else {
                throw new \Exception('You are not authorized to delete a user!');
            }
        }catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }

    }
}
