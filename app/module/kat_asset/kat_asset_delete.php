<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $kode_kat_asset	= $_POST['kode_kat_asset'];

    /* SQL Query Update */

    $sqlCekAsset = "SELECT * FROM tm_asset WHERE kode_kat_asset='$kode_kat_asset'";

    $exe_sqlCekAsset = $konek->query($sqlCekAsset);

    $cekAsset = mysqli_num_rows($exe_sqlCekAsset);

    $sqlKatAsset = "UPDATE tm_kat_asset SET status='REJECT' WHERE kode_kat_asset='$kode_kat_asset' ";

    if($cekAsset > 0){

        $pesan 		= "Kategori Asset Sudah Memiliki Data";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    }elseif($kode_kat_asset!=""){

        /** Menggunakan Transaction Mysql */
        $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $deleteKatAsset = $konek->query($sqlKatAsset);

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