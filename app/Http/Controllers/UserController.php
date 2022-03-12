<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
Use App\Models\User;
use Illuminate\Support\Facades\Validator;



class UserController extends Controller
{
    public function registration(request $request)
    {
        $validation = Validator::make($request-> all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password'=>'required|same:password'
        ]);
        if($validation->fails()){
            return response()->json($validation->errors(),202);
        }
        $allData = $request->all();
        $allData['password'] = bcrypt($allData['password']);

        $user = User::Create($allData);

        $resArr = [];
        $ressArr['token'] = $user->createToken('api-application')->accessToken;
        $resArr['name']=$user->name;

        return response()->json($resArr,200);
    }

    public function login(request $request)
    {
        if(Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ]))
        {
            $user = Auth::user();
            $resArr = [];
            $ressArr['token'] = $user->createToken('api-application')->accessToken;
            $resArr['name'] = $user->name; 
            
            return response()->json($resArr,200);
        }
        else{
            return response()->json(['error'=>'Unauthorized Access'],203);
        }
    }

}
