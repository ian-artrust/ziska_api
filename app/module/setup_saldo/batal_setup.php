<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $ref_number	    = $_POST['ref_number'];

    $kode_daerah    = $_SESSION['kode_daerah']; 

    /* SQL Query Update */
    $sqlJurnal  = "UPDATE trs_juhdr SET status='REJECT' WHERE no_jurnal='$ref_number' ";

    $sqlSetup   = "UPDATE trs_setup_saldo SET status='REJECT' WHERE ref_number='$ref_number' ";

    if($kode_daerah==''){

        $pesan 		= "Hanya Account Dengan Entitas Daerah Yang Bisa Melakukan Transaksi..!";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }elseif($ref_number!='') {

        $updateJurnal = $konek->query($sqlJurnal);
        
        $updateSetup = $konek->query($sqlSetup);

        $pesan 		= "Setup Saldo Berhasil Dibatalkan";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);
    
    }else{

        $pesan 		= "Setup Gagal Dibatalkan";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}