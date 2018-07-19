<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $kode_kreditur	= $_POST['kode_kreditur'];

    $nama_kreditur 	= strip_tags($_POST['nama_kreditur']);
    
    $alamat 		= strip_tags($_POST['alamat']);
    
    $no_hp 			= strip_tags($_POST['no_hp']);
    
    /* SQL Query Update */
    $sqlKreditur = "UPDATE tm_kreditur SET

            nama_kreditur='$nama_kreditur',
    
            alamat='$alamat',
    
            no_hp='$no_hp'
    
        WHERE kode_kreditur='$kode_kreditur' ";

    if($kode_kreditur!=""){

        /** Menggunakan Transaction Mysql */
        $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $updateKreditur = $konek->query($sqlKreditur);

        $konek->commit();        

        $pesan 		= "Data Berhasil Dirubah";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    } else {

        $pesan 		= "Data Gagal Dirubah";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}