<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TtfTmpTable extends Model
{
    use HasFactory;

    protected $table='ttf_tmp_table';
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $fillable = [
        'SEQ_NUM',
        'FP_TYPE',
        'SUPP_SITE',
        'CABANG',
        'NO_FP',
        'NO_NPWP',
        'FP_DATE',
        'FP_DPP',
        'FP_TAX',
        'BPB_NUM',
        'BPB_DATE',
        'BPB_AMOUNT',
        'BPB_PPN',
        'SESS_ID',
        'SCAN_FLAG'
    ];

    // public function saveToTmpTable($fp_type,$no_fp,$supp_site_id,$branch_code,$fp_date,$dpp_fp,$tax_fp,$data_bpb,$scan_flag){
    //     $session_id = session()->getId();
    //         try{
    //             DB::transaction(function () use ($fp_type,$no_fp,$supp_site_id,$branch_code,$fp_date,$dpp_fp,$tax_fp,$data_bpb,$scan_flag){
    //                 foreach($data_bpb as $a){
    //                     $tmpTable = TtfTmpTable::create([
    //                         'SEQ_NUM2' => 1,
    //                         'FP_TYPE' => $fp_type,
    //                         'SUPP_SITE' => '121',
    //                         'CABANG' => $branch_code,
    //                         'NO_FP' => $no_fp,
    //                         'NO_NPWP' => 'teest npwp',
    //                         'FP_DATE' => $tanggal_fp,
    //                         'FP_DPP' => $dpp_fp,
    //                         'FP_TAX' => $tax_fp,
    //                         'BPB_NUM' => $a['bpb_num'],
    //                         'BPB_DATE' => $a['bpb_date'],
    //                         'BPB_AMOUNT' => $a['bpb_amount'],
    //                         'BPB_PPN' => $a['bpb_ppn'],
    //                         'SESS_ID' => $session_id,
    //                         'SCAN_FLAG' => $scan_flag
    //                     ]);
    //                 }
    
    //             },5);
    //         }catch (\Exception $e) {

    //             return $e->getMessage();
    //         }
    // }

    public function getDataTtfTmpBYSessionId($supp_site_code){
        $getData = TtfTmpTable::where('SUPP_SITE',$supp_site_code)->get();

        return $getData;
    }

    public function getDataTTfTmpForInsertTTf($supp_site_code,$branch_code){
        $getData = TtfTmpTable::where('SUPP_SITE',$supp_site_code)->where('CABANG',$branch_code)->groupBy('SUPP_SITE')->select('SUPP_SITE','CABANG','FP_TYPE')->get();

        return $getData;
    }

    public function getDataTTFTmpBPB($supp_site_code,$branch_code,$no_fp){
        $getData = TtfTmpTable::where('SUPP_SITE',$supp_site_code)
                    ->where('CABANG',$branch_code)
                    ->where('NO_FP',$no_fp)
                    ->select('BPB_NUM','BPB_DATE','BPB_AMOUNT')
                    ->selectRaw("(SELECT 
                                      tdb.BPB_ID
                                  FROM
                                      ttf_data_bpb tdb
                                  WHERE
                                      tdb.BPB_NUMBER = BPB_NUM AND tdb.BRANCH_CODE = ?
                                          AND tdb.VENDOR_SITE_CODE = ?
                                          AND tdb.VENDOR_SITE_ID IS NOT NULL
                                          AND tdb.VENDOR_SITE_ID <> 0
                                  ) as BPB_ID",[$branch_code, $supp_site_code])
                    ->get();

        return $getData;
    }

    public function getDataTTFTmpFP($supp_site_code,$branch_code){
        $getData = TtfTmpTable::where('SUPP_SITE',$supp_site_code)->where('CABANG',$branch_code)->groupBy('NO_FP')->select('NO_FP','FP_TYPE','NO_NPWP','FP_DATE','FP_DPP','FP_TAX','SCAN_FLAG')->get();

        return $getData;
    }

    public function getDataTmpTtfBySessId($session_id){
        $getData = DB::select("SELECT 
                                    CASE
                                        WHEN FP_TYPE = 1 THEN 'STANDARD'
                                        ELSE 'TANPA FAKTUR PAJAK'
                                    END FP_TYPE,
                                    NO_FP,
                                    DATE_FORMAT(FP_DATE, '%d-%b-%Y') TANGGAL_FP,
                                    FP_DPP,
                                    FP_TAX,
                                    COUNT(BPB_NUM) AS JUMLAH_BPB,
                                    SUM(BPB_AMOUNT) AS JUMLAH_DPP_BPB,
                                    SUM(BPB_PPN) AS JUMLAH_PPN_BPB,
                                    (FP_DPP - SUM(BPB_AMOUNT)) SEL_DPP,
                                    (FP_TAX - SUM(BPB_PPN)) SEL_PPN
                                FROM
                                    ttf_tmp_table
                                WHERE
                                    SESS_ID = ?
                                GROUP BY NO_FP",[$session_id]);
        return $getData;
    }

    public function getDataDetailBPBperFP($supp_site_code,$branch_code,$no_fp){
        $getData = TtfTmpTable::where('SUPP_SITE',$supp_site_code)->where('CABANG',$branch_code)->where('NO_FP',$no_fp)->select('BPB_NUM','BPB_DATE','BPB_AMOUNT','BPB_PPN')->get();

        return $getData;
    }

    public function deleteTmpTableBySiteCodeAndBranch($session_id){
        $delete = TtfTmpTable::where('SESS_ID',$session_id)->delete();

        if($delete){
            return 1;
        }else{
            return 0;
        }
    }
}
