<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use DataTables;
use Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $data = User::latest()->get();
            return DataTables::of($data)
                    ->addColumn('action', function($data){
                        $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm">Edit</button>';
                        $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="edit" id="'.$data->id.'" class="delete btn btn-danger btn-sm">Delete</button>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('adminHome');
    }

    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required',
            'email' => ['required', 'email', 'unique:users'],
            'password' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        );

        User::create($form_data);

        return response()->json(['success' => 'User Added successfully.']);

    }

    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = User::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    public function update(Request $request, User $user)
    {
        $rules = array(
            'name' => 'required',
            'email' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $user = User::find($request->hidden_id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) $user->password = bcrypt($request->password);
        $user->save();

        // User::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'User is successfully updated']);

    }

    public function destroy($id)
    {
        $data = User::findOrFail($id);
        $data->delete();
    }

}
