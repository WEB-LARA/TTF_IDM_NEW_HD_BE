<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrepopulatedFp extends Model
{
    use HasFactory;
    protected $table = 'prepopulated_fp';

    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'USED_FLAG'
    ];

    public function updatePrepopulatedFP($nomor_fp,$status){
        $data = PrepopulatedFp::where('NOMOR_FAKTUR',$nomor_fp)->update([
                'USED_FLAG' => $status
        ]);

        if($data){
            return 1;
        }else{
            return 0;
        }
    }

    public function getPrepopulatedFpByNpwp($npwp,$supp_site_code,$branch_code,$session_id,$offset,$limit){
        $skip = ($limit*$offset) - $limit;
        $data = PrepopulatedFp::where('NPWP_PENJUAL',$npwp)->where('USED_FLAG','N')->whereRaw('NOMOR_FAKTUR NOT IN (SELECT 
                                           NO_FP
                                       FROM
                                           ttf_tmp_table
                                       WHERE
                                           SUPP_SITE = ? AND CABANG = ?
                                               AND SESS_ID = ?)',[$supp_site_code,$branch_code,$session_id]);
        $data_count = $data->count();
        $data = $data->skip($skip)->take($limit)->get();
        $nomor = $skip+1;
        $dataArray = array();
        $i=0;
        foreach ($data as $a){
            // print_r($a->FP_TYPE);
            // $dataFp = $ttf_fp->getFpByTtfId($request->ttf_id);
            $dataArray[$i]['NO'] = $nomor;
            $dataArray[$i]['ID'] = $a->ID;
            $dataArray[$i]['FG_PENGGANTI'] = $a->FG_PENGGANTI;
            $dataArray[$i]['JUMLAH_DPP'] = $a->JUMLAH_DPP;
            $dataArray[$i]['JUMLAH_PPN'] = $a->JUMLAH_PPN;
            $dataArray[$i]['JUMLAH_PPN_BM'] = $a->JUMLAH_PPN_BM;
            $dataArray[$i]['KD_JENIS_TRANSAKSI'] = $a->KD_JENIS_TRANSAKSI;
            $dataArray[$i]['MASA_PAJAK'] = $a->MASA_PAJAK;
            $dataArray[$i]['NAMA_PEMBELI'] = $a->NAMA_PEMBELI;
            $dataArray[$i]['NAMA_PENJUAL'] = $a->NAMA_PENJUAL;
            $dataArray[$i]['NOMOR_FAKTUR'] = $a->NOMOR_FAKTUR;
            $dataArray[$i]['NPWP_PEMBELI'] = $a->NPWP_PEMBELI;
            $dataArray[$i]['NPWP_PENJUAL'] = $a->NPWP_PENJUAL;
            $dataArray[$i]['TAHUN_PAJAK'] = $a->TAHUN_PAJAK;
            $dataArray[$i]['TANGGAL_FAKTUR'] = $a->TANGGAL_FAKTUR;
            $dataArray[$i]['EXISTED'] = $a->EXISTED;
            $dataArray[$i]['USED_FLAG'] = $a->USED_FLAG;
            $i++;
            $nomor++;
        }
        $return_data['count']=$data_count;
        $return_data['data']=$dataArray;

        return $data;
    }
    public function getPrepopulatedFpByNoFpAndNpwp($npwp,$no_faktur){
        $data = PrepopulatedFp::where('NPWP_PENJUAL',$npwp)->whereRaw('SUBSTR(nomor_faktur,5,18) = ?',[$no_faktur])->count();

        return $data;
    }
    public function getFpByNoFpAndNpwp($npwp,$no_faktur){
        $data = PrepopulatedFp::where('NPWP_PENJUAL',$npwp)->whereRaw('SUBSTR(nomor_faktur,5,18) = ?',[$no_faktur])->select('NOMOR_FAKTUR')->first();

        return $data;
    }
    public function checkPrepopulatedFPByNoFakturAndUsedFlag($no_faktur){
        $data = PrepopulatedFp::where('NOMOR_FAKTUR',$no_faktur)->where('USED_FLAG','N')->count();
        return $data;
    }
}
