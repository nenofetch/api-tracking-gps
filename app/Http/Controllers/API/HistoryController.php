<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\History;
use App\Models\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Validator;

class HistoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $history = History::paginate(10);
        return $this->sendResponse($history, 'The list has been displayed successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $header = $request->header('Token');
        $data = User::where('remember_token', $header)->first();

        if ($data) {
            $validator = Validator::make($request->all(), [
                'longtitude' => 'required',
                'latitude' => 'required',
            ]); 
    
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
    
            $input = $request->all();
            $input['user_id'] = $data->id;
            $history = History::create($input);
            return $this->sendResponse($history, 'Data has been successfully added');
        } else {
            return $this->sendResponseErr('Data has been unsuccessfully added');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $history = History::find($id);

        return $this->sendResponse($history, 'Data has been successfully retrieved');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, History $history)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'longtitude' => 'required',
            'latitude' => 'required',
        ]); 

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $history->update($input);

        return $this->sendResponse($history, 'Data has been successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(History $history)
    {
        $history->delete();
        return $this->sendResponse($history, 'Data has been successfully deleted');
    }
}
