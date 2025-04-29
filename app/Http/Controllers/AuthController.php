<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\error;

class AuthController extends Controller
{
    //create the register function for validated user data
    public function register(Request $request): JsonResponse
    {
        // $validated = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:users',
        //     'password' => 'required|string|min:6|confirmed'
        // ]);


        try{
            
            //usin another method we can validate
        $validated = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);

        if($validated->fails())
        {
            return response()->json($validated->errors(),403);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => $user,
        ],200);

        }catch(\Exception $exception){

            return response()->json([
                'error' => $exception->getMessage()
            ],403);
        }
    }

    //login
    public function login(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'email' => 'required|string|email',
            'password' => 'required|string|min:6'
        ]);

        if($validated->fails())
        {
            return response()->json($validated->errors(),403);
        }
        
        $credinals = ['email' => $request->email, 'password' => $request->password];

        try{
            if(!auth()->attempt($credinals))
            {
                return response()->json(['error' => 'Invalid data']);
            }

            $user = User::where('email', $request->email)->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'user' => $user,
            ],200);

        }catch(\Exception $exception){
            return response()->json([
                'error' => $exception->getMessage()
            ],403);
        }
    }

    //logout
    public function logout(Request $request)
    {
        $request->user()->currentAccesstoken()->delete();

        return response()->json(['message' => 'user logout successfully']);
    }
}
