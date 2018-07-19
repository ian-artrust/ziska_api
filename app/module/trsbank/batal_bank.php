<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    /** Variabel From Post */
    $kode_daerah = $_SESSION['kode_daerah'];

    $no_batal_bank = $_POST['no_batal_bank'];

    /* SQL Query Update */
    $sqlBatal = "UPDATE trs_juhdr SET status='REJECT' WHERE no_jurnal='$no_batal_bank' ";

    if($kode_daerah==''){

        $pesan 		= "Hanya Untuk Account Yang Memiliki Entitas Daerah";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    } elseif($no_batal_bank==""){

        $pesan 		= "Pilih Transaksi Bank Yang Akan Dibatalkan";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);
        

    } elseif($no_batal_bank!=""){

        $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $updateBatal = $konek->query($sqlBatal); 
        
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