<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\Validator;


use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use stdClass;
use Laravel\Sanctum\HasApiTokens;





class AuthController extends Controller
{

    use HasApiTokens;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:250',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
    
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }




    public function login(Request $request)
    {


        $credentials = $request->only('email', 'password');
        
        $user = User::where('email', $request->email)->firstOrFail();

        if (!Auth::attempt($credentials)) {

            if ($request->password != null && !Hash::check($request->password, $user->password)) {

                return response()->json(['error' => 'Se produjo un problema la es contraseña incorrecta'], 500);

            }else if($request->email != null && $request->email != $user->email){

                return response()->json(['error' => 'Se produjo un problema el correo es incorrecto'], 500);
            }

            return response()->json(['error' => 'Unauthorized'], 401);
        }
       

        $token = $user->createToken('auth_token')->plainTextToken;



        return response()
        ->json([

            'message' =>'Hi'. $user->name,
            'accesToken' =>$token,
            'token_type' =>'Bearer',
            'user'=>$user,

        ]);
    }


    public function logout(){
        
        auth()->user()->tokens()->delete();
        return[
            'message' => 'se a desloeao correctmente'

        ];
    }
}
