<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $no_donasi_kl	    = $_POST['no_donasi_kl'];

    $kode_daerah    = $_SESSION['kode_daerah']; 

    $no_kantor    = $_SESSION['no_kantor']; 

    /* SQL Query Update */
    $sqlReject = "UPDATE trs_donasi_kl SET status='REJECT' WHERE no_donasi='$no_donasi_kl' ";

    // $sqlJurnalReject = "UPDATE trs_juhdr SET status='REJECT' WHERE ref_number='$no_donasi' ";

    if($kode_daerah==''){

        $pesan 		= "Hanya Account Dengan Entitas Daerah Yang Bisa Melakukan Transaksi..!";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }elseif($no_donasi_kl!='') {

        $updateReject = $konek->query($sqlReject);
        
        // $updateJurnalReject = $konek->query($sqlJurnalReject);

        $pesan 		= "Donasi Berhasil Dibatalkan";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);
    
    }else{

        $pesan 		= "Donasi Gagal Dibatalkan";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}