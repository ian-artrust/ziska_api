<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $no_pengajuan	= $_POST['no_pengajuan'];

    /* SQL Query Update */

    $sqlUpdatePengajuan = "UPDATE trs_pengajuan SET 
                                status='REJECT' 
                            WHERE no_pengajuan='$no_pengajuan'";

    if($no_pengajuan==''){

        $pesan 		= "Data Tidak Boleh Kosong";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    } else {
        
        $updatePengajuan = $konek->query($sqlUpdatePengajuan);

        $pesan 		= "Data Berhasil Dihapus";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    }

}