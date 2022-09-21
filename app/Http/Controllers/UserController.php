<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Str;

class UserController extends Controller
{
    
    public function register(Request $request)
    {
        $data = $request->all();
        $user1= User::where('mac_address', $request->mac_address)->first();

        if($user1 == null){
            $user = new User;
            $user->first_name= $request->first_name;
            $user->last_name=$request->last_name;
            $user->password=$request->password;
            $user->email=$request->email;
            $user->mac_address=$request->mac_address;
            $user->token = Str::random(4); 
            if($user->invitation_code != null){
                $user->invitation_code = $request->invitation_code;
                $reffere_user = User::where('generated_invitation_code', $request->invitation_code)->get();
                $reffere_user->invitation_count += 1;
                $reffere_user->save();
            }
            $user->generated_invitation_code = Str::random(4);
            $user->save();
            $response = [
                'status' => 200,
                'token' => $user->token,
                'generated_invitation_code'=>$user->generated_invitation_code
            ];
            return response()->json($response);
            //return response()->json([$user->token], 200);
        }

        
    }


    public function logIn(Request $request)
    {
        $user = User::where('email', $request->email)->where('password', $request->password)->first();
        if($user != null){
            $response = [
                'status' => 200,
                'token' => $user->token,
                'generated_invitation_code'=>$user->generated_invitation_code
            ];
        }

       
        return response()->json($response);
    }

    public function getUserInfo($token){
        $user = User::where('token', $token)->first();
        $response = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'points' => $user->points,
            'remain_roll' => $user->remain_rolls,
            'remain_ads' => $user->remain_ads,
            'invitation_count'=>$user->invitation_count,
            'generated_invitation_code' => $user->generated_invitation_code,
            'subscribe_plan' => $user->subscribe_plan,
            'ordered_point' => $user->ordered_point,
            'token' => $user->token
        ];
        return response()->json($response);
    }
    public function addToPoints(Request $request){
        $user = User::where('token', $request->token)->first();
        $user->decrement("remain_rolls");
        $user->increment("points", $request->increment); 
    }
}
