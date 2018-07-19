<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    /** Variabel From Post */
    $kode_daerah    = $_SESSION['kode_daerah'];

    $no_jurnal	    = $_POST['no_jurnal'];

    /* SQL Query Update */
    $sqlBatal = "UPDATE trs_juhdr SET status='REJECT' WHERE no_jurnal='$no_jurnal' ";

    $sqlBatalSetup = "UPDATE trs_setup_saldo SET status='REJECT' WHERE ref_number='$no_jurnal' ";

    if($kode_daerah==''){

        $pesan 		= "Hanya Untuk Account Yang Memiliki Entitas Daerah";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }elseif($no_jurnal!=""){

        $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $updateBatal = $konek->query($sqlBatal); 
            
            $updateBatalSetup = $konek->query($sqlBatalSetup); 
        
            $konek->commit(); 

        $pesan 		= "Transaksi Telah Dibatalkan";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    } else {

        $pesan 		= "Data Gagal Dirubah";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}