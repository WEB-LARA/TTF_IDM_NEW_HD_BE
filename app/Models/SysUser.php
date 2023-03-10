<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\DB;

class SysUser extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table='sys_user';
    public $timestamps = false;
    protected $primaryKey = 'ID_USER';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'USERNAME',
        'USER_EMAIL',
        'PASSWORD',
        'USER_ROLE',
        'CREATION_DATE',
        'LAST_UPDATE_DATE'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getAuthPassword()
    {
        return $this->PASSWORD;
    }

    public function getDataUser($id){
        $getData = SysUser::where('ID_USER',$id)->select('ID_USER','USERNAME','USER_EMAIL','RESET_FLAG','ACTIVE_FLAG','USER_ROLE')->get();

        return $getData;
    }

    public function getAllDataUser($role_id,$user_id,$branch_code){
        $getData = SysUser::join('sys_mapp_supp', 'sys_mapp_supp.USER_ID', '=', 'sys_user.ID_USER')->where('BRANCH_CODE',$branch_code)->select('ID_USER','USERNAME','USER_EMAIL','RESET_FLAG','ACTIVE_FLAG','USER_ROLE');

        if($role_id != 1){
            $getData = $getData->where('ID_USER',$user_id);
        }
        $getData = $getData->groupBy('ID_USER')->get();
        return $getData;
    }

    public function getDataForInquiryUser($offset,$limit,$search){
        $skip = ($limit*$offset) - $limit;
        $where = '';
        $whereExtra = '';
        if($search){
            $where = " WHERE ";
            $whereExtra = " (USERNAME LIKE '%$search%' OR  USER_EMAIL LIKE '%$search%') ";
        }
        $getDataCount = DB::select('SELECT 
                                      ID_USER,
                                      USERNAME,
                                      USER_EMAIL,
                                      SUPP_ID,
                                      RESET_FLAG,
                                      USER_ROLE,
                                      ACTIVE_FLAG,
                                      CREATION_DATE,
                                      LAST_UPDATED_DATE,
                                      (SELECT 
                                              COUNT(*)
                                          FROM
                                              sys_mapp_supp
                                          WHERE
                                              USER_ID = ID_USER) JUMLAH_SUPPLIER
                                  FROM
                                      sys_user'.$where.$whereExtra);
        $getData = DB::select('SELECT 
                                      ID_USER,
                                      USERNAME,
                                      USER_EMAIL,
                                      SUPP_ID,
                                      RESET_FLAG,
                                      USER_ROLE,
                                      ACTIVE_FLAG,
                                      CREATION_DATE,
                                      LAST_UPDATED_DATE,
                                      (SELECT 
                                              COUNT(*)
                                          FROM
                                              sys_mapp_supp
                                          WHERE
                                              USER_ID = ID_USER) JUMLAH_SUPPLIER
                                  FROM
                                      sys_user '.$where.$whereExtra.'LIMIT ? OFFSET ?',[$limit,$skip]);
        $return_data = array();
        $data_count = count($getDataCount);
        $dataArray = array();
        $nomor = $skip+1;
        $i=0;
        foreach ($getData as $a){
            // print_r($a->FP_TYPE);
            // $dataFp = $ttf_fp->getFpByTtfId($request->ttf_id);
            $dataArray[$i]['NO'] = $nomor;
            $dataArray[$i]['ID_USER'] = $a->ID_USER;
            $dataArray[$i]['USERNAME'] = $a->USERNAME;
            $dataArray[$i]['USER_EMAIL'] = $a->USER_EMAIL;
            $dataArray[$i]['SUPP_ID'] = $a->SUPP_ID;
            $dataArray[$i]['RESET_FLAG'] = $a->RESET_FLAG;
            $dataArray[$i]['USER_ROLE'] = $a->USER_ROLE;
            $dataArray[$i]['ACTIVE_FLAG'] = $a->ACTIVE_FLAG;
            $dataArray[$i]['CREATION_DATE'] = $a->CREATION_DATE;
            $dataArray[$i]['LAST_UPDATED_DATE'] = $a->LAST_UPDATED_DATE;
            $dataArray[$i]['JUMLAH_SUPPLIER'] = $a->JUMLAH_SUPPLIER;
            $i++;
            $nomor++;
        }
        $return_data['count']=$data_count;
        $return_data['data']=$dataArray;
        return $return_data;
    }

    public function checkAvailableUsername($username){
        $getData = SysUser::select('USERNAME')->where('USERNAME',$username)->get();
        $num_rows = count($getData);
        return $num_rows;
    }

    public function checkAvailableUsernameEdit($username_old,$username_new){
        $getData = SysUser::select('USERNAME')->where('USERNAME',$username_new)->where('USERNAME','!=',$username_old)->get();
        $num_rows = count($getData);
        return $num_rows;
    }

    public function getOldUsernameByUserId($user_id){
        $getData = SysUser::select('USERNAME')->where('ID_USER',$user_id)->get();
        return $getData;
    }

    public function getDataUsername(){
        $getData = SysUser::select('USERNAME','ID_USER')->get();
        return $getData;
    }
    public function getDataForInquiryTtfDashboard($id_user,$branch_code){
        $data = SysUser::join('sys_mapp_supp', 'sys_mapp_supp.USER_ID', '=', 'sys_user.ID_USER')
        ->join('ttf_headers', 'ttf_headers.VENDOR_SITE_CODE', '=', 'sys_mapp_supp.SUPP_SITE_CODE')
        ->whereColumn('ttf_headers.BRANCH_CODE','=','sys_mapp_supp.BRANCH_CODE')
        ->select('ttf_headers.TTF_NUM','ttf_headers.BRANCH_CODE','ttf_headers.SELISIH_DPP','ttf_headers.SELISIH_TAX','ttf_headers.CREATION_DATE')
        ->selectRaw("CASE
                          WHEN ttf_headers.TTF_STATUS = '' THEN 'DRAFT'
                          WHEN ttf_headers.TTF_STATUS = 'C' THEN 'CANCEL'
                          WHEN ttf_headers.TTF_STATUS = 'E' THEN 'EXPIRED'
                          WHEN ttf_headers.TTF_STATUS = 'R' THEN 'REJECTED'
                          WHEN ttf_headers.TTF_STATUS = 'S' THEN 'SUBMITTED'
                          WHEN ttf_headers.TTF_STATUS = 'V' THEN 'VALIDATED'
                     END AS TTF_STATUS");

        if($id_user){
            $data = $data->where('sys_user.ID_USER',$id_user);
        }
        if($branch_code){
            $data = $data->where('ttf_headers.BRANCH_CODE',$branch_code);
        }
        $data = $data->orderBy('ttf_headers.TTF_ID','DESC')->take(10)->get();
        
        return $data;
    }
    public function getDataForInquiryTtfDashboardUser($id_user,$branch_code){
        $data = SysUser::join('sys_mapp_supp', 'sys_mapp_supp.USER_ID', '=', 'sys_user.ID_USER')
        ->join('ttf_headers', 'ttf_headers.VENDOR_SITE_CODE', '=', 'sys_mapp_supp.SUPP_SITE_CODE')
        ->whereColumn('ttf_headers.BRANCH_CODE','=','sys_mapp_supp.BRANCH_CODE')
        ->where('sys_user.ID_USER',$id_user)
        ->where('ttf_headers.BRANCH_CODE',$branch_code)
        ->select('ttf_headers.TTF_NUM','ttf_headers.BRANCH_CODE','ttf_headers.SELISIH_DPP','ttf_headers.SELISIH_TAX','ttf_headers.CREATION_DATE')
        ->selectRaw("CASE
                          WHEN ttf_headers.TTF_STATUS = '' THEN 'DRAFT'
                          WHEN ttf_headers.TTF_STATUS = 'C' THEN 'CANCEL'
                          WHEN ttf_headers.TTF_STATUS = 'E' THEN 'EXPIRED'
                          WHEN ttf_headers.TTF_STATUS = 'R' THEN 'REJECTED'
                          WHEN ttf_headers.TTF_STATUS = 'S' THEN 'SUBMITTED'
                          WHEN ttf_headers.TTF_STATUS = 'V' THEN 'VALIDATED'
                     END AS TTF_STATUS")
        ->orderBy('ttf_headers.TTF_ID','DESC')->take(10)->get();

        return $data;
    }
    
}
