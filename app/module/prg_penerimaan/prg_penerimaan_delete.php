<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $kode_prg_pnr	= $_POST['kode_prg_pnr'];

    /* SQL Query Update */
    $sqlProgram = "UPDATE tm_prg_pnr SET status='REJECT' WHERE kode_prg_pnr='$kode_prg_pnr' ";

    if($kode_prg_pnr!=""){

        /** Menggunakan Transaction Mysql */
        $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $deleteProgram = $konek->query($sqlProgram);

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