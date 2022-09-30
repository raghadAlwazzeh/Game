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
        $user2= User::where('email', $request->email)->first();
        if($user1 != null){
            $response= null;
            return response()->json(['status'=>301, 'you have an account from this device'=>"MAC address is already in database", 'data'=>$response]);
        }
        else if($user2 != null){
            $response= null;
            return response()->json(['status'=>302, 'message'=>"user is already exist", 'data'=>$response]);
        }
        else{
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
                //'status' => 200,
                'token' => $user->token,
                'generated_invitation_code'=>$user->generated_invitation_code
            ];
            return response()->json(['status'=>200, 'message'=>"registered successfully",'data'=>$response]);
        }
    }


    public function logIn(Request $request)
    {
        $user = User::where('email', $request->email)->where('password', $request->password)->first();
        if($user != null){
            $response = [
                //'status' => 200,
                'token' => $user->token,
                'generated_invitation_code'=>$user->generated_invitation_code
            ];
            return response()->json(['status'=>200, 'message'=>"logged in successfully", 'data'=>$response]);
        }

        $response= null;

        return response()->json(['status'=>303, 'message'=>"email or password is not correct", 'data'=>$response]);
       
        
    }

    public function getUserInfo($token){
        $user = User::where('token', $token)->first();
        $response = [
            //'status' => 200,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'points' => $user->points,
            'remain_roll' => $user->remain_rolls,
            'remain_ads' => $user->remain_ads_count,
            'invitation_count'=>$user->invitation_count,
            'generated_invitation_code' => $user->generated_invitation_code,
            'subscribe_plan' => $user->subscribe_plan,
            'ordered_point' => $user->ordered_points];
        
        return response()->json(['status'=>200, 'data'=>$response]);
    }
    public function addToPoints(Request $request){
        $user = User::where('token', $request->token)->first();
        $user->decrement("remain_rolls");
        $user->increment("points", $request->increment); 
        $response = [
            'status' => 200,
        ];
        return response()->json($response);
    }


    public function addToPointsAds(Request $request){
        $user = User::where('token', $request->token)->first();
        $user->decrement("remain_ads_count");
        $user->increment("points", $request->increment);
        $response = [
            'status' => 200,
        ];
        return response()->json($response); 
    }

    public function subPlan(Request $request){
        $user = User::where('token', $request->token)->first();
        if($request->subscribe_plan==0){
            $user->subscribe_plan=0;
            $user->rolls_count=3;
            $user->remain_rolls=3;
            $user->ads_count=3;
            $user->remain_ads_count=3;
            $user->update();
            $response = [
                //'status' => 200,
                'subscribe_plan'=>"Normal User"
            ];
            return response()->json(['status'=>200,'message'=>$response]); 
        }
        else if($request->subscribe_plan==1){
            $user->days_count=30;
            $user->subscribe_plan=1;
            $user->rolls_count=7;
            $user->remain_rolls=7;
            $user->ads_count=7;
            $user->remain_ads_count=7;
            $user->update();
            $response = [
                //'status' => 200,
                'subscribe_plan'=>"Bronze User"
            ];
            return response()->json(['status'=>200,'message'=>$response]); 
        }
        else if($request->subscribe_plan==2){
            $user->days_count=30;
            $user->subscribe_plan=2;
            $user->rolls_count=10;
            $user->remain_rolls=10;
            $user->ads_count=10;
            $user->remain_ads_count=10;
            $user->update();
            $response = [
                //'status' => 200,
                'subscribe_plan'=>"Silver User"
            ];
            return response()->json(['status'=>200,'message'=>$response]); 
        }
        else if($request->subscribe_plan==3){
            $user->days_count=30;
            $user->subscribe_plan=3;
            $user->rolls_count=15;
            $user->remain_rolls=15;
            $user->ads_count=15;
            $user->remain_ads_count=15;
            $user->update();
            $response = [
                //'status' => 200,
                'subscribe_plan'=>"Gold User"
            ];
            return response()->json(['status'=>200,'message'=>$response]); 
        }
        $response = [
            'status' => 400,
        ];
        return response()->json($response);
    }
    public function orderPrize(Request $request){
        $user = User::where('token', $request->token)->first();
        $user->ordered_point= $request->ordered_point;
        $user->pinned=1;
        $user->points=($user->points)-($request->ordered_point);
        $user->update();        
        $response = [
            'status' => 200,
        ];
        return response()->json($response);        
    }
    public function confirmPrize(Request $request){
        $user = User::where('token', $request->token)->first();
        $user->ordered_point= 0;
        $user->pinned=0;
        $user->update();        
        $response = [
            'status' => 200,
        ];
        return response()->json([$response, 'message'=>"the prize s confirmed"]);        
    }

    public function getAllUsers(){
        $users= User::all();
        return response()->json($users);
    }

    public function getnotif($token){
        $user = User::where('token', $token)->first();
        if($user->noti){
            $response = [
                'code' => $user->code,
            ];
            $user->noti=0;
            return response()->json(['status'=>200, 'message'=>"user have new notification", 'data'=> $response]);
        }
        $response=null;
        return response()->json(['status'=>310, 'message'=>"user have not any new notification", 'data'=> $response]);
        
    }

}
