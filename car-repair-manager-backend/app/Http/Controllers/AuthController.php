<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class AuthController extends Controller {

    // logic for user registration
    public function register(Request $request){

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed'
        ]);

        // user account already exists ?
        $found = User::where('email', $request->email)->first();

        if(!$found){

            $password = Hash::make($request->password, ['rounds' => 12]);
            $verify_otp = Str::random(10);
            $password_reset_code = Str::random(6);

            $account = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $password,
                'password_reset_code' => $password_reset_code,
                'verify_otp' => $verify_otp,
            ];

            // create user account
            $user = User::create($account);

            // create token
            $token = $user->createToken($request->name);

            // some specific variables
            $action = 'emails.verify_email';
            $subject = 'verify email';       // form to show

            // send email verification link
            Mail::to($user->email)->send(new VerifyEmail($user, $verify_otp, $action, $subject));

            return [
                'status' => 201,
                'user' => $user,
                'token' => $token->plainTextToken
            ];

        }else{

            return [
                'status' => 409,
                'message' => 'Account already exists. Please logg-in'
            ];
        }        
    }

    // logic to manage user logg-in
    public function login(Request $request){
        
        // fields validation rules
        $request->validate([
            "email" => "required|email|exists:users,email",
            "password" => "required",
        ]);

        $user = User::where('email', $request->email)->first();

        if($user){

            // user's email already verified?
            if ($user->hasVerifiedEmail()) {

                // did passwords matches ?
                if(Hash::check($request->password, $user->password)){

                    // Provided credentials are correct
                    $token = $user->createToken('auth_token')->plainTextToken;

                    return [
                        'status' => 200,
                        'auth_token' => $token,
                        'user' => $user,
                    ];

                } else {

                    // uncorrect credentials
                    return [
                        'status' => 401,
                        'message' => 'The provided credentials are incorrect. Please try again !',
                    ];
                }
            }else{

                // unverify account
                return response()->json([
                    'status' => 403,
                    'message' => 'Check your email address first, to verify your account before logg-in.'
                ]);
            }
        }else{
            
            // user not found
            return [
                'status' => 404,
                'message' => 'user account not found !',
            ];
        }        
    }

    // logic for resetting password
    public function sendResetPasswordCode(){

        // log-in user
        $connected_user = Auth::user();

        if ($connected_user) {

            // get user infos
            $user = User::find($connected_user->id);

            // send reset password code email
            $action = 'auth.password_reset';

            $subject = 'verify password reset code';

            $password_reset_code = $user->password_reset_code;

            // send email to the user
            Mail::to($user->email)->send(new VerifyEmail($user, $password_reset_code, $action, $subject));

            return [
                'status' => 200,
                'user' => $user,
                'message' => 'The password reset code has been sent. Please, check to your email address.'
            ];
            
        }else{

            // user not found
            return [
                'status' => 401,
                'message' => 'Unauthorized, you must first log in.'
            ];
        }
    }

    // logic to verified user's account
    public function verify(Request $request){
        
        $user = User::find($request->id);

        if ($user->verify_otp === $request->hash) {
            $user->email_verified_at = now();
            $user->verify_otp = ""; // delete the verify_otp after email vérification
            $user->save();

            return [
                'status' => 200,
                'user' => $user,
                'message' => 'Email address verified successfully.'
            ];
        }else{
            return [
                'status' => 404,
                'message' => 'Invalid verification Code.'
            ];
        }
    }

    // logic to resend a verification code to user
    public function resendVerification(Request $request){

        $user = User::find($request->id);

        if ($user->email_verified_at === null) {

            // generate unique verify_otp
            $verify_otp = Str::random(10);
            $action = 'emails.resend_email';
            $subject = 'verify email';    // form to show

            // update verify_otp field in the user
            $user->update([
                'verify_otp' => $verify_otp,
            ]);

            // resend verification email
            Mail::to($user->email)->send(new VerifyEmail($user, $verify_otp, $action, $subject));
           
            return [
                'status' => 200,
                'user' => $user,
                'message' => 'New verification email successfully sent.'
            ];
            
        }else{
            return response()->json([
                'status' => 500,
                'message' => 'Unable to send a new verification email.'
            ]);
        }
    }

    // logic to reset user's password
    public function reset(Request $request){

        // log-in user
        $connected_user = Auth::user();

        if ($connected_user) {

            // fields validation rules
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
                'password_reset_code' => 'required|max:255',
            ]);

            // found password reset code which belongs to the connected user
            $user = User::find($connected_user->id);

            if($user && ($request->password_reset_code === $user->password_reset_code)) {

                // regenerate & update the new password
                $user->password = Hash::make($request->password, ['rounds' => 12]);
                $user->password_reset_code = Str::random(6);
                $user->save();

                // Revoke all user token
                $user->tokens()->delete();

                return [
                    'status' => 200,
                    'message' => 'Password successfully reset. You can now log in.'
                ];

            }else{

                // password reset code not matching
                return [
                    'status' => 422,
                    'message' => 'Invalid credential.'
                ];
            }

        }else{

            // no user connected
            return [
                'status' => 401,
                'message' => 'Unauthorized, you must first log in.'
            ];
        }
    }

    // logic to logg-out
    public function logout(Request $request){

        $request->user()->tokens()->delete();

        return [
            'status' => 200,
            'message' => "You are logged out."
        ];
    }
    
}
