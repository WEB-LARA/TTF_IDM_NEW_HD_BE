<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysMapSupplier extends Model
{
    use HasFactory;
    protected $table = 'sys_mapp_supp';

    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'USER_ID',
        'SUPP_SITE_CODE',
        'BRANCH_CODE',
        'DATE',
        'STATUS'
    ];

    public function deleteMapSuppByUserID($user_id){
        $data = SysMapSupplier::where('USER_ID',$user_id)->delete();

        if($data){
            return 1;
        }else{
            return 0;
        }
    }

    public function getSupplierByUserId($user_id){
        $getData = SysMapSupplier::from('sys_mapp_supp as a')->where('USER_ID',$user_id)
                   ->SELECT('SUPP_SITE_CODE','BRANCH_CODE')
                   ->selectRaw('SELECT 
                                    b.SUPP_SITE_ALT_NAME
                                FROM
                                    sys_supp_site b
                                WHERE
                                    b.SUPP_SITE_CODE = a.SUPP_SITE_CODE
                                        AND b.SUPP_BRANCH_CODE = a.BRANCH_CODE NAMA_SUPPLIER')
                   ->selectRaw('SELECT 
                                    COUNT(*)
                                FROM
                                    ttf_data_bpb c
                                WHERE
                                    c.VENDOR_SITE_CODE = a.SUPP_SITE_CODE
                                        AND c.BRANCH_CODE = a.BRANCH_CODE JUMLAH_BPB')
                   ->selectRaw('SELECT 
                                    b.SUPP_PKP_NUM
                                FROM
                                    sys_supp_site b
                                WHERE
                                    b.SUPP_SITE_CODE = a.SUPP_SITE_CODE
                                        AND b.SUPP_BRANCH_CODE = a.BRANCH_CODE NOMOR_NPWP')
                   ->selectRaw('SELECT 
                                    b.SUPP_PKP_ADDR1
                                FROM
                                    sys_supp_site b
                                WHERE
                                    b.SUPP_SITE_CODE = a.SUPP_SITE_CODE
                                        AND b.SUPP_BRANCH_CODE = a.BRANCH_CODE ALAMAT_SUPPLIER')
                    ->get();
        return $getData;
    }
}
