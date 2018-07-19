<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $no_donasi	    = $_POST['no_donasi'];

    $kode_daerah    = $_SESSION['kode_daerah']; 

    /* SQL Query Update */
    $sqlReject = "UPDATE trs_donasi SET status='REJECT' WHERE no_donasi='$no_donasi' ";

    $sqlJurnalReject = "UPDATE trs_juhdr SET status='REJECT' WHERE ref_number='$no_donasi' ";

    if($kode_daerah==''){

        $pesan 		= "Hanya Account Dengan Entitas Daerah Yang Bisa Melakukan Transaksi..!";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }elseif($no_donasi!='') {

        $updateReject = $konek->query($sqlReject);
        
        $updateJurnalReject = $konek->query($sqlJurnalReject);

        $pesan 		= "Donasi Berhasil Dibatalkan";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);
    
    }else{

        $pesan 		= "Donasi Gagal Dibatalkan";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}