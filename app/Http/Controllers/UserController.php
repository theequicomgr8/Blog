<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    public function sendResponse($data,$message)
    {
        $response=[
            "status"=>'true',
            "data"=>$data,
            "message"=>$message
        ];
        return response()->json($response,202);
    }

    public function sendError($error,$message=[])
    {
        $response=[
            "status"=>'false',
            "message"=>$error
        ];

        if(!empty($message))
        {
            $response['data']=$message;
        }

        return response()->json($response,404);
    }


    public function index()
    {
        $data=DB::table('students')->paginate(20);
        return view('home',compact('data'));
    }

    public function signup(Request $request)
    {
        $details=[
            "name"=>$request->name,
            "email"=>$request->email,
            "password"=>Hash::make($request->password)
        ];
        $data=DB::table('Users')->insert($details);
        $job=(new \App\Jobs\SendEmail($details))
                ->delay(
                    now()
                    ->addSeconds(2)
                );
        dispatch($job);
        Session::flash('msg',"Data Saved Success");
        return redirect()->route('user.home');
    }

    public function login(Request $request)
    {
        $user=DB::table('Users')->where('email',$request->email)->first();
        if($user && Hash::check($request->password,$user->password))
        {
            Session::put('user',$request->email);
            return redirect()->route('user.profile');
        }
        else
        {
            return "false";
        }
    }

    public function profile()
    {
        return view('profile');
    }
}
