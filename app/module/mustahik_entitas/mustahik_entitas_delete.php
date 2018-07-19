<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $no_registrasi 	= strip_tags($_POST['no_registrasi']);

    $kode_daerah = $_SESSION['kode_daerah'];

    /* SQL Query Update */
    $sqlMustahik= "UPDATE tm_mustahik_entitas SET status='REJECT' WHERE no_registrasi='$no_registrasi' ";

    if($kode_daerah!=''){

        $deleteMustahik = $konek->query($sqlMustahik);    

        $pesan 		= "Data Berhasil Dihapus";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    } else {

        $pesan 		= "Hanya Untuk Account Yang Memiliki Entitas Daerah";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}