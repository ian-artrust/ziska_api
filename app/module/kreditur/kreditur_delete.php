<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $kode_kreditur	= $_POST['kode_kreditur'];

    /* SQL Query Update */
    $sqlPetugas = "UPDATE tm_kreditur SET status='REJECT' WHERE kode_kreditur='$kode_kreditur' ";

    if($kode_kreditur!=""){

        /** Menggunakan Transaction Mysql */
        $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $deletePetugas = $konek->query($sqlPetugas);

        $konek->commit();        

        $pesan 		= "Data Berhasil Dihapus";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    } else {

        $pesan 		= "Data Gagal Dihapus";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}