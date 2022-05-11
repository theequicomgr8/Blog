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
        $email=Session::get('user');
        $countries=DB::table('countries')->get();
        $studentdata=DB::table('students as student')
                ->select('student.name as sname','student.id as sid','student.email as semail','country.id as cid','country.id as country_id','country.name as country_name','state.id as state_id','state.name as state_name','city.id as city_id','city.name as city_name')
                ->leftJoin('countries as country','country.id','=','student.country')
                ->leftJoin('states as state','state.id','=','student.state')
                ->leftJoin('cities as city','city.id','=','student.city')
                ->where("email",$email)
                ->first();
        $country_id=@$studentdata->country_id;
        $state_id=@$studentdata->state_id;       
        $statefetch=DB::table('states')->where('country_id',$country_id)->get();
        $cityfetch=DB::table('cities')->where('state_id',$state_id)->get();

        $student_id=@$studentdata->sid;
        $mobiles=DB::table('mobiles')->where("student_id",$student_id)->get();
        // dd($cityfetch);
        return view('profile',compact('countries','studentdata','statefetch','cityfetch','mobiles'));
    }

    public function getState(Request $request)
    {
        $country_id=$request->countryid;
        $states=DB::table('states')->where('country_id',$country_id)->get();
        $html="";
        foreach($states as $state)
        {
            $html.='<option value="'.$state->id.'">'.$state->name.'</option>';
        }
        echo $html;
    }

    public function getCity(Request $request)
    {
        $state_id=$request->stateid;
        $cities=DB::table('cities')->where('state_id',$state_id)->get();
        $html="";
        foreach($cities as $city)
        {
            $html.='<option value="'.$city->id.'">'.$city->name.'</option>';
        }
        echo $html;
    }

    public function student_save(Request $request)
    {
        $request->validate([
            "name"=>'required',
            "email"=>'required',
            "country"=>'required',
            "state"=>'required',
            "city"=>'required'
        ]);



        $data=[
            "name"=>$request->name,
            "email"=>$request->email,
            "country"=>$request->country,
            "state"=>$request->state,
            "city"=>$request->city
        ];
        $student=DB::table('students')->insert($data);

        $getid=DB::table('students')->orderBy('id','desc')->first();
        $student_id=$getid->id;

        foreach($request->mobile as $key => $value)
        {
            $mobile=array(
                "student_id"=>$student_id,
                "mobile"=>$request->mobile[$key]
            );
            DB::table('mobiles')->insert($mobile);
        }

        Session::flash('success',"Student Data Save Successfully");
        return redirect()->route('user.profile');
        
    }


    public function logout()
    {
        // session_unset('user');
        Session::forget('user');
        return redirect()->route('user.home');
    }
}
