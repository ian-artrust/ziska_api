<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $npwz	= $_POST['npwz'];

    $no_kantor 		= $_SESSION['no_kantor'];

    $nk 		    = strip_tags($_POST['no_kantor']);

    /* SQL Query Update */
    $sqlNpwz= "UPDATE tm_donatur SET status='REJECT' WHERE npwz='$npwz' ";

    if($nk == $no_kantor){

        $deleteMuzaki = $konek->query($sqlNpwz);    

        $pesan 		= "Data Berhasil Dihapus";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    }elseif($nk != $no_kantor){

        $pesan 		= "Data Muzaki Kantor Layanan Lain Tidak Boleh Dihapus";
        
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