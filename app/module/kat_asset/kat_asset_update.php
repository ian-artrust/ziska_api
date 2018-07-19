<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $kode_kat_asset	= $_POST['kode_kat_asset'];

    $kategori 	    = strip_tags($_POST['kategori']);

    /* SQL Query Update */
    $sqlKatAsset = "UPDATE tm_kat_asset SET

                    kategori='$kategori'
    
                WHERE kode_kat_asset='$kode_kat_asset' ";

    if($kode_kat_asset!=""){

        /** Menggunakan Transaction Mysql */
        $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $updateKatAsset = $konek->query($sqlKatAsset);

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