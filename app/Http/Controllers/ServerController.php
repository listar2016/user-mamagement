<?php

namespace App\Http\Controllers;

use App\Server;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Validator;


class ServerController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index(Request $request)
    {
        if($request->ajax())
        {
            $data = Server::latest()->get();
            return DataTables::of($data)
                    ->addColumn('action', function($data){
                        $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm">Edit</button>';
                        $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="edit" id="'.$data->id.'" class="delete btn btn-danger btn-sm">Delete</button>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('servers');
    }

    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required',
            'host_name' => ['required', 'unique:servers'],
            'ip_address' => 'required',
            'user_name' => 'required',
            'password' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'name' => $request->name,
            'host_name' => $request->host_name,
            'ip_address' => $request->ip_address,
            'user_name' => $request->user_name,
            'password' => $request->password,
        );

        Server::create($form_data);

        return response()->json(['success' => 'Server Added successfully.']);
    }

    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = Server::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    public function update(Request $request, Server $server)
    {
        $rules = array(
            'name' => 'required',
            'host_name' => 'required',
            'ip_address' => 'required',
            'user_name' => 'required',
            'password' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'name' => $request->name,
            'host_name' => $request->host_name,
            'ip_address' => $request->ip_address,
            'user_name' => $request->user_name,
            'password' => $request->password,
        );

        Server::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Server is successfully updated']);
    }

    public function destroy($id)
    {
        $data = Server::findOrFail($id);
        $data->delete();
    }

    public function getUserHostNames() {

        $hostnames= Server::select('user_name', DB::raw('GROUP_CONCAT(host_name) as hostnames'))
                    ->groupBy('user_name');
        return response()->json($hostnames);
        // return DataTables::of($hostnames)->make(true);
    }
}
