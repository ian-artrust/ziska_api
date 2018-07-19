<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_daerah    = $_SESSION['kode_daerah'];

    /** Variabel From Post */
    $npwz	= $_POST['npwz'];

    $kd     = strip_tags($_POST['kode_daerah']);

    /* SQL Query Update */
    $sqlNpwz= "UPDATE tm_donatur SET status='REJECT' WHERE npwz='$npwz' ";

    if($kode_daerah == $kd){

        $deleteMuzaki = $konek->query($sqlNpwz);    

        $pesan 		= "Data Berhasil Dihapus";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);
    
    }elseif($kode_daerah != $kd){

        $pesan 		= "Data Muzaki Daerah Lain Tidak Boleh Dihapus";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }elseif($npwz==""){

        $pesan 		= "Data Tidak Boleh Kosong";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    } else {

        $pesan 		= "Data Gagal Dihapus";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}