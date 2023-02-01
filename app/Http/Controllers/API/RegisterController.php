<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Validator;

class RegisterController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'no_hp' => 'required|numeric|min:10',
            'password' => 'required|min:5',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['remember_token'] = Str::random(10);
        $input['api_token'] = Str::random(100);
        $user = User::create($input);
        $user->assignRole('User');
        $success['token'] = $user->api_token;
        $success['name'] = $user->name;

        return $this->sendResponse($success, 'User register successfully.');
    }
}
