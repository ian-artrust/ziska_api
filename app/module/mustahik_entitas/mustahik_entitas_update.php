<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
                
    $kode_daerah	= $_SESSION['kode_daerah'];

    $no_kantor	    = $_SESSION['no_kantor'];

    $no_registrasi 	= strip_tags($_POST['no_registrasi']);

    $nama_lembaga 	= strip_tags($_POST['nama_lembaga']);

    $no_hp 			= strip_tags($_POST['no_hp']);
    
    $no_sk 		    = strip_tags($_POST['no_sk']);
    
    $alamat 		= strip_tags($_POST['alamat']);

    /* SQL Query Update */
    $sqlMustahik = "UPDATE tm_mustahik_entitas SET

            nama_lembaga='$nama_lembaga',

            no_hp='$no_hp',
    
            no_sk='$no_sk',
    
            alamat='$alamat'
    
        WHERE no_registrasi='$no_registrasi' ";

    if($kode_daerah!=''){

        $updateMustahik = $konek->query($sqlMustahik);    

        $pesan 		= "Data Berhasil Dirubah";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    } else {

        $pesan 		= "DHanya Untuk Account Yang Memiliki Entitas Daerah";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}