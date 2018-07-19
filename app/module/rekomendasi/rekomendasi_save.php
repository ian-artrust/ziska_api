<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";
    
    /** Variabel From Post */
    
    $no_pengajuan 	    = strip_tags($_POST['no_pengajuan']);

    $rekomendasi 	    = strip_tags($_POST['rekomendasi']);
    
    $poin_penilaian 	= strip_tags($_POST['poin_penilaian']);
    
    $asnaf 			    = strip_tags($_POST['asnaf']);

    $sqlCekData = "SELECT * FROM trs_pengajuan WHERE

                    no_pengajuan='$no_pengajuan' AND status='REKOMENDASI' 
                    
                    OR
                    
                    no_pengajuan='$no_pengajuan' AND status='REALISASI'";

    $exe_sqlCekData = $konek->query($sqlCekData);

    $cekData	= mysqli_num_rows($exe_sqlCekData);
     
    /* SQL Query Simpan */    
    $sqlUpdatePengajuan = "UPDATE trs_pengajuan SET 
                                poin_penilaian='$poin_penilaian',
                                rekomendasi='$rekomendasi',  
                                asnaf='$asnaf',
                                status='REKOMENDASI' 
                            WHERE no_pengajuan='$no_pengajuan'";
    
    if($cekData > 0 ){

        $pesan 		= "Data Sudah Direkomendasi / Direalisasi ";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);
    
    }elseif($poin_penilaian=='' OR $rekomendasi==''){

        $pesan 		= "Data Tidak Boleh Kosong ";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }else{

        $rekomendasiPengajuan = $konek->query($sqlUpdatePengajuan);
        
        $pesan 		= "Pengajuan Berhasil Direkomendasikan";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}