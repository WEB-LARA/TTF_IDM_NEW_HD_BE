<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysSuppSite extends Model
{
    use HasFactory;
    protected $table = 'sys_supp_site';

    protected $primaryKey = 'SUPP_SITE_ID';


    public function getSiteCodeAndNpwp($supp_site_id,$branch_code){
        $getData = SysSuppSite::where('SUPP_SITE_ID',$supp_site_id)->where('SUPP_BRANCH_CODE',$branch_code)->select('SUPP_SITE_CODE','SUPP_PKP_NUM')->first();

        return $getData;
    }
}
