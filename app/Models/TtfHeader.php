<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TtfHeader extends Model
{
    use HasFactory;

    protected $table='ttf_headers';
    public $timestamps = false;
    protected $primaryKey = 'TTF_ID';
    protected $fillable = [
        'BRANCH_CODE',
        'VENDOR_SITE_CODE',
        'TTF_NUM',
        'TTF_DATE',
        'TTF_TYPE',
        'TTF_STATUS',
        'TTF_RETURN_DATE',
        'TTF_GIRO_DATE',
        'ORG_ID',
        'SOURCE',
        'REVIEWED_BY',
        'REVIEWED_DATE',
        'CREATED_BY',
        'CREATION_DATE',
        'LAST_UPDATE_DATE',
        'LAST_UPDATE_BY',
        'MEMO_NUM',
        'JUMLAH_FP',
        'SUM_DPP_FP',
        'SUM_TAX_FP',
        'JUMLAH_BPB',
        'SUM_DPP_BPB',
        'SUM_TAX_BPB',
        'SELISIH_DPP',
        'SELISIH_TAX',
        'FLAG_GO',
        'FLAG_PPN'
    ];


    public function getDataInquiryTTF($user_id,$offset,$limit,$search,$start_date,$end_date,$status_ttf){
        $skip = ($limit*$offset) - $limit;
        // $getData = TtfHeader::where('CREATED_BY',$user_id)
        //                      ->select('TTF_ID','TTF_NUM','BRANCH_CODE','TTF_DATE','REVIEWED_DATE','VENDOR_SITE_CODE')
        //                      ->selectRaw('CASE
        //                                       WHEN TTF_STATUS = \'\' THEN \'DRAFT\'
        //                                   END AS TTF_STATUS')
        //                      ->get();
        $where = '';
        $whereExtra = "";
        if($search){
            if($where == ''){
                $where = " WHERE ";
                $whereExtra .= " (asd.TTF_NUM LIKE '%$search%' OR asd.BRANCH_NAME LIKE '%$search%' OR asd.VENDOR_SITE_NAME LIKE '%$search%') ";
            }else{
                $whereExtra .= " AND (asd.TTF_ID LIKE '%$search%' OR asd.BRANCH_NAME LIKE '%$search%' OR asd.VENDOR_SITE_NAME LIKE '%$search%') ";
            }
        }
        if($start_date && $end_date){
            if($where == ''){
                $where = " WHERE ";
                $whereExtra .= " asd.TTF_DATE >= '$start_date' AND asd.TTF_DATE <= '$end_date' ";
            }else{
                $whereExtra .= " AND asd.TTF_DATE >= '$start_date' AND asd.TTF_DATE <= '$end_date' ";
            }
        }
        if($status_ttf){
            if($status_ttf == 'ALL'){
                
            }else{
                if($where == ''){
                    $where = " WHERE ";
                    $whereExtra .= " asd.TTF_STATUS = '$status_ttf' ";
                }else{
                    $whereExtra .= " AND asd.TTF_STATUS = '$status_ttf' ";
                }
            }
        }
        $getData = DB::select("SELECT 
                                   asd.TTF_ID,
                                   asd.TTF_NUM,
                                   asd.TTF_STATUS,
                                   asd.BRANCH_CODE,
                                   asd.BRANCH_NAME,
                                   asd.TTF_DATE,
                                   asd.REVIEWED_DATE,
                                   asd.VENDOR_SITE_CODE,
                                   asd.VENDOR_SITE_NAME,
                                   asd.JUMLAH_FP,
                                   asd.JUMLAH_DPP_FAKTUR,
                                   asd.JUMLAH_PPN_FAKTUR,
                                   asd.JUMLAH_BPB,
                                   asd.JUMLAH_DPP_BPB,
                                   asd.JUMLAH_TAX_BPB,
                                   (asd.JUMLAH_DPP_FAKTUR - asd.JUMLAH_DPP_BPB) AS SEL_DPP,
                                   (asd.JUMLAH_PPN_FAKTUR - asd.JUMLAH_TAX_BPB) AS SEL_PPN,
                                   asd.PATH_NOTTF
                               FROM
                                   (SELECT 
                                       a.TTF_ID,
                                           a.TTF_NUM,
                                           CASE
                                                WHEN a.TTF_STATUS = '' THEN 'DRAFT'
                                                WHEN a.TTF_STATUS = 'C' THEN 'CANCEL'
                                                WHEN a.TTF_STATUS = 'E' THEN 'EXPIRED'
                                                WHEN a.TTF_STATUS = 'R' THEN 'REJECTED'
                                                WHEN a.TTF_STATUS = 'S' THEN 'SUBMITTED'
                                                WHEN a.TTF_STATUS = 'V' THEN 'VALIDATED'
                                           END AS TTF_STATUS,
                                           a.BRANCH_CODE,
                                           (SELECT 
                                                   b.branch_name
                                               FROM
                                                   sys_ref_branch b
                                               WHERE
                                                   b.branch_code = a.BRANCH_CODE) BRANCH_NAME,
                                           a.TTF_DATE,
                                           a.REVIEWED_DATE,
                                           a.VENDOR_SITE_CODE,
                                           a.PATH_NOTTF,
                                           (SELECT 
                                                   b.SUPP_SITE_ALT_NAME
                                               FROM
                                                   sys_supp_site b
                                               WHERE
                                                   b.SUPP_SITE_CODE = a.VENDOR_SITE_CODE
                                                       AND b.SUPP_BRANCH_CODE = a.BRANCH_CODE) VENDOR_SITE_NAME,
                                           (SELECT 
                                                   COUNT(*)
                                               FROM
                                                   ttf_fp b
                                               WHERE
                                                   b.TTF_ID = a.TTF_ID) JUMLAH_FP,
                                           (SELECT 
                                                   SUM(b.FP_DPP_AMT)
                                               FROM
                                                   ttf_fp b
                                               WHERE
                                                   b.TTF_ID = a.TTF_ID) JUMLAH_DPP_FAKTUR,
                                           (SELECT 
                                                   SUM(b.FP_TAX_AMT)
                                               FROM
                                                   ttf_fp b
                                               WHERE
                                                   b.TTF_ID = a.TTF_ID) JUMLAH_PPN_FAKTUR,
                                           (SELECT 
                                                   COUNT(*)
                                               FROM
                                                   ttf_lines c
                                               WHERE
                                                   c.TTF_ID = a.TTF_ID) AS JUMLAH_BPB,
                                           (SELECT 
                                                   SUM(c.BPB_DPP)
                                               FROM
                                                   ttf_data_bpb c
                                               WHERE
                                                   c.BPB_ID IN (SELECT 
                                                           d.TTF_BPB_ID
                                                       FROM
                                                           ttf_lines d
                                                       WHERE
                                                           d.TTF_ID = a.TTF_ID)) AS JUMLAH_DPP_BPB,
                                           (SELECT 
                                                   SUM(c.BPB_TAX)
                                               FROM
                                                   ttf_data_bpb c
                                               WHERE
                                                   c.BPB_ID IN (SELECT 
                                                           d.TTF_BPB_ID
                                                       FROM
                                                           ttf_lines d
                                                       WHERE
                                                           d.TTF_ID = a.TTF_ID)) AS JUMLAH_TAX_BPB
                                   FROM
                                       ttf_headers a
                                   WHERE
                                       CREATED_BY = ?) asd $where $whereExtra",[$user_id]);
        $return_data = array();
        $data_count = count($getData);
        $dataArray = array();
        $getDataPage = DB::select("SELECT 
                                   asd.TTF_ID,
                                   asd.TTF_NUM,
                                   asd.TTF_STATUS,
                                   asd.BRANCH_CODE,
                                   asd.BRANCH_NAME,
                                   asd.TTF_DATE,
                                   asd.REVIEWED_DATE,
                                   asd.VENDOR_SITE_CODE,
                                   asd.VENDOR_SITE_NAME,
                                   asd.JUMLAH_FP,
                                   asd.JUMLAH_DPP_FAKTUR,
                                   asd.JUMLAH_PPN_FAKTUR,
                                   asd.JUMLAH_BPB,
                                   asd.JUMLAH_DPP_BPB,
                                   asd.JUMLAH_TAX_BPB,
                                   (asd.JUMLAH_DPP_FAKTUR - asd.JUMLAH_DPP_BPB) AS SEL_DPP,
                                   (asd.JUMLAH_PPN_FAKTUR - asd.JUMLAH_TAX_BPB) AS SEL_PPN,
                                   asd.PATH_NOTTF
                               FROM
                                   (SELECT 
                                       a.TTF_ID,
                                           a.TTF_NUM,
                                           CASE
                                                WHEN a.TTF_STATUS = '' THEN 'DRAFT'
                                                WHEN a.TTF_STATUS = 'C' THEN 'CANCEL'
                                                WHEN a.TTF_STATUS = 'E' THEN 'EXPIRED'
                                                WHEN a.TTF_STATUS = 'R' THEN 'REJECTED'
                                                WHEN a.TTF_STATUS = 'S' THEN 'SUBMITTED'
                                                WHEN a.TTF_STATUS = 'V' THEN 'VALIDATED'
                                           END AS TTF_STATUS,
                                           a.BRANCH_CODE,
                                           (SELECT 
                                                   b.branch_name
                                               FROM
                                                   sys_ref_branch b
                                               WHERE
                                                   b.branch_code = a.BRANCH_CODE) BRANCH_NAME,
                                           a.TTF_DATE,
                                           a.REVIEWED_DATE,
                                           a.VENDOR_SITE_CODE,
                                           a.PATH_NOTTF,
                                           (SELECT 
                                                   b.SUPP_SITE_ALT_NAME
                                               FROM
                                                   sys_supp_site b
                                               WHERE
                                                   b.SUPP_SITE_CODE = a.VENDOR_SITE_CODE
                                                       AND b.SUPP_BRANCH_CODE = a.BRANCH_CODE) VENDOR_SITE_NAME,
                                           (SELECT 
                                                   COUNT(*)
                                               FROM
                                                   ttf_fp b
                                               WHERE
                                                   b.TTF_ID = a.TTF_ID) JUMLAH_FP,
                                           (SELECT 
                                                   SUM(b.FP_DPP_AMT)
                                               FROM
                                                   ttf_fp b
                                               WHERE
                                                   b.TTF_ID = a.TTF_ID) JUMLAH_DPP_FAKTUR,
                                           (SELECT 
                                                   SUM(b.FP_TAX_AMT)
                                               FROM
                                                   ttf_fp b
                                               WHERE
                                                   b.TTF_ID = a.TTF_ID) JUMLAH_PPN_FAKTUR,
                                           (SELECT 
                                                   COUNT(*)
                                               FROM
                                                   ttf_lines c
                                               WHERE
                                                   c.TTF_ID = a.TTF_ID) AS JUMLAH_BPB,
                                           (SELECT 
                                                   SUM(c.BPB_DPP)
                                               FROM
                                                   ttf_data_bpb c
                                               WHERE
                                                   c.BPB_ID IN (SELECT 
                                                           d.TTF_BPB_ID
                                                       FROM
                                                           ttf_lines d
                                                       WHERE
                                                           d.TTF_ID = a.TTF_ID)) AS JUMLAH_DPP_BPB,
                                           (SELECT 
                                                   SUM(c.BPB_TAX)
                                               FROM
                                                   ttf_data_bpb c
                                               WHERE
                                                   c.BPB_ID IN (SELECT 
                                                           d.TTF_BPB_ID
                                                       FROM
                                                           ttf_lines d
                                                       WHERE
                                                           d.TTF_ID = a.TTF_ID)) AS JUMLAH_TAX_BPB
                                   FROM
                                       ttf_headers a
                                   WHERE
                                       CREATED_BY = ?) asd $where $whereExtra limit ? offset ?",[$user_id,$limit,$skip]);
        $nomor = $skip+1;
        $i=0;
        foreach ($getDataPage as $a){
            // print_r($a->FP_TYPE);
            // $dataFp = $ttf_fp->getFpByTtfId($request->ttf_id);
            $dataArray[$i]['NO'] = $nomor;
            $dataArray[$i]['TTF_ID'] = $a->TTF_ID;
            $dataArray[$i]['TTF_NUM'] = $a->TTF_NUM;
            $dataArray[$i]['TTF_STATUS'] = $a->TTF_STATUS;
            $dataArray[$i]['BRANCH_CODE'] = $a->BRANCH_CODE;
            $dataArray[$i]['BRANCH_NAME'] = $a->BRANCH_NAME;
            $dataArray[$i]['TTF_DATE'] = $a->TTF_DATE;
            $dataArray[$i]['REVIEWED_DATE'] = $a->REVIEWED_DATE;
            $dataArray[$i]['VENDOR_SITE_CODE'] = $a->VENDOR_SITE_CODE;
            $dataArray[$i]['VENDOR_SITE_NAME'] = $a->VENDOR_SITE_NAME;
            $dataArray[$i]['JUMLAH_FP'] = $a->JUMLAH_FP;
            $dataArray[$i]['JUMLAH_DPP_FAKTUR'] = $a->JUMLAH_DPP_FAKTUR;
            $dataArray[$i]['JUMLAH_PPN_FAKTUR'] = $a->JUMLAH_PPN_FAKTUR;
            $dataArray[$i]['JUMLAH_BPB'] = $a->JUMLAH_BPB;
            $dataArray[$i]['JUMLAH_DPP_BPB'] = $a->JUMLAH_DPP_BPB;
            $dataArray[$i]['JUMLAH_TAX_BPB'] = $a->JUMLAH_TAX_BPB;
            $i++;
            $nomor++;
        }              
        $return_data['count']=$data_count;
        $return_data['data']=$dataArray;                         
        return $return_data;
    }

    public function getDataInquiryDetailTTF($ttf_id){
        $getData = DB::select('SELECT 
                                   asd.JUMLAH_FP,
                                   asd.JUMLAH_DPP_FAKTUR,
                                   asd.JUMLAH_PPN_FAKTUR,
                                   asd.JUMLAH_BPB,
                                   asd.JUMLAH_DPP_BPB,
                                   asd.JUMLAH_TAX_BPB,
                                   (asd.JUMLAH_DPP_FAKTUR - asd.JUMLAH_DPP_BPB) AS SEL_DPP,
                                   (asd.JUMLAH_PPN_FAKTUR - asd.JUMLAH_TAX_BPB) AS SEL_PPN
                               FROM
                                   (SELECT 
                                       COUNT(*) JUMLAH_FP,
                                           SUM(FP_DPP_AMT) JUMLAH_DPP_FAKTUR,
                                           SUM(FP_TAX_AMT) JUMLAH_PPN_FAKTUR,
                                           (SELECT 
                                                   COUNT(*)
                                               FROM
                                                   ttf_lines
                                               WHERE
                                                   TTF_ID = ?) AS JUMLAH_BPB,
                                           (SELECT 
                                                   SUM(BPB_DPP)
                                               FROM
                                                   ttf_data_bpb
                                               WHERE
                                                   BPB_ID IN (SELECT 
                                                           TTF_BPB_ID
                                                       FROM
                                                           ttf_lines
                                                       WHERE
                                                           TTF_ID = ?)) AS JUMLAH_DPP_BPB,
                                           (SELECT 
                                                   SUM(BPB_TAX)
                                               FROM
                                                   ttf_data_bpb
                                               WHERE
                                                   BPB_ID IN (SELECT 
                                                           TTF_BPB_ID
                                                       FROM
                                                           ttf_lines
                                                       WHERE
                                                           TTF_ID = ?)) AS JUMLAH_TAX_BPB
                                   FROM
                                       ttf_fp
                                   WHERE
                                       TTF_ID = ?) asd',[$ttf_id,$ttf_id,$ttf_id,$ttf_id]);
        return $getData;
    }

    public function updateTtfInsert($ttf_list)
    {
        DB::transaction(function () use($ttf_list){
            $update = DB::select("UPDATE ttf_headers th set
		    					th.JUMLAH_FP = (select count(*) from ttf_fp tf where tf.TTF_ID=th.TTF_ID and tf.used_flag = 'Y'),
		    					th.SUM_DPP_FP = (select IFNULL(sum(tf.FP_DPP_AMT),0) from ttf_fp tf where tf.TTF_FP_ID in (select TTF_FP_ID from ttf_lines where TTF_ID = th.TTF_ID)),
		    					th.SUM_TAX_FP = (select IFNULL(sum(tf.FP_TAX_AMT),0) from ttf_fp tf where tf.TTF_FP_ID in (select TTF_FP_ID from ttf_lines where TTF_ID = th.TTF_ID)),
		    					th.JUMLAH_BPB = (select count(*) from ttf_lines tl where tl.TTF_ID=th.TTF_ID)
		    					where th.TTF_NUM IN (?)",[$ttf_list]);
            $update = DB::select("UPDATE ttf_headers th set
		    					  th.SUM_DPP_BPB = (
		    					  	select IFNULL(sum(tdb.BPB_DPP),0) from ttf_data_bpb tdb where tdb.BPB_ID in (select tl.TTF_BPB_ID from ttf_lines tl where tl.TTF_ID = th.TTF_ID) AND tdb.VENDOR_SITE_ID IS NOT NULL AND tdb.VENDOR_SITE_ID <> 0 AND tdb.BRANCH_CODE = th.BRANCH_CODE
		    					  ) where th.TTF_NUM IN (?)",[$ttf_list]);

            $update = DB::select("UPDATE ttf_headers th set
		    					    th.SUM_TAX_BPB = (
		    					    	select IFNULL(sum(tdb.BPB_TAX),0) from ttf_data_bpb tdb where tdb.BPB_ID in (select tl.TTF_BPB_ID from ttf_lines tl where tl.TTF_ID = th.TTF_ID) AND tdb.VENDOR_SITE_ID IS NOT NULL AND tdb.VENDOR_SITE_ID <> 0 AND tdb.BRANCH_CODE = th.BRANCH_CODE
		    					    ) where th.TTF_NUM IN (?)",[$ttf_list]);
            $update = DB::select("UPDATE ttf_headers th set
		    					    th.SELISIH_DPP = abs(th.SUM_DPP_FP - th.SUM_DPP_BPB),
		    					    th.SELISIH_TAX = abs(th.SUM_TAX_FP - th.SUM_TAX_BPB) where th.TTF_NUM IN (?)",[$ttf_list]);
            if($update){
                return 1;
            }else{
                return 0;
            }
        },5);


        /*$this->db->query("update ttf_headers a set
        a.TTF_STATUS = (case when (a.SELISIH_DPP + a.SELISIH_TAX) > 0 then 'E' else '' end)
        where a.TTF_STATUS = '' and a.TTF_NUM IN ($ttf_list)");*/
    }
    public function getDetailTtfByTtfId($ttf_id){
        $data = TtfHeader::where('TTF_ID',$ttf_id)
            ->select('TTF_NUM','TTF_DATE','VENDOR_SITE_CODE')
            ->selectRaw("CASE
                            WHEN TTF_TYPE = 1 THEN 'STANDARD'
                            ELSE 'TANPA FAKTUR PAJAK'
                        END AS TIPE_TTF")
            ->selectRaw("(SUM_DPP_FP + SUM_TAX_FP) TOTAL_TTF")
            ->selectRaw("(SELECT 
                            b.SUPP_SITE_ALT_NAME
                        FROM
                            sys_supp_site b
                        WHERE
                            b.SUPP_SITE_CODE = VENDOR_SITE_CODE
                                AND b.SUPP_BRANCH_CODE = BRANCH_CODE) NAMA_SUPPLIER")
            ->selectRaw("(SELECT 
                            CASE
                                    WHEN b.SUPP_TYPE = 'Y' THEN 'PKP'
                                    ELSE 'NON-PKP'
                                END AS TIPE_SUPP
                        FROM
                            sys_supp_site b
                        WHERE
                            b.SUPP_SITE_CODE = VENDOR_SITE_CODE
                                AND b.SUPP_BRANCH_CODE = BRANCH_CODE) SUPP_TYPE")
            ->selectRaw("(SELECT 
                            b.SUPP_PKP_NUM
                        FROM
                            sys_supp_site b
                        WHERE
                            b.SUPP_SITE_CODE = VENDOR_SITE_CODE
                                AND b.SUPP_BRANCH_CODE = BRANCH_CODE) NOMOR_NPWP")
            ->selectRaw("(SELECT 
                            b.SUPP_PKP_ADDR1
                        FROM
                            sys_supp_site b
                        WHERE
                            b.SUPP_SITE_CODE = VENDOR_SITE_CODE
                                AND b.SUPP_BRANCH_CODE = BRANCH_CODE) ALAMAT_SUPPLIER")
            ->get();

        return $data;
    }

    public function deleteTtf($ttf_id){
        $delete = TtfHeader::where('TTF_ID',$ttf_id)->delete();

        if ($delete){
            return 1;
        }else{
            return 0;
        }
    }

    public function getTtfNumByTtfId($ttf_id){
        $data = TtfHeader::where('TTF_ID',$ttf_id)->get();

        // $data = DB::select('SELECT * FROM ttf_headers WHERE TTF_ID = ?',[$ttf_id]);
        // print_r($data);
        return $data;
    }

    public function getPathDirByTtfId($ttf_id){
        $data = TtfHeader::where('TTF_ID',$ttf_id)->select('PATH_NOTTF')->first();

        return $data;
    }

    public function getDataForInquiryTtfDashboard($id_user,$branch_code){
        $data = Ttfheader::select('ttf_headers.TTF_NUM','ttf_headers.BRANCH_CODE','ttf_headers.SELISIH_DPP','ttf_headers.SELISIH_TAX','ttf_headers.CREATION_DATE')
        ->selectRaw("CASE
                          WHEN ttf_headers.TTF_STATUS = '' THEN 'DRAFT'
                          WHEN ttf_headers.TTF_STATUS = 'C' THEN 'CANCEL'
                          WHEN ttf_headers.TTF_STATUS = 'E' THEN 'EXPIRED'
                          WHEN ttf_headers.TTF_STATUS = 'R' THEN 'REJECTED'
                          WHEN ttf_headers.TTF_STATUS = 'S' THEN 'SUBMITTED'
                          WHEN ttf_headers.TTF_STATUS = 'V' THEN 'VALIDATED'
                     END AS TTF_STATUS")
        ->selectRaw('(SELECT 
                                       b.BRANCH_NAME
                                   FROM
                                       sys_ref_branch b
                                   WHERE
                                       b.BRANCH_CODE = ttf_headers.BRANCH_CODE) BRANCH_NAME');
        if($id_user){
            $data = $data->where('ttf_headers.CREATED_BY',$id_user);
        }
        if($branch_code){
            $data = $data->where('ttf_headers.BRANCH_CODE',$branch_code);
        }
        $data = $data->orderBy('ttf_headers.TTF_ID','DESC')->take(10)->get();
        
        return $data;
    }

    public function getCountTtfAndMaxDate($user_id,$role_id){
        $data = TtfHeader::selectRaw('COUNT(*) TOTAL_TTF, MAX(CREATION_DATE) LAST_TTF_DATE');
        if($role_id!=1){
            $data = $data->where('CREATED_BY',$user_id);
        }

        $data = $data->get();

        return $data;
    }

    public function getCountTtfDraftAndSubmitted($user_id,$role_id){
        $data = TtfHeader::selectRaw('COUNT(*) TOTAL_TTF_UNVALIDATED')->where('TTF_STATUS','')->orWhere('TTF_STATUS','S');
        if($role_id!=1){
            $data = $data->where('CREATED_BY',$user_id);
        }

        $data = $data->value('TOTAL_TTF_UNVALIDATED');

        return $data;
    }   

    public function getCountTtfValidated($user_id,$role_id){
        $data = TtfHeader::selectRaw('COUNT(*) TOTAL_TTF_VALIDATED')->where('TTF_STATUS','V');
        if($role_id!=1){
            $data = $data->where('CREATED_BY',$user_id);
        }

        $data = $data->value('TOTAL_TTF_VALIDATED');

        return $data;
    }   
    
    
}
