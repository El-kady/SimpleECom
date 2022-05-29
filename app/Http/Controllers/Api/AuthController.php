<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class AuthController extends Controller
{
    use ApiResponser;

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) return $this->sendError('Validation Error.', $validator->errors(), 422);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user             = Auth::user();
            $success['name']  = $user->name;
            $success['token'] = $user->createToken('accessToken')->accessToken;

            return $this->sendResponse($success, 'You are successfully logged in.');
        } else {
            return $this->sendError('Unauthorised', ['error' => 'Unauthorised'], 401);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
            'user_type' => 'required|in:MERCHANT,CONSUMER'
        ]);

        if ($validator->fails()) return $this->sendError('Validation Error.', $validator->errors(), 422);

        try {

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => bcrypt($request->password),
                'user_type' => $request->user_type,
            ]);

            if($request->user_type === "MERCHANT"){
                $user->merchant()->save(new Merchant());
            }

            $success['name']  = $user->name;
            $message          = 'A user has been successfully created.';
            $success['token'] = $user->createToken('accessToken')->accessToken;
        } catch (Exception $e) {
            $success['token'] = [];
            $message          = 'Oops! Unable to create a new user.';
        }

        return $this->sendResponse($success, $message);
    }
}
