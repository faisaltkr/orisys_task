<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function register(UserRequest $request)
    {

        try {
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'dob' => Carbon::createFromFormat("d-m-Y",$request->dob),
            ]);
            
            $user->assignRole('staff');

            return response()->json([
                'success' => true,
                'data' => new UserResource($user),
            ]);


        } catch (\Exception $e) {
            return response()->json([
                'success'=> false,
                'mesage' => $e->getMessage()//something went wrong
            ],500);
        }
    }
    


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6'
        ]);
    
       try {

        if (Auth::attempt($credentials)) {
            //$request->session()->regenerate();
 
            $token = $request->user()->createToken('access_token');
 
            return response()->json([
                'user' => auth()->user(),
                'token' => $token->plainTextToken
            ]);
            
        }

        return response()->json([
            'status' => false,
            'message' => "Something went wrog"
        ]);

        
       } catch (\Exception $e) {
            return response()->json([
                'success'=> false,
                'mesage' => $e->getMessage()//something went wrong
            ],500);
       }
    }

    public function logout(Request $request)
    {
        try {
            if(auth()->user())
            {
                $request->user()->currentAccessToken()->delete();
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return response()->json([
                    'success' => true,
                    'message' => "User logged out"
                ],200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success'=>false,
                'mesage' => $e->getMessage()//something went wrong
            ],500);
        }
    }
}
