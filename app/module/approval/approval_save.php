<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";
    
    /** Variabel From Post */
    
    $no_pengajuan 	    = strip_tags($_POST['no_pengajuan']);

    $catatan_direktur   = strip_tags($_POST['catatan_direktur']);

    $sumber_dana 	    = strip_tags($_POST['sumber_dana']);
    
    $status 		    = strip_tags($_POST['status']);
    
    $jml_realisasi      = $_POST['jml_realisasi'];
    
    $tgl_realisasi 	    = strip_tags($_POST['tgl_realisasi']);

    $approved           = date('Y-m-d');
    
    $approved_by        = $_SESSION['kode_petugas'];    
     
    /* SQL Query Simpan */
    $sqlRealisasi = "UPDATE trs_pengajuan SET 

                                jml_realisasi='$jml_realisasi',

                                tgl_realisasi='$tgl_realisasi',

                                sumber_dana='$sumber_dana',

                                catatan_direktur='$catatan_direktur', 

                                status='$status', 

                                approved_by='$approved_by' 
                                
                            WHERE no_pengajuan='$no_pengajuan'";
    
    // $rekomendasiPengajuan = $konek->query($sqlUpdatePengajuan);

    if($status!='REALISASI'){

        $konek->query($sqlRealisasi);

        $pesan 		= "Pengajuan Ditolak";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }else{

        if ($jml_realisasi=='' OR $tgl_realisasi=='') {
           
            $pesan 		= "Jumlah dan Tanggal Realisasi Tidak Boleh Kosong";
        
            $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
        
            echo json_encode($response);

        } else {

            $konek->query($sqlRealisasi);
    
            $pesan 		= "Pengajuan Telah Direalisasi";
            
            $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
        
            echo json_encode($response);

        }

    }

   

}