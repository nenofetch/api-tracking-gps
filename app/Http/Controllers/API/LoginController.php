<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;

class LoginController extends BaseController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            // $success['token'] = $user->createToken('TokenUser')->accessToken;
            $success['name'] = $user->name;
            $success['token'] = [
                'Accept' => 'application/json',
                'Authorization' => $request->bearerToken()
            ];


            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->tokens();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!.'];
        return response()->json($response, 200);
    }
}
