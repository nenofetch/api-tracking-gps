<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Validator;

class UserController extends BaseController
{

    // public function __construct()
    // {
    //     $this->middleware('auth:api')->except('index', 'show');
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::paginate(10);
        return $this->sendResponse($user, 'The list has been displayed successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
            'gender' => 'required',
            'no_hp' => 'required|numeric|min:10',
            'address' => 'required',
            'gps_code' => 'required',
        ]); 

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $user = User::create($input);

        return $this->sendResponse($user, 'Data has been successfully added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        return $this->sendResponse($user, 'Data has been successfully retrieved');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        
        if ($user->email == $request->email) {
            $rules = 'required|email';
        } else {
            $rules = 'required|email|unique:users';
        }

        if ($request->password) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => $rules,
                'password' => 'required|min:5',
                'gender' => 'required',
                'no_hp' => 'required|numeric|min:10',
                'address' => 'required',
                'gps_code' => 'required',
            ]); 

            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user->update($input);
        } else {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => $rules,
                'gender' => 'required',
                'no_hp' => 'required|numeric|min:10',
                'address' => 'required',
                'gps_code' => 'required',
            ]); 
            $input = $request->all();
            $user->update($input);
        }

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return $this->sendResponse($user, 'Data has been successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->sendResponse($user, 'Data has been successfully deleted');
    }
}
