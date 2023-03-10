<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\SysUser;
use App\Models\SysMapSupplier;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login','register']]);
    // }

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
            ],200);

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
        ],200);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ],200);
    }

    public function refresh()
    {
        $user = Auth::id();
        $sys_user = new  SysUser();
        $dataUser =  $sys_user->getDataUser($user);
        return response()->json([
            'status' => 'success',
            'user' => $dataUser,
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ],200);
    }

    public function createUser(Request $request){
        $user = new SysUser();
        $checkUsername = $user->checkAvailableUsername($request->username);
        if($checkUsername == 0){
            
            try{
                DB::transaction(function () use ($request,$user){
                    $user = SysUser::create([
                        'USERNAME' => $request->username,
                        'USER_EMAIL' => $request->email,
                        'RESET_FLAG' => $request->reset_flag,
                        'PASSWORD' => Hash::make($request->password),
                        'ACTIVE_FLAG' => $request->active_flag,
                        'USER_ROLE' => $request->role,
                        'CREATION_DATE' => date('Y-m-d')
                    ]);
                    foreach($request->list_supplier as $a){
                        // print_r();
                        $sys_map_customer = SysMapSupplier::create([
                            'USER_ID' => $user->ID_USER,
                            'SUPP_SITE_CODE' =>$a['supp_site_code'],
                            'BRANCH_CODE' =>  $a['supp_branch_code'],
                            'STATUS' => 'Y',
                            'TRANSFER_FLAG' => 'Y'
                        ]);
                    }
    
                },5);
            }catch (\Exception $e) {

                return $e->getMessage();
            }

    
            if($user){
                return response()->json([
                    'status' => 'success',
                    'message' => 'User Berhasil dibuat!'
                ],200);
            }else{
                return response()->json([
                    'status' => 'gagal',
                    'message' => 'User Gagal dibuat!'
                ],400);
            }
        }else{
            return response()->json([
                'status' => 'gagal',
                'message' => 'Duplicate Username'
            ],400);
        }
    }

    public function updateEmailAndPasswordperUser(Request $request){
        $sys_usser = new SysUser();

        if($request->password){
            try{
                DB::transaction(function () use ($request){
                    $user = SysUser::where('ID_USER',$request->user_id)->update([
                        'USER_EMAIL' => $request->email,
                        'PASSWORD' => Hash::make($request->password),
                        'LAST_UPDATED_DATE' => date('Y-m-d')
                    ]);    
                },5);
                return response()->json([
                    'status' => 'success',
                    'message' => 'User Berhasil diedit!'
                ],200);
            }catch (\Exception $e) {

                return $e->getMessage();
            }
        }else{
            try{
                DB::transaction(function () use ($request){
                    $user = SysUser::where('ID_USER',$request->user_id)->update([
                        'USER_EMAIL' => $request->email,
                        'LAST_UPDATED_DATE' => date('Y-m-d')
                    ]);    
                },5);
                return response()->json([
                    'status' => 'success',
                    'message' => 'User Berhasil diedit!'
                ],200);
            }catch (\Exception $e) {

                return $e->getMessage();
            }
        }
    }
    public function updateUser(Request $request){
        // $user = SysUser::find($request->user_id);
        $sys_user = new SysUser();
        $sys_map_supplier = new SysMapSupplier();
        $getOldUsername = $sys_user->getOldUsernameByUserId($request->user_id);
        $checkAvailableUsername = $sys_user->checkAvailableUsernameEdit($getOldUsername[0]->USERNAME,$request->username);
        if($checkAvailableUsername == 0){
            if($request->password){
                DB::transaction(function () use ($request,$sys_map_supplier){
                    $user = SysUser::where('ID_USER',$request->user_id)->update([
                        'USERNAME' => $request->username,
                        'USER_EMAIL' => $request->email,
                        'RESET_FLAG' => $request->reset_flag,
                        'PASSWORD' => Hash::make($request->password),
                        'ACTIVE_FLAG' => $request->active_flag,
                        'USER_ROLE' => $request->role,
                        'LAST_UPDATED_DATE' => date('Y-m-d')
                    ]);

                    $deleteMappSupp = $sys_map_supplier->deleteMapSuppByUserID($request->user_id);

                    if($request->list_supplier){
                        foreach($request->list_supplier as $a){
                            // print_r();
                            $sys_map_customer = SysMapSupplier::create([
                                'USER_ID' => $request->user_id,
                                'SUPP_SITE_CODE' =>$a['supp_site_code'],
                                'BRANCH_CODE' =>  $a['supp_branch_code'],
                                'STATUS' => 'Y',
                                'TRANSFER_FLAG' => 'Y'
                            ]);
                        }
                    }
    
                },5);
                // $user->USERNAME = $request->username;
                // $user->USER_EMAIL = $request->email;
                // $user->RESET_FLAG = $request->reset_flag;
                // $user->PASSWORD = Hash::make($request->password);
                // $user->ACTIVE_FLAG = $request->active_flag;
            }else{
                DB::transaction(function () use ($request,$sys_map_supplier){
                    $user = SysUser::where('ID_USER',$request->user_id)->update([
                        'USERNAME' => $request->username,
                        'USER_EMAIL' => $request->email,
                        'RESET_FLAG' => $request->reset_flag,
                        'ACTIVE_FLAG' => $request->active_flag,
                        'USER_ROLE' => $request->role,
                        'LAST_UPDATED_DATE' => date('Y-m-d')
                    ]);
    
                },5);

                    $deleteMappSupp = $sys_map_supplier->deleteMapSuppByUserID($request->user_id);

                    if($request->list_supplier){
                        foreach($request->list_supplier as $a){
                            // print_r();
                            $sys_map_customer = SysMapSupplier::create([
                                'USER_ID' => $request->user_id,
                                'SUPP_SITE_CODE' =>$a['supp_site_code'],
                                'BRANCH_CODE' =>  $a['supp_branch_code'],
                                'STATUS' => 'Y',
                                'TRANSFER_FLAG' => 'Y'
                            ]);
                        }
                    }
                // $user->USERNAME = $request->username;
                // $user->USER_EMAIL = $request->email;
                // $user->RESET_FLAG = $request->reset_flag;
                // $user->ACTIVE_FLAG = $request->active_flag;
            }
        }else{
            return response()->json([
                'status' => 'gagal',
                'message' => 'Duplicate Username'
            ],400);
        }
            return response()->json([
                'status' => 'success',
                'message' => 'User Berhasil diedit!'
            ],200);
        // if($user->save()){
        //     return response()->json([
        //         'status' => 'success',
        //         'message' => 'User Berhasil diedit!'
        //     ],200);
        // }else{
        //     return response()->json([
        //         'status' => 'gagal',
        //         'message' => 'User Gagal diedit!'
        //     ],400);
        // }
    }

    public function getDataForInquiryUser(Request $request){
        $sys_user = new SysUser();

        $getData = $sys_user->getDataForInquiryUser($request->offset,$request->limit,$request->search);

        if($getData){
            return response()->json([
                'status' => 'success',
                'message' => 'List User',
                'count' => $getData['count'],
                'data' => $getData['data']
            ],200);
        }else{
            return response()->json([
                'status' => 'success',
                'message' => 'Gagal Mengambil Data Inquiry User!',
            ],400);
        }
    }

}
