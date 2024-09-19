<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ForgotPasswordMail;
// use Illuminate\Auth\Notifications\ResetPassword;
use App\Http\Requests\ResetPassword;
// use Mail;

class AuthController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $user = User::create($fields);
        
        $token = $user->createToken($request->name);
        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    public function login(Request $request) {
        $request->validate(([
            'email' => 'required|email|exists:users',
            'password' => 'required'
        ]));

        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password,$user->password)) {
            return [
                'errors' => [
                    'email' => ['The Provided Credentials are incorrect']
                ]
                
            ];
        }

        $token = $user->createToken($user->name);
        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];

    }


    public function logout(Request $request) {

        $request->user()->tokens()->delete();

        return [
            'message' => 'You are logeed out'
        ];
    }

    public function forgot_password(Request $request) {
        $request->validate(([
            'email' => 'required|email'
        ]));

        $count = User::where('email', '=', $request->email)->count();
        if($count > 0) {
            $user = User::where('email', '=', $request->email)->first();
            $user->remember_token = Str::random(50);
            $user->save();
            Mail::to($user->email)->send(new ForgotPasswordMail($user));
            return ['message' => 'Password reset link sent to your mail, Please check your mail and click on reset button'];
        }else {
            return ['message' => 'Email not found In the System'];
        }
        // return [
        //     'message' => "tet"
        // ];
    }

    public function getReset(Request $request, $token) {
        // echo "etst";exit;
        $user = User::where('remember_token','=', $token);

        if($user->count() == 0){
            // return "est";
            abort(403);
        } 
        $user = $user->first();
        $data['token'] = $token;
        return [
            'rememberToken' => $data['token'],
            'message' => 'Password Reset Successfully'
        ];
    }

    public function reset_password($token,ResetPassword $request) {
        // echo $token; exit;
        $user = User::where('remember_token','=', $token);
        if($user->count() == 0){
            // return "est";
            // abort(403);
            return [
                'errors' => [
                    'message' => ['Invalid Token!!']
                ]
                
            ];
        } 
        $user = $user->first();
        $user->password = Hash::make($request->password);
        $user->remember_token = Str::random(50);
        if($user->save()){
              return [
            'message' => 'Successfully Password Reset'
        ];
        } else {
            abort(403);
        }
      
    }
    
}

