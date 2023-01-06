<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TtfTmpTable extends Model
{
    use HasFactory;

    protected $table='ttf_tmp_table';
    public $timestamps = false;
    protected $primaryKey = 'ID';


    public function saveToTmpTable($fp_type,$no_fp,$supp_site_id,$branch_code,$fp_date,$dpp_fp,$tax_fp,$data_bpb,$scan_flag){
        $session_id = session()->getId();
        print_r($session_id);
            // try{
            //     DB::transaction(function () use ($request,$user){
            //         $tmpTable = TtfTmpTable::create([
            //             'USERNAME' => $request->username,
            //             'USER_EMAIL' => $request->email,
            //             'RESET_FLAG' => $request->reset_flag,
            //             'PASSWORD' => Hash::make($request->password),
            //             'ACTIVE_FLAG' => $request->active_flag,
            //             'CREATION_DATE' => date('Y-m-d')
            //         ]);
            //         foreach($request->list_supplier as $a){
            //             // print_r();
            //             $sys_map_customer = SysMapSupplier::create([
            //                 'USER_ID' => $user->ID_USER,
            //                 'SUPP_SITE_CODE' =>$a['supp_site_code'],
            //                 'BRANCH_CODE' =>  $a['supp_branch_code'],
            //                 'STATUS' => 'Y',
            //                 'TRANSFER_FLAG' => 'Y'
            //             ]);
            //         }
    
            //     },5);
            // }catch (\Exception $e) {

            //     return $e->getMessage();
            // }
    }
}
