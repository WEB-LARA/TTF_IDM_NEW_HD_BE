<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\SysUser;


class LoginController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('username', 'password');
        // print_r($credentials);
        $token = Auth::attempt($credentials);
        // print_r("token =".$token);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::id();
        $sys_user = new  SysUser();
        $dataUser =  $sys_user->getDataUser($user);
        return response()->json([
                'status' => 'success',
                'user' => $dataUser,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);

    }

    public function register(Request $request){
        // print_r($request->all());
        // $request->validate([
        //     'USERNAME' => 'required|string|max:255',
        //     'USER_EMAIL' => 'required|string|email|max:255|unique:sys_user',
        //     'PASSWORD' => 'required|string|min:6',
        // ]);
        $user = SysUser::create([
            'USERNAME' => $request->username,
            'USER_EMAIL' => $request->email,
            'PASSWORD' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function createUser(Request $request){
        $user = SysUser::create([
            'USERNAME' => $request->username,
            'USER_EMAIL' => $request->email,
            'RESET_FLAG' => $request->reset_flag,
            'PASSWORD' => Hash::make($request->password),
            'ACTIVE_FLAG' => $request->active_flag
        ]);

        if($user){
            return response()->json([
                'status' => 'success',
                'message' => 'User Berhasil dibuat!'
            ]);
        }else{
            return response()->json([
                'status' => 'gagal',
                'message' => 'User Gagal dibuat!'
            ]);
        }
    }

    public function updateUser(Request $request){
        $user = SysUser::find($request->user_id);
        if($request->password){
            $user->USERNAME = $request->username;
            $user->USER_EMAIL = $request->email;
            $user->RESET_FLAG = $request->reset_flag;
            $user->PASSWORD = Hash::make($request->password);
            $user->ACTIVE_FLAG = $request->active_flag;
        }else{
            $user->USERNAME = $request->username;
            $user->USER_EMAIL = $request->email;
            $user->RESET_FLAG = $request->reset_flag;
            $user->ACTIVE_FLAG = $request->active_flag;
        }

        if($user->save()){
            return response()->json([
                'status' => 'success',
                'message' => 'User Berhasil diedit!'
            ]);
        }else{
            return response()->json([
                'status' => 'gagal',
                'message' => 'User Gagal diedit!'
            ]);
        }
    }

}
