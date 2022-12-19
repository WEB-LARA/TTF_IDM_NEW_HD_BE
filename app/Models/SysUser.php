<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

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
        $getData = SysUser::where('ID_USER',$id)->select('ID_USER','USERNAME','USER_EMAIL','RESET_FLAG','ACTIVE_FLAG')->get();

        return $getData;
    }

    public function getDataForInquiryUser(){
        $getData = SysUser::select('ID_USER','USERNAME','USER_EMAIL','RESET_FLAG','ACTIVE_FLAG')->get();

        return $getData;
    }

    public function checkAvailableUsername($username){
        $getData = SysUser::select('USERNAME')->where('USERNAME',$username)->get();
        $num_rows = count($getData);
        return $num_rows;
    }

    public function checkAvailableUsernameEdit($username_new,$username_old){
        $getData = SysUser::select('USERNAMEE')->where('USERNAME',$username)->where('USERNAME','!=',$username)->get();
        $num_rows = count($getData);
        return $num_rows;
    }

    public function getOldUsernameByUserId($user_id){
        $getData = SysUser::select('USERNAME')->where('ID_USER',$user_id)->get()->USERNAME;
        return $getData;
    }
}
